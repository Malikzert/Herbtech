<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Recipe;
use App\Models\RawMaterial;
use App\Models\QualityControl;
use App\Models\Production;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneticBatchRecommender
{
    private const TARGET_BATCH_COUNT = 10;
    private const POPULATION_SIZE = 80;
    private const MAX_GENERATIONS = 50;
    private const CROSSOVER_RATE = 0.8;
    private const MUTATION_RATE = 0.15;
    private const TOURNAMENT_SIZE = 3;

    private Collection $products;
    private Collection $recipes;
    private Collection $materials;
    private array $materialCache;
    private array $rejectRateCache;
    private array $fefoScore;
    private array $stockScore;
    private array $productRecipeMaterials;

    public function __construct()
    {
        $this->materialCache = [];
        $this->rejectRateCache = [];
        $this->fefoScore = [];
        $this->stockScore = [];
        $this->productRecipeMaterials = [];
    }

    public function recommend(): array
    {
        $this->loadData();
        $this->calculateScores();

        $productIds = $this->products->pluck('id')->values()->toArray();

        if (empty($productIds)) {
            return $this->emptyResult('Tidak ada produk tersedia untuk dijadwalkan.');
        }

        $candidates = $this->filterCandidates($productIds);
        $criticalIds = $this->identifyCriticalProducts($productIds);

        if (count($candidates) <= self::TARGET_BATCH_COUNT) {
            return $this->buildDirectResult($candidates, $criticalIds, $productIds);
        }

        $bestChromosome = $this->runGeneticAlgorithm($candidates);

        return $this->buildResult($bestChromosome, $criticalIds, $productIds);
    }

    private function loadData(): void
    {
        $this->products = Product::with('recipes.rawMaterial')->get();

        $this->materials = RawMaterial::all()->keyBy('id');

        $this->recipes = Recipe::with('rawMaterial')->get();

        foreach ($this->products as $product) {
            $mats = [];
            foreach ($product->recipes as $recipe) {
                $material = $recipe->rawMaterial;
                if ($material) {
                    $mats[] = [
                        'material_id' => $material->id,
                        'name' => $material->name,
                        'quantity_needed' => (float) $recipe->quantity_needed,
                        'current_stock' => (float) $material->current_stock,
                        'expired_date' => $material->expired_date,
                        'unit' => $recipe->unit,
                    ];
                }
            }
            $this->productRecipeMaterials[$product->id] = $mats;
        }
    }

    private function calculateScores(): void
    {
        foreach ($this->products as $product) {
            $materials = $this->productRecipeMaterials[$product->id] ?? [];

            if (empty($materials)) {
                $this->fefoScore[$product->id] = 0;
                $this->stockScore[$product->id] = 0;
                continue;
            }

            $minExpiryDays = PHP_INT_MAX;
            $hasExpiryData = false;
            $totalStock = 0;

            foreach ($materials as $mat) {
                $totalStock += $mat['current_stock'];

                if (!empty($mat['expired_date'])) {
                    $hasExpiryData = true;
                    $daysToExpiry = Carbon::now()->diffInDays(Carbon::parse($mat['expired_date']), false);
                    if ($daysToExpiry < $minExpiryDays) {
                        $minExpiryDays = $daysToExpiry;
                    }
                }
            }

            $this->fefoScore[$product->id] = $hasExpiryData ? $this->normalizeFefo($minExpiryDays) : 50;
            $this->stockScore[$product->id] = $this->normalizeStock($totalStock, $materials);
        }
    }

    private function normalizeFefo(int $minExpiryDays): float
    {
        if ($minExpiryDays <= 0) return 100;
        if ($minExpiryDays <= 7) return max(20, 100 - ($minExpiryDays * 10));
        if ($minExpiryDays <= 30) return max(10, 30 - $minExpiryDays);
        return 5;
    }

    private function normalizeStock(float $totalStock, array $materials): float
    {
        $totalNeeded = array_sum(array_column($materials, 'quantity_needed'));
        if ($totalNeeded <= 0) return 0;

        $ratio = $totalStock / $totalNeeded;
        if ($ratio >= 5) return 100;
        if ($ratio >= 3) return 80;
        if ($ratio >= 1.5) return 60;
        if ($ratio >= 1) return 40;
        if ($ratio >= 0.5) return 20;
        return 10;
    }

    private function filterCandidates(array $productIds): array
    {
        $candidates = [];

        foreach ($productIds as $id) {
            $materials = $this->productRecipeMaterials[$id] ?? [];
            if (empty($materials)) continue;

            $allSufficient = true;
            foreach ($materials as $mat) {
                if ($mat['current_stock'] < $mat['quantity_needed']) {
                    $allSufficient = false;
                    break;
                }
            }

            if ($allSufficient) {
                $candidates[] = $id;
            }
        }

        return $candidates;
    }

    private function identifyCriticalProducts(array $allIds): array
    {
        $critical = [];

        foreach ($allIds as $id) {
            if (in_array($id, $this->filterCandidates($allIds))) continue;

            $materials = $this->productRecipeMaterials[$id] ?? [];
            if (empty($materials)) {
                $critical[$id] = 'Produk ini belum memiliki resep (BOM) yang lengkap.';
                continue;
            }

            $rejectRate = $this->getProductRejectRate($id);
            if ($rejectRate > 30) {
                $critical[$id] = "Batch produk [{$this->getProductName($id)}] tidak direkomendasikan karena berdasarkan riwayat batch sebelumnya, produk ini mengalami tingkat kegagalan (reject rate) yang tinggi pada proses Quality Control akhir-akhir ini.";
                continue;
            }

            foreach ($materials as $mat) {
                if ($mat['current_stock'] < $mat['quantity_needed']) {
                    $critical[$id] = "Batch produk [{$this->getProductName($id)}] tidak direkomendasikan karena stok bahan baku kritis [{$mat['name']}] tidak mencukupi untuk produksi.";
                    break;
                }
            }

            if (!isset($critical[$id])) {
                $critical[$id] = "Batch produk [{$this->getProductName($id)}] tidak direkomendasikan karena stok bahan baku tidak mencukupi untuk memenuhi kebutuhan produksi.";
            }
        }

        return $critical;
    }

    private function getProductRejectRate(int $productId): float
    {
        if (isset($this->rejectRateCache[$productId])) {
            return $this->rejectRateCache[$productId];
        }

        $rate = QualityControl::whereHas('production', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->selectRaw('COALESCE(AVG(total_rejected * 100.0 / NULLIF(total_inspected, 0)), 0) as reject_rate')
            ->value('reject_rate');

        $this->rejectRateCache[$productId] = (float) $rate;
        return $this->rejectRateCache[$productId];
    }

    private function getProductName(int $productId): string
    {
        $product = $this->products->firstWhere('id', $productId);
        return $product ? $product->name : 'Unknown';
    }

    private function buildDirectResult(array $candidates, array $criticalIds, array $allIds): array
    {
        $recommended = [];
        $simulatedStock = $this->buildStockMap();

        foreach ($candidates as $id) {
            $fitness = $this->calculateProductFitness($id);
            $recommended[] = $this->buildBatchItem($id, $fitness, $simulatedStock);
        }

        usort($recommended, fn($a, $b) => $b['fitness_score'] <=> $a['fitness_score']);

        $notRecommended = $this->buildNotRecommended($candidates, $criticalIds, $allIds, $simulatedStock);

        return [
            'success' => true,
            'recommended_batches' => $recommended,
            'not_recommended_batches' => $notRecommended,
            'remaining_stock' => $this->formatStockMap($simulatedStock),
            'generations' => 0,
            'message' => 'Rekomendasi langsung berdasarkan ketersediaan stok dan skor kebugaran.',
        ];
    }

    private function runGeneticAlgorithm(array $candidates): array
    {
        $population = $this->initializePopulation($candidates);
        $bestChromosome = null;
        $bestFitness = 0;
        $stagnantCount = 0;

        for ($gen = 0; $gen < self::MAX_GENERATIONS; $gen++) {
            $fitnessScores = [];
            foreach ($population as $chromosome) {
                $fitnessScores[] = $this->calculateChromosomeFitness($chromosome);
            }

            $maxFitness = max($fitnessScores);
            $maxIndex = array_search($maxFitness, $fitnessScores);

            if ($maxFitness > $bestFitness) {
                $bestFitness = $maxFitness;
                $bestChromosome = $population[$maxIndex];
                $stagnantCount = 0;
            } else {
                $stagnantCount++;
            }

            if ($stagnantCount >= 15) break;

            $selected = $this->tournamentSelection($population, $fitnessScores);
            $population = $this->crossover($selected);
            $this->mutate($population, $candidates);
        }

        return $bestChromosome ?? $this->getTopCandidates($candidates);
    }

    private function initializePopulation(array $candidates): array
    {
        $population = [];
        $maxCount = min(self::TARGET_BATCH_COUNT, count($candidates));

        for ($i = 0; $i < self::POPULATION_SIZE; $i++) {
            $shuffled = $candidates;
            shuffle($shuffled);
            $population[] = array_slice($shuffled, 0, $maxCount);
        }

        return $population;
    }

    private function calculateProductFitness(int $productId): float
    {
        $fefo = $this->fefoScore[$productId] ?? 0;
        $stock = $this->stockScore[$productId] ?? 0;
        return ($fefo * 0.6) + ($stock * 0.4);
    }

    private function calculateChromosomeFitness(array $chromosome): float
    {
        if (empty($chromosome)) return 0;

        $usedMaterials = [];
        $totalFitness = 0;
        $productTypeCount = [];
        $uniqueProducts = array_unique($chromosome);

        foreach ($uniqueProducts as $productId) {
            $fitness = $this->calculateProductFitness($productId);

            $materials = $this->productRecipeMaterials[$productId] ?? [];
            $stockConflict = false;

            foreach ($materials as $mat) {
                $needed = $mat['quantity_needed'];
                $available = $mat['current_stock'] - ($usedMaterials[$mat['material_id']] ?? 0);

                if ($available < $needed) {
                    $stockConflict = true;
                    break;
                }
                $usedMaterials[$mat['material_id']] = ($usedMaterials[$mat['material_id']] ?? 0) + $needed;
            }

            if ($stockConflict) {
                $fitness *= 0.3;
            }

            $totalFitness += $fitness;

            $product = $this->products->firstWhere('id', $productId);
            $type = $product ? $product->jeniss : 'general';
            $productTypeCount[$type] = ($productTypeCount[$type] ?? 0) + 1;
        }

        $varietyBonus = $this->calculateVarietyBonus($productTypeCount);
        $totalFitness += $varietyBonus;

        return $totalFitness;
    }

    private function calculateVarietyBonus(array $typeCount): float
    {
        $total = array_sum($typeCount);
        if ($total <= 0) return 0;

        $uniqueTypes = count($typeCount);
        $typeRatio = $uniqueTypes / max(1, $total);

        $maxType = max($typeCount);
        $dominancePenalty = ($maxType / max(1, $total) > 0.5) ? -20 : 0;

        return ($typeRatio * 50) + $dominancePenalty;
    }

    private function tournamentSelection(array $population, array $fitnessScores): array
    {
        $selected = [];
        $popSize = count($population);

        for ($i = 0; $i < $popSize; $i++) {
            $bestIdx = null;
            $bestFitness = -1;

            for ($j = 0; $j < self::TOURNAMENT_SIZE; $j++) {
                $idx = mt_rand(0, $popSize - 1);
                if ($fitnessScores[$idx] > $bestFitness) {
                    $bestFitness = $fitnessScores[$idx];
                    $bestIdx = $idx;
                }
            }

            if ($bestIdx !== null) {
                $selected[] = $population[$bestIdx];
            }
        }

        return $selected;
    }

    private function crossover(array $population): array
    {
        $newPopulation = [];
        $popSize = count($population);

        for ($i = 0; $i < $popSize; $i++) {
            $parent1 = $population[$i];
            $parent2 = $population[mt_rand(0, $popSize - 1)];

            if (mt_rand() / mt_getrandmax() < self::CROSSOVER_RATE) {
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
        $size = min(count($parent1), self::TARGET_BATCH_COUNT);
        if ($size <= 1) return $parent1;

        $start = mt_rand(0, $size - 2);
        $end = mt_rand($start + 1, $size - 1);
        $segmentLength = $end - $start + 1;

        $child = array_fill(0, $size, null);
        for ($i = $start; $i <= $end; $i++) {
            $child[$i] = $parent1[$i];
        }

        $segmentValues = array_slice($child, $start, $segmentLength);
        $remaining = array_values(array_filter($parent2, fn($id) => !in_array($id, $segmentValues)));

        $childIdx = 0;
        $remainingIdx = 0;

        while ($childIdx < $size) {
            if ($child[$childIdx] === null) {
                $child[$childIdx] = $remaining[$remainingIdx] ?? $parent1[$childIdx];
                $remainingIdx++;
            }
            $childIdx++;
        }

        return array_values(array_filter($child, fn($v) => $v !== null));
    }

    private function mutate(array &$population, array $candidates): void
    {
        $maxCount = min(self::TARGET_BATCH_COUNT, count($candidates));

        foreach ($population as &$chromosome) {
            if (mt_rand() / mt_getrandmax() < self::MUTATION_RATE) {
                $pos = mt_rand(0, count($chromosome) - 1);
                $newId = $candidates[mt_rand(0, count($candidates) - 1)];
                $chromosome[$pos] = $newId;
            }
        }
    }

    private function getTopCandidates(array $candidates): array
    {
        $scored = [];
        foreach ($candidates as $id) {
            $scored[$id] = $this->calculateProductFitness($id);
        }
        arsort($scored);

        $maxCount = min(self::TARGET_BATCH_COUNT, count($scored));
        return array_keys(array_slice($scored, 0, $maxCount, true));
    }

    private function buildResult(array $chromosome, array $criticalIds, array $allIds): array
    {
        $recommended = [];
        $simulatedStock = $this->buildStockMap();

        foreach ($chromosome as $id) {
            $fitness = $this->calculateProductFitness($id);
            $recommended[] = $this->buildBatchItem($id, $fitness, $simulatedStock);
        }

        usort($recommended, fn($a, $b) => $b['fitness_score'] <=> $a['fitness_score']);

        $recommendedIds = array_column($recommended, 'product_id');
        $notRecommended = $this->buildNotRecommended($recommendedIds, $criticalIds, $allIds, $simulatedStock);

        return [
            'success' => true,
            'recommended_batches' => $recommended,
            'not_recommended_batches' => $notRecommended,
            'remaining_stock' => $this->formatStockMap($simulatedStock),
            'generations' => self::MAX_GENERATIONS,
            'message' => 'Rekomendasi batch berhasil dioptimalkan menggunakan Algoritma Genetika.',
        ];
    }

    private function buildBatchItem(int $productId, float $fitness, array &$simulatedStock): array
    {
        $product = $this->products->firstWhere('id', $productId);
        $materials = $this->productRecipeMaterials[$productId] ?? [];

        $materialDetails = [];
        foreach ($materials as $mat) {
            $materialDetails[] = [
                'raw_material_id' => $mat['material_id'],
                'name' => $mat['name'],
                'quantity_needed' => $mat['quantity_needed'],
                'unit' => $mat['unit'],
                'current_stock_before' => $mat['current_stock'],
                'current_stock_after' => $simulatedStock[$mat['material_id']] ?? $mat['current_stock'],
            ];

            if (isset($simulatedStock[$mat['material_id']])) {
                $simulatedStock[$mat['material_id']] -= $mat['quantity_needed'];
            }
        }

        return [
            'product_id' => $productId,
            'product_name' => $product ? $product->name : 'Unknown',
            'product_category' => $product ? $product->jeniss : null,
            'fitness_score' => round($fitness, 2),
            'fefo_score' => round($this->fefoScore[$productId] ?? 0, 2),
            'stock_score' => round($this->stockScore[$productId] ?? 0, 2),
            'reject_rate' => round($this->getProductRejectRate($productId), 2),
            'materials' => $materialDetails,
            'is_recommended' => true,
        ];
    }

    private function buildNotRecommended(array $recommendedIds, array $criticalIds, array $allIds, array &$simulatedStock): array
    {
        $notRecommended = [];
        $remainingSlots = self::TARGET_BATCH_COUNT - count($recommendedIds);

        if ($remainingSlots <= 0) return $notRecommended;

        $pool = array_values(array_diff($allIds, $recommendedIds));
        $stockConflictCandidates = [];
        $rejectRateCandidates = [];

        foreach ($pool as $id) {
            if (isset($criticalIds[$id])) {
                $reason = $criticalIds[$id];

                if (str_contains($reason, 'reject rate') || str_contains($reason, 'kegagalan')) {
                    $rejectRateCandidates[] = ['id' => $id, 'reason' => $reason];
                } else {
                    $stockConflictCandidates[] = ['id' => $id, 'reason' => $this->buildStockConflictReason($id, $recommendedIds)];
                }
                continue;
            }

            $materials = $this->productRecipeMaterials[$id] ?? [];

            $conflictFound = false;
            foreach ($materials as $mat) {
                $availableAfter = $simulatedStock[$mat['material_id']] ?? $mat['current_stock'];
                if ($availableAfter < $mat['quantity_needed']) {
                    $conflictFound = true;
                    $conflictMaterial = $mat['name'];

                    $conflictingProduct = $this->findConflictingProduct($mat['material_id'], $recommendedIds);
                    $reason = "Batch produk [{$this->getProductName($id)}] tidak direkomendasikan karena stok bahan baku kritis [{$conflictMaterial}] bentrok/habis dialokasikan untuk rekomendasi utama [{$conflictingProduct}].";
                    $stockConflictCandidates[] = ['id' => $id, 'reason' => $reason];
                    break;
                }
            }

            if (!$conflictFound) {
                $rejectRate = $this->getProductRejectRate($id);
                if ($rejectRate > 20) {
                    $reason = "Batch produk [{$this->getProductName($id)}] tidak direkomendasikan karena berdasarkan riwayat batch sebelumnya, produk ini mengalami tingkat kegagalan (reject rate) yang tinggi pada proses Quality Control akhir-akhir ini.";
                    $rejectRateCandidates[] = ['id' => $id, 'reason' => $reason];
                } else {
                    $reason = "Batch produk [{$this->getProductName($id)}] tidak direkomendasikan karena skor kebugaran (fitness) lebih rendah dibandingkan produk lain dalam rekomendasi utama.";
                    $stockConflictCandidates[] = ['id' => $id, 'reason' => $reason];
                }
            }
        }

        $combined = array_merge($stockConflictCandidates, $rejectRateCandidates);

        $fillCount = min($remainingSlots, count($combined));
        for ($i = 0; $i < $fillCount; $i++) {
            $item = $combined[$i];
            $notRecommended[] = [
                'product_id' => $item['id'],
                'product_name' => $this->getProductName($item['id']),
                'reason' => $item['reason'],
                'is_recommended' => false,
            ];
        }

        return $notRecommended;
    }

    private function buildStockConflictReason(int $productId, array $recommendedIds): string
    {
        $materials = $this->productRecipeMaterials[$productId] ?? [];
        foreach ($materials as $mat) {
            $conflictingProduct = $this->findConflictingProduct($mat['material_id'], $recommendedIds);
            if ($conflictingProduct !== 'Unknown') {
                return "Batch produk [{$this->getProductName($productId)}] tidak direkomendasikan karena stok bahan baku kritis [{$mat['name']}] bentrok/habis dialokasikan untuk rekomendasi utama [{$conflictingProduct}].";
            }
        }
        return "Batch produk [{$this->getProductName($productId)}] tidak direkomendasikan karena stok bahan baku tidak mencukupi.";
    }

    private function findConflictingProduct(int $materialId, array $recommendedIds): string
    {
        foreach ($recommendedIds as $recId) {
            $materials = $this->productRecipeMaterials[$recId] ?? [];
            foreach ($materials as $mat) {
                if ($mat['material_id'] === $materialId) {
                    return $this->getProductName($recId);
                }
            }
        }
        return 'Unknown';
    }

    private function buildStockMap(): array
    {
        $map = [];
        foreach ($this->materials as $material) {
            $map[$material->id] = (float) $material->current_stock;
        }
        return $map;
    }

    private function formatStockMap(array $stockMap): array
    {
        $result = [];
        foreach ($stockMap as $materialId => $remaining) {
            $material = $this->materials->get($materialId);
            if ($material) {
                $result[] = [
                    'raw_material_id' => $materialId,
                    'name' => $material->name,
                    'unit' => $material->unit,
                    'remaining_stock' => max(0, round($remaining, 2)),
                ];
            }
        }
        return $result;
    }

    private function emptyResult(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
            'recommended_batches' => [],
            'not_recommended_batches' => [],
            'remaining_stock' => [],
            'generations' => 0,
        ];
    }
}
