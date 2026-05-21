<?php

namespace App\Services;

use App\Models\Production;
use App\Models\Recipe;
use App\Models\RawMaterial;
use App\Models\ProductionMaterial;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionSchedulerService
{
    private int $populationSize = 50;
    private float $crossoverRate = 0.8;
    private float $mutationRate = 0.1;
    private int $maxGenerations = 100;
    private float $targetDeadlineWeight = 40;
    private float $materialExpiryWeight = 30;
    private float $machineEfficiencyWeight = 30;
    private int $baseProductionDuration = 480;

    public function generateOptimalSchedule(): array
    {
        $pendingProductions = $this->getPendingProductions();

        if ($pendingProductions->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Tidak ada produksi dengan status pending untuk dijadwalkan.',
                'productions' => [],
            ];
        }

        $this->loadMaterialData($pendingProductions);
        $this->calculatePriorityLevels($pendingProductions);

        $population = $this->initializePopulation($pendingProductions);
        $bestSchedule = null;
        $bestFitness = PHP_FLOAT_MAX;
        $generation = 0;

        while ($generation < $this->maxGenerations) {
            $fitnessScores = [];
            foreach ($population as $chromosome) {
                $fitnessScores[] = $this->calculateFitness($chromosome, $pendingProductions);
            }

            $minFitness = min($fitnessScores);
            $minIndex = array_search($minFitness, $fitnessScores);
            if ($minFitness < $bestFitness) {
                $bestFitness = $minFitness;
                $bestSchedule = $population[$minIndex];
            }

            $selected = $this->selection($population, $fitnessScores);
            $population = $this->crossover($selected, $pendingProductions);
            $this->mutate($population, $pendingProductions);

            $generation++;
        }

        $this->applyScheduleToProductions($bestSchedule, $pendingProductions);

        return [
            'success' => true,
            'message' => "Jadwal berhasil dioptimalkan dalam {$generation} generasi. Fitness score: " . number_format($bestFitness, 2),
            'productions' => $pendingProductions,
            'fitness_score' => $bestFitness,
            'generations' => $generation,
        ];
    }

    private function getPendingProductions(): Collection
    {
        return Production::with(['product', 'productionMaterials.rawMaterial'])
            ->where('status', 'pending')
            ->get();
    }

    private function loadMaterialData(Collection $productions): void
    {
        $rawMaterials = RawMaterial::with('recipes')->get()->keyBy('id');
    }

    private function calculatePriorityLevels(Collection $productions): void
    {
        $productions->each(function (Production $production) {
            $priority = 50;

            if ($production->target_date) {
                $daysUntilDue = Carbon::now()->diffInDays($production->target_date, false);
                if ($daysUntilDue < 0) {
                    $priority += 30;
                } elseif ($daysUntilDue <= 3) {
                    $priority += 20;
                } elseif ($daysUntilDue <= 7) {
                    $priority += 10;
                }
            }

            $recipes = Recipe::where('product_id', $production->product_id)->with('rawMaterial')->get();
            $minExpiryDays = PHP_INT_MAX;
            foreach ($recipes as $recipe) {
                $material = $recipe->rawMaterial;
                if ($material && $material->expired_date) {
                    $expiryDays = Carbon::now()->diffInDays($material->expired_date, false);
                    if ($expiryDays < $minExpiryDays) {
                        $minExpiryDays = $expiryDays;
                    }
                }
            }

            if ($minExpiryDays < PHP_INT_MAX) {
                if ($minExpiryDays <= 7) {
                    $priority += 20;
                } elseif ($minExpiryDays <= 14) {
                    $priority += 10;
                }
            }

            $production->priority_level = min(100, $priority);
        });
    }

    private function initializePopulation(Collection $productions): array
    {
        $population = [];
        $ids = $productions->pluck('id')->toArray();

        for ($i = 0; $i < $this->populationSize; $i++) {
            $chromosome = $ids;
            shuffle($chromosome);
            $population[] = $chromosome;
        }

        return $population;
    }

    private function calculateFitness(array $chromosome, Collection $productions): float
    {
        $productionsById = $productions->keyBy('id');
        $fitness = 0.0;
        $currentTime = Carbon::now()->startOfDay();
        $usedMachineSlots = [];

        foreach ($chromosome as $index => $productionId) {
            $production = $productionsById->get($productionId);
            if (!$production) continue;

            $duration = $this->estimateDuration($production);
            $fitness += $this->calculateDeadlinePenalty($production, $currentTime, $duration);
            $fitness += $this->calculateMaterialExpiryPenalty($production, $currentTime);
            $fitness += $this->calculateMachineEfficiencyPenalty($production, $usedMachineSlots, $currentTime);

            $currentTime->addMinutes($duration);
        }

        return $fitness;
    }

    private function estimateDuration(Production $production): int
    {
        $targetQty = $production->target_quantity ?? 100;
        $recipes = Recipe::where('product_id', $production->product_id)->get();
        $materialCount = $recipes->count();

        $complexityFactor = 1 + ($materialCount * 0.1);
        $quantityFactor = $targetQty / 100;

        $duration = (int) ($this->baseProductionDuration * $complexityFactor * $quantityFactor);

        $production->estimated_duration = $duration;
        return $duration;
    }

    private function calculateDeadlinePenalty(Production $production, Carbon $startTime, int $duration): float
    {
        $endTime = (clone $startTime)->addMinutes($duration);
        $penalty = 0.0;

        if ($production->target_date) {
            $targetDate = Carbon::parse($production->target_date);
            $daysLate = $startTime->diffInDays($targetDate, false);

            if ($daysLate < 0) {
                $penalty = abs($daysLate) * $this->targetDeadlineWeight * 10;
            } elseif ($daysLate <= 3) {
                $penalty = abs($daysLate) * $this->targetDeadlineWeight * 2;
            }
        }

        return $penalty;
    }

    private function calculateMaterialExpiryPenalty(Production $production, Carbon $startTime): float
    {
        $penalty = 0.0;
        $recipes = Recipe::where('product_id', $production->product_id)->with('rawMaterial')->get();

        foreach ($recipes as $recipe) {
            $material = $recipe->rawMaterial;
            if ($material && $material->expired_date) {
                $expiryDate = Carbon::parse($material->expired_date);
                $daysUntilExpiry = $startTime->diffInDays($expiryDate, false);

                if ($daysUntilExpiry < 0) {
                    $penalty += 100;
                } elseif ($daysUntilExpiry <= 7) {
                    $penalty += (7 - $daysUntilExpiry) * $this->materialExpiryWeight;
                }
            }
        }

        return $penalty;
    }

    private function calculateMachineEfficiencyPenalty(Production $production, array &$usedMachineSlots, Carbon $startTime): float
    {
        $penalty = 0.0;
        $productionType = $production->product->jeniss ?? 'general';

        $dayKey = $startTime->toDateString();
        if (!isset($usedMachineSlots[$dayKey])) {
            $usedMachineSlots[$dayKey] = [];
        }

        if (!isset($usedMachineSlots[$dayKey][$productionType])) {
            $usedMachineSlots[$dayKey][$productionType] = 0;
        }

        $usedMachineSlots[$dayKey][$productionType]++;
        $maxCapacity = 5;

        if ($usedMachineSlots[$dayKey][$productionType] > $maxCapacity) {
            $penalty = ($usedMachineSlots[$dayKey][$productionType] - $maxCapacity) * $this->machineEfficiencyWeight * 5;
        }

        return $penalty;
    }

    private function selection(array $population, array $fitnessScores): array
    {
        $selected = [];
        $totalFitness = array_sum($fitnessScores);

        if ($totalFitness <= 0) {
            return array_slice($population, 0, (int) ($this->populationSize * 0.5));
        }

        for ($i = 0; $i < $this->populationSize; $i++) {
            $random = mt_rand() / mt_getrandmax() * $totalFitness;
            $cumulative = 0.0;

            foreach ($fitnessScores as $index => $fitness) {
                $cumulative += $fitness;
                if ($cumulative >= $random) {
                    $selected[] = $population[$index];
                    break;
                }
            }

            if (count($selected) <= $i) {
                $selected[] = $population[array_key_first($fitnessScores)];
            }
        }

        return $selected;
    }

    private function crossover(array $population, Collection $productions): array
    {
        $newPopulation = [];

        while (count($newPopulation) < $this->populationSize) {
            $parent1 = $population[array_rand($population)];
            $parent2 = $population[array_rand($population)];

            if ((mt_rand() / mt_getrandmax()) < $this->crossoverRate) {
                $child = $this->orderCrossover($parent1, $parent2);
            } else {
                $child = $parent1;
            }

            $newPopulation[] = $child;
        }

        return $newPopulation;
    }

    private function orderCrossover(array $parent1, array $parent2): array
    {
        $size = count($parent1);
        $start = mt_rand(0, $size - 1);
        $end = mt_rand($start, $size - 1);

        $child = array_fill(0, $size, null);
        for ($i = $start; $i <= $end; $i++) {
            $child[$i] = $parent1[$i];
        }

        $parent2Remaining = array_filter($parent2, fn($id) => !in_array($id, array_slice($child, $start, $end - $start + 1)));
        $childIndex = 0;

        foreach ($parent2Remaining as $id) {
            while ($child[$childIndex] !== null) {
                $childIndex++;
            }
            $child[$childIndex] = $id;
        }

        return array_filter($child);
    }

    private function mutate(array &$population, Collection $productions): void
    {
        foreach ($population as &$chromosome) {
            if ((mt_rand() / mt_getrandmax()) < $this->mutationRate) {
                $size = count($chromosome);
                if ($size >= 2) {
                    $pos1 = mt_rand(0, $size - 1);
                    $pos2 = mt_rand(0, $size - 1);
                    $temp = $chromosome[$pos1];
                    $chromosome[$pos1] = $chromosome[$pos2];
                    $chromosome[$pos2] = $temp;
                }
            }
        }
    }

    private function applyScheduleToProductions(array $chromosome, Collection $productions): void
    {
        $productionsById = $productions->keyBy('id');
        $currentTime = Carbon::now()->startOfDay()->addHours(8);
        $usedMachineSlots = [];

        foreach ($chromosome as $productionId) {
            $production = $productionsById->get($productionId);
            if (!$production) continue;

            $duration = $production->estimated_duration ?? $this->estimateDuration($production);
            $endTime = (clone $currentTime)->addMinutes($duration);

            $production->scheduled_start = $currentTime;
            $production->scheduled_end = $endTime;
            $production->algorithm_generated = true;
            $production->schedule_notes = "Dijadwalkan oleh algoritma genetika. Prioritas: {$production->priority_level}";
            $production->fitness_data = [
                'deadline_score' => $this->calculateDeadlinePenalty($production, $currentTime, $duration),
                'material_score' => $this->calculateMaterialExpiryPenalty($production, $currentTime),
                'machine_score' => $this->calculateMachineEfficiencyPenalty($production, $usedMachineSlots, $currentTime),
            ];

            $production->save();

            $currentTime = $endTime->addHours(2);
        }
    }

    public function getScheduledProductions(): Collection
    {
        return Production::with(['product', 'productionMaterials.rawMaterial'])
            ->where('algorithm_generated', true)
            ->whereNull('start_date')
            ->orderBy('scheduled_start')
            ->get();
    }

    public function approveSchedule(array $productionIds): array
    {
        $productions = Production::with('productionMaterials.rawMaterial')
            ->whereIn('id', $productionIds)
            ->where('status', 'pending')
            ->get();

        if ($productions->isEmpty()) {
            $exists = Production::whereIn('id', $productionIds)->exists();
            $msg = $exists
                ? 'Batch yang dipilih tidak dalam status pending atau sudah diproses.'
                : 'Batch yang dipilih tidak ditemukan.';
            Log::warning('approveSchedule: no pending productions found', ['ids' => $productionIds]);
            return ['success' => false, 'message' => $msg];
        }

        $stockIssues = $this->checkStockForProductions($productions);
        if (!empty($stockIssues)) {
            Log::warning('approveSchedule: stock insufficient', ['issues' => $stockIssues]);
            return [
                'success' => false,
                'message' => 'Stok bahan baku tidak mencukupi untuk batch ini: ' . implode('; ', $stockIssues),
                'stock_warnings' => $stockIssues,
            ];
        }

        try {
            DB::beginTransaction();

            foreach ($productions as $production) {
                $updateData = [
                    'start_date' => $production->scheduled_start ?? now(),
                    'end_date'   => $production->scheduled_end,
                    'status'     => 'in_progress',
                ];

                if (!$production->scheduled_start) {
                    Log::info("approveSchedule: {$production->batch_number} has no scheduled_start, using now()");
                }

                $updated = $production->update($updateData);

                if (!$updated) {
                    throw new \Exception("Gagal mengupdate batch {$production->batch_number}.");
                }

                $this->deductMaterialStock($production);
            }

            DB::commit();

            Log::info("approveSchedule: {$productions->count()} batches approved");
            return [
                'success' => true,
                'message' => "Penjadwalan berhasil disetujui. {$productions->count()} batch kini On Progress.",
                'count'   => $productions->count(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('approveSchedule exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return [
                'success' => false,
                'message' => 'Penjadwalan gagal: ' . $e->getMessage(),
            ];
        }
    }

    public function checkStockAvailability(Collection $productions): array
    {
        $issues = [];

        foreach ($productions as $production) {
            $production->loadMissing('productionMaterials.rawMaterial');
            foreach ($production->productionMaterials as $pm) {
                $material = $pm->rawMaterial;
                if ($material && $material->current_stock < $pm->quantity_used) {
                    $issues[] = "{$material->name} (stok: {$material->current_stock}, kebutuhan batch {$production->batch_number}: {$pm->quantity_used})";
                }
            }
        }

        return $issues;
    }

    private function checkStockForProductions(Collection $productions): array
    {
        $issues = [];

        foreach ($productions as $production) {
            foreach ($production->productionMaterials as $pm) {
                $material = $pm->rawMaterial;
                if ($material && $material->current_stock < $pm->quantity_used) {
                    $issues[] = "{$material->name} untuk batch {$production->batch_number} (stok: {$material->current_stock}, butuh: {$pm->quantity_used})";
                }
            }
        }

        return array_unique($issues);
    }

    private function deductMaterialStock(Production $production): void
    {
        foreach ($production->productionMaterials as $pm) {
            $material = $pm->rawMaterial;
            if ($material) {
                $material->decrement('current_stock', $pm->quantity_used);
            }
        }
    }

    public function resetSchedule(array $productionIds): array
    {
        $productions = Production::whereIn('id', $productionIds)->get();

        foreach ($productions as $production) {
            $production->algorithm_generated = false;
            $production->scheduled_start = null;
            $production->scheduled_end = null;
            $production->schedule_notes = null;
            $production->fitness_data = null;
            $production->save();
        }

        return [
            'success' => true,
            'message' => "Berhasil mereset {$productions->count()} jadwal.",
        ];
    }

    public function getSchedulingStats(): array
    {
        $pending = Production::where('status', 'pending')->count();
        $scheduled = Production::where('algorithm_generated', true)->whereNull('start_date')->count();
        $totalPriority = Production::where('status', 'pending')
            ->whereNotNull('priority_level')
            ->avg('priority_level') ?? 0;

        return [
            'pending_count' => $pending,
            'scheduled_count' => $scheduled,
            'average_priority' => round($totalPriority, 1),
        ];
    }
}
