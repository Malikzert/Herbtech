<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Recipe;
use App\Models\RawMaterial;
use App\Models\QualityControl;
use App\Models\Scheduling;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchedulingController extends Controller
{
    // ============================================================
    //  KONSTANTA ALGORITMA GENETIKA
    // ============================================================
    private const TARGET_BATCHES     = 10;
    private const POPULATION_SIZE    = 80;
    private const MAX_GENERATIONS    = 100;
    private const CROSSOVER_RATE     = 0.8;
    private const MUTATION_RATE      = 0.15;
    private const TOURNAMENT_SIZE    = 3;
    private const MAX_PER_PRODUCT    = 2; // diversity cap

    // Bobot fitness: FEFO 60%, volume stok 40%
    private const FEFO_WEIGHT        = 0.6;
    private const STOCK_WEIGHT       = 0.4;

    // ============================================================
    //  STATE
    // ============================================================
    private Collection $products;
    private array $productMaterials = [];
    private array $fefoScore       = [];
    private array $stockScore      = [];
    private array $rejectRateCache = [];
    private \Illuminate\Support\Collection $materialStock;

    // ============================================================
    //  PUBLIC API: SchedulingController@generate
    // ============================================================
    public function generate(Request $request)
    {
        if (!auth()->user()->can('admin')) {
            abort(403);
        }

        $isAjax = $request->ajax() || $request->wantsJson();

        try {
            // ---- Pre-validation: Produk Sudah Masuk Rencana Produksi Aktif ----
            $productionIds = $request->input('production_ids', []);
            if (!empty($productionIds)) {
                $activeProductions = Production::whereIn('id', $productionIds)
                    ->whereIn('status', ['pending', 'in_progress', 'qc_check', 'rework'])
                    ->where('algorithm_generated', true)
                    ->exists();

                if ($activeProductions) {
                    $msg = 'Proses ditolak! Produk yang terpilih sudah masuk ke dalam antrean penjadwalan produksi aktif periode ini.';
                    return $isAjax
                        ? response()->json(['success' => false, 'message' => $msg], 422)
                        : back()->with('error', $msg);
                }
            }

            // ---- Pre-validation: Bahan Baku dengan Status QC Pending ----
            $pendingQcMaterials = RawMaterial::whereIn('qc_status', ['waiting', 'rework'])
                ->where('is_active', true)
                ->get();

            if ($pendingQcMaterials->isNotEmpty()) {
                $names = $pendingQcMaterials->pluck('name')->implode(', ');
                $msg = "Proses gagal! Terdapat komponen bahan baku pada resep yang statusnya masih Waiting (Belum di-QC oleh Operator): {$names}.";
                return $isAjax
                    ? response()->json(['success' => false, 'message' => $msg], 422)
                    : back()->with('error', $msg);
            }

            // ---- Pre-validation: Stok Bahan Baku ----
            $lowStockMaterials = RawMaterial::where(function ($q) {
                $q->where('current_stock', '<=', \DB::raw('min_stock_level'))
                  ->orWhere('current_stock', '<=', 0);
            })->where('is_active', true)->get();

            if ($lowStockMaterials->isNotEmpty()) {
                $names = $lowStockMaterials->pluck('name')->implode(', ');
                $msg = "Gagal memproses jadwal! Sisa stok bahan baku saat ini tidak mencukupi batas minimum kebutuhan batch produksi. Bahan dengan stok rendah: {$names}.";
                return $isAjax
                    ? response()->json(['success' => false, 'message' => $msg], 422)
                    : back()->with('error', $msg);
            }

            $this->loadData();

            if ($this->products->isEmpty()) {
                $msg = 'Tidak ada produk tersedia untuk dijadwalkan.';
                return $isAjax
                    ? response()->json(['success' => false, 'message' => $msg], 422)
                    : back()->with('warning', $msg);
            }

            // ---- 1. Hitung skor FEFO & volume stok tiap produk ----
            $this->calculateScores();

            // ---- 2. Jalankan Algoritma Genetika ----
            $productIds = $this->products->pluck('id')->toArray();
            $bestChromosome = $this->runGeneticAlgorithm($productIds);

            // ---- 3. Bangun output rekomendasi ----
            $result = $this->buildRecommendation($bestChromosome, $productIds);

            // ---- 4. Simpan hasil ke tabel `schedulings` ----
            $this->persistResults($result, $request);

            // ---- 5. Kembalikan response ----
            if ($isAjax) {
                return response()->json([
                    'success'               => true,
                    'message'               => 'Rekomendasi batch berhasil dibuat menggunakan Algoritma Genetika.',
                    'recommended_batches'   => $result['recommended'],
                    'not_recommended_batches' => $result['not_recommended'],
                ]);
            }

            return redirect()->route('admin.scheduling.index')
                ->with('success', 'Rekomendasi batch berhasil dibuat menggunakan Algoritma Genetika.')
                ->with('ga_result', [
                    'recommended_batches'     => $result['recommended'],
                    'not_recommended_batches' => $result['not_recommended'],
                    'remaining_stock'         => $result['remaining_stock'],
                    'generations'             => self::MAX_GENERATIONS,
                    'message'                 => 'Rekomendasi batch berhasil dibuat menggunakan Algoritma Genetika.',
                ]);
        } catch (\Exception $e) {
            \Log::error('GA Algorithm error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            $msg = 'Terjadi kesalahan internal pada optimasi fungsi fitness algoritma.';

            if ($isAjax) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }

            return back()->with('error', $msg);
        }
    }

    // ============================================================
    //  PERSISTENSI KE TABEL `schedulings`
    // ============================================================
    private function persistResults(array $result, Request $request): void
    {
        $userId = $request->user()->id;

        foreach ($result['recommended'] as $item) {
            Scheduling::create([
                'product_id'                 => $item['product_id'],
                'user_id'                    => $userId,
                'batch_number_recommendation' => null,
                'recommended_quantity'       => $item['recommended_quantity'] ?? 1,
                'priority_order'             => $item['priority_order'],
                'is_recommended'             => true,
                'critical_material_name'     => $item['critical_material'] ?? null,
                'rejection_reason'           => null,
                'status'                     => 'draft',
                'recom_date'                 => now()->toDateString(),
            ]);
        }

        foreach ($result['not_recommended'] as $item) {
            Scheduling::create([
                'product_id'                 => $item['product_id'],
                'user_id'                    => $userId,
                'batch_number_recommendation' => null,
                'recommended_quantity'       => 1,
                'priority_order'             => null,
                'is_recommended'             => false,
                'critical_material_name'     => null,
                'rejection_reason'           => $item['reason'],
                'status'                     => 'draft',
                'recom_date'                 => now()->toDateString(),
            ]);
        }
    }

    // ============================================================
    //  LOAD DATA
    // ============================================================
    private function loadData(): void
    {
        // Muat semua produk beserta relasi resep dan bahan baku
        $this->products = Product::with('recipes.rawMaterial')->get();

        // Index bahan baku untuk akses cepat
        $this->materialStock = RawMaterial::where('is_active', true)->get()->keyBy('id');

        // Cache relasi produk -> [bahan baku]
        foreach ($this->products as $product) {
            $materials = [];
            foreach ($product->recipes as $recipe) {
                $material = $recipe->rawMaterial;
                if ($material) {
                    $materials[] = [
                        'material_id'   => $material->id,
                        'name'          => $material->name,
                        'qty_needed'    => (float) $recipe->quantity_needed,
                        'current_stock' => (float) $material->current_stock,
                        'expired_date'  => $material->expired_date ?? null,
                        'unit'          => $recipe->unit ?? $material->unit,
                    ];
                }
            }
            $this->productMaterials[$product->id] = $materials;
        }
    }

    // ============================================================
    //  PERHITUNGAN SKOR FEFO & VOLUME STOK
    // ============================================================
    private function calculateScores(): void
    {
        foreach ($this->products as $product) {
            $materials = $this->productMaterials[$product->id] ?? [];

            if (empty($materials)) {
                $this->fefoScore[$product->id]  = 0;
                $this->stockScore[$product->id] = 0;
                continue;
            }

            // ---- FEFO: cari bahan dengan expired terdekat ----
            $minDays   = PHP_INT_MAX;
            $hasExpiry = false;
            $criticalMaterial = null;

            foreach ($materials as $mat) {
                if (!empty($mat['expired_date'])) {
                    $hasExpiry = true;
                    $days = Carbon::now()->diffInDays(Carbon::parse($mat['expired_date']), false);
                    if ($days < $minDays) {
                        $minDays = $days;
                        $criticalMaterial = $mat['name'];
                    }
                }
            }

            // Semakin dekat expired -> semakin tinggi skor FEFO
            $fefo = $hasExpiry ? $this->normalizeFefo($minDays) : 50;

            // ---- Volume stok: rasio stok tersedia / kebutuhan per batch ----
            $totalStock  = array_sum(array_column($materials, 'current_stock'));
            $totalNeeded = array_sum(array_column($materials, 'qty_needed'));
            $stockScore  = $totalNeeded > 0
                ? $this->normalizeStock($totalStock / $totalNeeded)
                : 0;

            $this->fefoScore[$product->id]  = $fefo;
            $this->stockScore[$product->id] = $stockScore;
        }
    }

    // Skor FEFO: 100 = expired hari ini, menurun hingga 5 untuk >30 hari
    private function normalizeFefo(int $days): float
    {
        return match (true) {
            $days <= 0  => 100,
            $days <= 7  => max(20, 100 - $days * 10),
            $days <= 30 => max(10, 30 - $days),
            default     => 5,
        };
    }

    // Skor stok: 100 jika rasio >= 5, 10 jika rasio < 0.5
    private function normalizeStock(float $ratio): float
    {
        return match (true) {
            $ratio >= 5   => 100,
            $ratio >= 3   => 80,
            $ratio >= 1.5 => 60,
            $ratio >= 1   => 40,
            $ratio >= 0.5 => 20,
            default       => 10,
        };
    }

    // ============================================================
    //  ALGORITMA GENETIKA
    // ============================================================
    private function runGeneticAlgorithm(array $genePool): array
    {
        // ----------------------------------------------------------
        //  Fase 1: Inisialisasi populasi awal
        //  Setiap kromosom adalah array product_id acak sepanjang TARGET
        // ----------------------------------------------------------
        $population = $this->initializePopulation($genePool);

        $bestChromosome = null;
        $bestFitness    = -INF;
        $stagnantGen    = 0;

        for ($gen = 0; $gen < self::MAX_GENERATIONS; $gen++) {
            // Hitung fitness seluruh populasi
            $fitnesses = array_map(fn($chrom) => $this->calculateFitness($chrom), $population);

            // Cari kromosom terbaik generasi ini
            $maxFitness = max($fitnesses);
            $maxIdx     = array_search($maxFitness, $fitnesses, true);

            if ($maxFitness > $bestFitness) {
                $bestFitness    = $maxFitness;
                $bestChromosome = $population[$maxIdx];
                $stagnantGen    = 0;
            } else {
                $stagnantGen++;
            }

            // Early stopping jika fitness tidak meningkat 15 generasi
            if ($stagnantGen >= 15) break;

            // ----------------------------------------------------------
            //  Fase 2: Seleksi (Tournament Selection)
            //  Pilih induk terbaik dari tournament berukuran TOURNAMENT_SIZE
            // ----------------------------------------------------------
            $selected = $this->tournamentSelection($population, $fitnesses);

            // ----------------------------------------------------------
            //  Fase 3: Rekombinasi (Order Crossover)
            //  Pertukarkan segmen kromosom antara dua induk
            // ----------------------------------------------------------
            $offspring = $this->crossover($selected);

            // ----------------------------------------------------------
            //  Fase 4: Mutasi
            //  Ubah satu gen secara acak dengan probabilitas MUTATION_RATE
            // ----------------------------------------------------------
            $this->mutate($offspring, $genePool);

            $population = $offspring;
        }

        return $bestChromosome ?? $this->getTopCandidates($genePool);
    }

    // ----------------------------------------------------------
    //  Inisialisasi populasi: buat POPULATION_SIZE kromosom acak
    // ----------------------------------------------------------
    private function initializePopulation(array $genePool): array
    {
        $size  = min(self::TARGET_BATCHES, count($genePool));
        $pop   = [];

        for ($i = 0; $i < self::POPULATION_SIZE; $i++) {
            $shuffled = $genePool;
            shuffle($shuffled);
            $chromosome = array_slice($shuffled, 0, $size);

            // Terapkan diversity cap
            $chromosome = $this->applyDiversityCap($chromosome);
            $pop[] = $chromosome;
        }

        return $pop;
    }

    // ----------------------------------------------------------
    //  Fitness Function
    //  Gabungan skor FEFO (60%) + skor stok (40%)
    //  + bonus keberagaman jenis produk
    //  - penalti bentrok stok
    // ----------------------------------------------------------
    private function calculateFitness(array $chromosome): float
    {
        if (empty($chromosome)) return 0;

        $usedStock    = []; // material_id => jumlah terpakai
        $totalFitness = 0;
        $uniqueIds    = array_unique($chromosome);

        foreach ($uniqueIds as $productId) {
            $fefo  = $this->fefoScore[$productId]  ?? 0;
            $stock = $this->stockScore[$productId] ?? 0;
            $fitness = ($fefo * self::FEFO_WEIGHT) + ($stock * self::STOCK_WEIGHT);

            $materials = $this->productMaterials[$productId] ?? [];
            $hasConflict = false;

            foreach ($materials as $mat) {
                $used = $usedStock[$mat['material_id']] ?? 0;
                $remaining = $mat['current_stock'] - $used;

                // Jika stok tidak mencukupi, beri penalti besar
                if ($remaining < $mat['qty_needed']) {
                    $hasConflict = true;
                }
                $usedStock[$mat['material_id']] = $used + $mat['qty_needed'];
            }

            if ($hasConflict) {
                $fitness *= 0.3; // penalti bentrok stok
            }

            $totalFitness += $fitness;
        }

        // Bonus keberagaman: variasi kategori produk
        $categories = [];
        foreach ($uniqueIds as $id) {
            $product = $this->products->firstWhere('id', $id);
            if ($product) {
                $cat = $product->jeniss ?? 'general';
                $categories[$cat] = ($categories[$cat] ?? 0) + 1;
            }
        }

        $total = array_sum($categories);
        $uniqueTypes = count($categories);
        $diversityRatio = $uniqueTypes / max(1, $total);

        // Dominasi satu kategori -> penalti
        $maxCat = max($categories);
        $dominancePenalty = ($maxCat / max(1, $total) > 0.5) ? -20 : 0;

        $totalFitness += ($diversityRatio * 30) + $dominancePenalty;

        return $totalFitness;
    }

    // ----------------------------------------------------------
    //  Diversity Cap: pastikan 1 produk maksimal muncul MAX_PER_PRODUCT kali
    // ----------------------------------------------------------
    private function applyDiversityCap(array $chromosome): array
    {
        $counts = array_count_values($chromosome);
        $capped = [];

        foreach ($chromosome as $gene) {
            $current = $counts[$gene] ?? 0;
            if ($current > self::MAX_PER_PRODUCT) {
                // Kurangi kemunculan berlebih
                $counts[$gene]--;
                continue;
            }
            $capped[] = $gene;
        }

        // Jika setelah cap panjang < TARGET, isi dari pool lain
        while (count($capped) < self::TARGET_BATCHES) {
            $pool = $this->products->pluck('id')->diff($capped)->values()->toArray();
            if (empty($pool)) break;
            $capped[] = $pool[array_rand($pool)];
        }

        return $capped;
    }

    // ----------------------------------------------------------
    //  Tournament Selection
    // ----------------------------------------------------------
    private function tournamentSelection(array $population, array $fitnesses): array
    {
        $selected = [];
        $popSize  = count($population);

        for ($i = 0; $i < $popSize; $i++) {
            $bestIdx    = null;
            $bestFit    = -INF;

            for ($t = 0; $t < self::TOURNAMENT_SIZE; $t++) {
                $idx = mt_rand(0, $popSize - 1);
                if ($fitnesses[$idx] > $bestFit) {
                    $bestFit = $fitnesses[$idx];
                    $bestIdx = $idx;
                }
            }

            if ($bestIdx !== null) {
                $selected[] = $population[$bestIdx];
            }
        }

        return $selected;
    }

    // ----------------------------------------------------------
    //  Order Crossover (OX)
    //  Ambil segmen dari parent1, sisanya dari parent2
    // ----------------------------------------------------------
    private function crossover(array $population): array
    {
        $offspring = [];
        $popSize   = count($population);

        for ($i = 0; $i < $popSize; $i++) {
            $p1 = $population[$i];
            $p2 = $population[mt_rand(0, $popSize - 1)];

            if (mt_rand() / mt_getrandmax() < self::CROSSOVER_RATE) {
                $child = $this->orderCrossover($p1, $p2);
            } else {
                $child = $p1;
            }

            $offspring[] = $this->applyDiversityCap($child);
        }

        return $offspring;
    }

    private function orderCrossover(array $p1, array $p2): array
    {
        $size = min(count($p1), self::TARGET_BATCHES);
        if ($size <= 1) return $p1;

        // Pilih segmen acak
        $start = mt_rand(0, $size - 2);
        $end   = mt_rand($start + 1, $size - 1);
        $segLen = $end - $start + 1;

        // Copy segmen dari parent1
        $child = array_fill(0, $size, null);
        for ($i = $start; $i <= $end; $i++) {
            $child[$i] = $p1[$i];
        }

        // Isi sisa dari parent2 (urut, skip yang sudah ada)
        $segValues  = array_values(array_filter($child, fn($v) => $v !== null));
        $remaining  = array_values(array_filter($p2, fn($v) => !in_array($v, $segValues)));

        $ci = 0;
        $ri = 0;
        while ($ci < $size) {
            if ($child[$ci] === null) {
                $child[$ci] = $remaining[$ri] ?? $p1[$ci];
                $ri++;
            }
            $ci++;
        }

        return array_values(array_filter($child, fn($v) => $v !== null));
    }

    // ----------------------------------------------------------
    //  Mutasi: tukar gen dengan product_id acak dari pool
    // ----------------------------------------------------------
    private function mutate(array &$population, array $genePool): void
    {
        foreach ($population as &$chromosome) {
            if (mt_rand() / mt_getrandmax() < self::MUTATION_RATE) {
                $pos = mt_rand(0, count($chromosome) - 1);
                $chromosome[$pos] = $genePool[array_rand($genePool)];
            }
        }
    }

    // ----------------------------------------------------------
    //  Fallback: ambil produk dengan fitness tertinggi
    // ----------------------------------------------------------
    private function getTopCandidates(array $genePool): array
    {
        $scored = [];
        foreach ($genePool as $id) {
            $scored[$id] = ($this->fefoScore[$id] ?? 0) * self::FEFO_WEIGHT
                         + ($this->stockScore[$id] ?? 0) * self::STOCK_WEIGHT;
        }
        arsort($scored);

        $top = array_keys(array_slice($scored, 0, self::TARGET_BATCHES));
        return $this->applyDiversityCap($top);
    }

    // ============================================================
    //  BANGUN OUTPUT REKOMENDASI
    // ============================================================
    private function buildRecommendation(array $chromosome, array $allProductIds): array
    {
        $recommended    = [];
        $simulatedStock = [];
        foreach ($this->materialStock as $mat) {
            $simulatedStock[$mat->id] = (float) $mat->current_stock;
        }

        // ---- Recommended batches dari kromosom ----
        foreach ($chromosome as $i => $productId) {
            $fefo  = $this->fefoScore[$productId]  ?? 0;
            $stock = $this->stockScore[$productId] ?? 0;
            $fitness = ($fefo * self::FEFO_WEIGHT) + ($stock * self::STOCK_WEIGHT);

            $product   = $this->products->firstWhere('id', $productId);
            $materials = $this->productMaterials[$productId] ?? [];

            // Hitung recommended_quantity: floor(stok terkecil / kebutuhan)
            $minBatch = PHP_INT_MAX;
            $criticalMat = null;
            foreach ($materials as $mat) {
                $need   = $mat['qty_needed'];
                $avail  = $simulatedStock[$mat['material_id']] ?? $mat['current_stock'];
                $canMake = $need > 0 ? (int) floor($avail / $need) : 0;
                if ($canMake < $minBatch) {
                    $minBatch   = $canMake;
                    $criticalMat = $mat['name'];
                }
                // Kurangi stok simulasi
                $simulatedStock[$mat['material_id']] = $avail - $need;
            }

            $recommended[] = [
                'product_id'          => $productId,
                'product_name'        => $product->name ?? 'Unknown',
                'product_category'    => $product->jeniss ?? null,
                'priority_order'      => $i + 1,
                'fefo_score'          => round($fefo, 2),
                'stock_score'         => round($stock, 2),
                'fitness_score'       => round($fitness, 2),
                'recommended_quantity' => max(1, $minBatch),
                'critical_material'   => $criticalMat,
                'is_recommended'      => true,
            ];
        }

        // ---- Not recommended: isi sisa slot ----
        $recIds      = array_column($recommended, 'product_id');
        $notRec      = [];
        $remainingSlots = self::TARGET_BATCHES - count($recommended);

        if ($remainingSlots > 0) {
            $pool = array_values(array_diff($allProductIds, $recIds));
            $candidates = $this->evaluateNotRecommended($pool, $recIds);

            // Ambil sebanyak remainingSlots
            for ($i = 0; $i < min($remainingSlots, count($candidates)); $i++) {
                $notRec[] = $candidates[$i];
            }
        }

        // ---- Sisa stok simulasi ----
        $stockSummary = [];
        foreach ($simulatedStock as $matId => $remaining) {
            $mat = $this->materialStock->get($matId);
            if ($mat) {
                $stockSummary[] = [
                    'raw_material_id' => $matId,
                    'name'            => $mat->name,
                    'unit'            => $mat->unit,
                    'remaining_stock' => max(0, round($remaining, 2)),
                ];
            }
        }

        return [
            'recommended'      => $recommended,
            'not_recommended'  => $notRec,
            'remaining_stock'  => $stockSummary,
        ];
    }

    // ============================================================
    //  EVALUASI PRODUK TIDAK DIREKOMENDASIKAN
    // ============================================================
    private function evaluateNotRecommended(array $pool, array $recommendedIds): array
    {
        $results = [];

        foreach ($pool as $productId) {
            $product   = $this->products->firstWhere('id', $productId);
            $name      = $product->name ?? 'Unknown';
            $materials = $this->productMaterials[$productId] ?? [];

            if (empty($materials)) {
                $results[] = [
                    'product_id'   => $productId,
                    'product_name' => $name,
                    'recommended_quantity' => 0,
                    'reason'       => "Batch produk [$name] tidak direkomendasikan karena belum memiliki resep (Bill of Materials) yang lengkap.",
                    'is_recommended' => false,
                ];
                continue;
            }

            // Cek reject rate dari Quality Control
            $rejectRate = $this->getRejectRate($productId);
            if ($rejectRate > 30) {
                $results[] = [
                    'product_id'   => $productId,
                    'product_name' => $name,
                    'recommended_quantity' => 0,
                    'reason'       => "Batch produk [$name] tidak direkomendasikan karena berdasarkan riwayat Quality Control batch sebelumnya, produk ini memiliki tingkat kegagalan (reject rate) yang tinggi.",
                    'is_recommended' => false,
                ];
                continue;
            }

            // Cek kecukupan stok minimum
            $insufficient = false;
            $conflictMaterial = null;
            $conflictProduct  = null;

            foreach ($materials as $mat) {
                if ($mat['current_stock'] < $mat['qty_needed']) {
                    // Cari produk rekomendasi yang memakai bahan ini
                    $conflictProduct = $this->findConflictingProduct($mat['material_id'], $recommendedIds);
                    $conflictMaterial = $mat['name'];

                    if ($mat['current_stock'] <= 0) {
                        $results[] = [
                            'product_id'   => $productId,
                            'product_name' => $name,
                            'recommended_quantity' => 0,
                            'reason'       => "Batch produk [$name] tidak direkomendasikan karena stok bahan baku [$conflictMaterial] sudah habis (0 {$mat['unit']}).",
                            'is_recommended' => false,
                        ];
                    } elseif ($conflictProduct) {
                        $results[] = [
                            'product_id'   => $productId,
                            'product_name' => $name,
                            'recommended_quantity' => 0,
                            'reason'       => "Batch produk [$name] tidak direkomendasikan karena alokasi stok bahan baku [$conflictMaterial] bentrok/habis digunakan untuk rekomendasi utama [$conflictProduct].",
                            'is_recommended' => false,
                        ];
                    } else {
                        $results[] = [
                            'product_id'   => $productId,
                            'product_name' => $name,
                            'recommended_quantity' => 0,
                            'reason'       => "Batch produk [$name] tidak direkomendasikan karena sisa stok bahan baku [$conflictMaterial] tidak mencukupi batas minimum pembuatan 1 batch.",
                            'is_recommended' => false,
                        ];
                    }
                    $insufficient = true;
                    break;
                }
            }

            if (!$insufficient) {
                $results[] = [
                    'product_id'   => $productId,
                    'product_name' => $name,
                    'recommended_quantity' => 0,
                    'reason'       => "Batch produk [$name] tidak direkomendasikan karena skor kebugaran (fitness) lebih rendah dibandingkan produk lain dalam rekomendasi utama.",
                    'is_recommended' => false,
                ];
            }
        }

        return $results;
    }

    // Cari nama produk rekomendasi yang memakai material tertentu
    private function findConflictingProduct(int $materialId, array $recommendedIds): ?string
    {
        foreach ($recommendedIds as $recId) {
            $materials = $this->productMaterials[$recId] ?? [];
            foreach ($materials as $mat) {
                if ($mat['material_id'] === $materialId) {
                    $product = $this->products->firstWhere('id', $recId);
                    return $product->name ?? 'Unknown';
                }
            }
        }
        return null;
    }

    // Ambil rata-rata reject rate dari QC untuk produk tertentu
    private function getRejectRate(int $productId): float
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
}
