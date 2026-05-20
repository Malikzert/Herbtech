<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Product;
use App\Models\Scheduling;
use App\Services\ProductionSchedulerService;
use App\Services\GeneticBatchRecommender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Production::with('product', 'user');
        
        if (!auth()->user()->can('admin')) {
            $query->where('user_id', auth()->id());
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('batch_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $productions = $query->latest()->paginate(10)->appends($request->query());
        
        $view = auth()->user()->can('admin') ? 'admin.productions.index' : 'operator.productions.index';
        return view($view, compact('productions'));
    }

    public function create(Request $request)
    {
        $products = Product::all();
        $rawMaterials = \App\Models\RawMaterial::all();
        $scheduling = null;

        if ($request->has('scheduling_id')) {
            $scheduling = Scheduling::with('product')->find($request->input('scheduling_id'));
        }

        return view('operator.productions.create', compact('products', 'rawMaterials', 'scheduling'));
    }

    public function store(Request $request)
    {
        if ($request->has('materials')) {
            $materials = array_filter($request->input('materials'), function ($mat) {
                return !empty($mat['raw_material_id']) && !empty($mat['quantity']);
            });
            $request->merge(['materials' => $materials]);
        }

        $validated = $request->validate([
            'batch_number' => 'required|string|max:100|unique:productions,batch_number',
            'product_id' => 'required|exists:products,id',
            'target_quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'pic_name' => 'nullable|string|max:255',
            'materials' => 'nullable|array',
            'materials.*.raw_material_id' => 'required|exists:raw_materials,id',
            'materials.*.quantity' => 'required|numeric|min:0.1',
        ]);

        $isOperator = !auth()->user()->can('admin');
        $status = $isOperator ? 'pending' : ($request->input('action') === 'start' ? 'in_progress' : 'draft');

        $production = Production::create([
            'batch_number' => $validated['batch_number'],
            'product_id' => $validated['product_id'],
            'target_quantity' => $validated['target_quantity'],
            'start_date' => $validated['start_date'],
            'pic_name' => $validated['pic_name'],
            'user_id' => auth()->id(),
            'status' => $status,
        ]);

        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $mat) {
                \App\Models\ProductionMaterial::create([
                    'production_id' => $production->id,
                    'raw_material_id' => $mat['raw_material_id'],
                    'quantity_used' => $mat['quantity'],
                ]);
            }
        }

        if ($isOperator) {
            return redirect()->route('operator.productions.show', $production->id)
                ->with('success', 'Batch produksi dibuat dan menunggu penjadwalan oleh admin.');
        }

        return redirect()->route('operator.productions.show', $production->id)
            ->with('success', 'Produksi berhasil ' . ($status === 'in_progress' ? 'dimulai.' : 'disimpan sebagai draft.'));
    }

    public function show(Production $production)
    {
        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        if (request()->wantsJson()) {
            $production->loadMissing('product', 'user', 'productionMaterials.rawMaterial', 'qualityControls');
            return response()->json([
                'success' => true,
                'data' => $production,
            ]);
        }

        $view = auth()->user()->can('admin') ? 'admin.productions.show' : 'operator.productions.show';
        return view($view, compact('production'));
    }

    public function edit(Production $production)
    {

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($production->status, ['draft', 'in_progress'])) {
            return redirect()->route('operator.productions.index')
                ->with('error', 'Produksi tidak dapat diedit pada status ini.');
        }

        $products = Product::all();
        return view('operator.productions.edit', compact('production', 'products'));
    }

    public function update(Request $request, Production $production)
    {

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'batch_number' => 'required|string|max:100|unique:productions,batch_number,' . $production->id,
            'product_id' => 'required|exists:products,id',
            'target_quantity' => 'required|integer|min:1',
            'actual_quantity' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'pic_name' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['draft', 'pending', 'in_progress', 'qc_check', 'rework', 'completed', 'cancelled'])],
        ]);

        $isOperator = !auth()->user()->can('admin');

        if ($isOperator && isset($validated['status']) && !in_array($validated['status'], ['draft', 'pending'])) {
            return back()->with('error', 'Operator hanya dapat mengubah status ke draft atau pending.');
        }

        $production->update($validated);

        return redirect()->route('operator.productions.show', $production->id)
            ->with('success', 'Produksi berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Production $production)
    {
        $production->loadMissing('productionMaterials.rawMaterial', 'product.recipes.rawMaterial');

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        $allowedStatuses = ['draft', 'pending', 'in_progress', 'qc_check', 'rework', 'completed', 'cancelled'];
        $validated = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $production->status;

        $isAdmin = auth()->user()->can('admin');

        if (!$isAdmin && !in_array($newStatus, ['draft', 'pending'])) {
            return back()->with('error', 'Operator hanya dapat mengubah status ke draft atau pending.');
        }

        if (!$isAdmin && $newStatus === 'pending' && $oldStatus !== 'draft') {
            return back()->with('error', 'Hanya Admin yang dapat mengembalikan status ke pending.');
        }

        $this->validateStatusTransition($production, $newStatus, $oldStatus);

        if (in_array($newStatus, ['in_progress', 'qc_check'])) {
            $validation = $this->validateStockAvailability($production);
            if (!$validation['passed']) {
                return back()->with('error', $validation['message']);
            }
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'in_progress' && $oldStatus === 'draft') {
            $updateData['start_date'] = now();
        }

        if ($newStatus === 'in_progress' && $oldStatus === 'pending') {
            $updateData['start_date'] = now();
        }

        if (in_array($newStatus, ['completed', 'cancelled', 'rework'])) {
            $updateData['end_date'] = now();
        }

        $production->update($updateData);

        return back()->with('success', 'Status produksi berhasil diperbarui.');
    }

    private function validateStatusTransition(Production $production, string $newStatus, string $oldStatus): void
    {
        $validTransitions = [
            'draft'       => ['pending'],
            'pending'     => ['in_progress', 'draft'],
            'in_progress' => ['qc_check'],
            'qc_check'    => ['completed', 'rework'],
            'rework'      => ['pending'],
        ];

        if (isset($validTransitions[$oldStatus]) && !in_array($newStatus, $validTransitions[$oldStatus])) {
            abort(422, "Transisi status dari {$oldStatus} ke {$newStatus} tidak diizinkan.");
        }
    }

    private function validateStockAvailability(Production $production): array
    {
        $insufficient = [];

        foreach ($production->productionMaterials as $pm) {
            $material = $pm->rawMaterial;
            if (!$material) {
                continue;
            }
            if ($material->current_stock < $pm->quantity_used) {
                $insufficient[] = "{$material->name} (stok: {$material->current_stock}, butuh: {$pm->quantity_used})";
            }
        }

        if (!empty($insufficient)) {
            return [
                'passed'  => false,
                'message' => 'Stok bahan baku tidak mencukupi: ' . implode(', ', $insufficient),
            ];
        }

        return ['passed' => true, 'message' => ''];
    }

    public function getRecipeByProduct(string $productId)
    {
        $recipes = \App\Models\Recipe::with('rawMaterial')
            ->where('product_id', $productId)
            ->get();

        if ($recipes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Resep tidak ditemukan untuk produk ini.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $recipes->map(function ($recipe) {
                return [
                    'id' => $recipe->id,
                    'raw_material_id' => $recipe->raw_material_id,
                    'name' => $recipe->rawMaterial->name,
                    'quantity_needed' => $recipe->quantity_needed,
                    'unit' => $recipe->unit,
                    'current_stock' => $recipe->rawMaterial->current_stock,
                ];
            })
        ]);
    }

    public function schedulingIndex(Request $request)
    {
        $stats = app(ProductionSchedulerService::class)->getSchedulingStats();

        $statusCounts = Production::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $lowStockMaterials = \App\Models\RawMaterial::where(function ($q) {
            $q->where('current_stock', '<=', \DB::raw('min_stock_level'))
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('expired_date')
                      ->where('expired_date', '<=', now()->addDays(14));
              });
        })->get();

        $query = Production::with(['product:id,name', 'productionMaterials.rawMaterial:id,name,current_stock,expired_date,min_stock_level'])
            ->whereIn('status', ['pending', 'draft']);

        if ($request->get('filter') === 'scheduled') {
            $query->where('algorithm_generated', true);
        } elseif ($request->get('filter') === 'unscheduled') {
            $query->where('algorithm_generated', false);
        }

        $productions = $query->latest()->paginate(15)->appends($request->query());

        $pendingCount = $statusCounts['pending'] ?? $statusCounts['draft'] ?? 0;
        $scheduledCount = $stats['scheduled_count'];
        $completedCount = $statusCounts['completed'] ?? 0;
        $inProgressCount = $statusCounts['in_progress'] ?? 0;

        $pendingProductions = Production::with('product:id,name')
            ->whereIn('status', ['pending', 'draft'])
            ->where('algorithm_generated', false)
            ->count();

        $schedulings = Scheduling::with('product:id,name')
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->latest()
            ->get();

        $gaResult = null;
        if ($schedulings->isNotEmpty()) {
            $recommended = $schedulings->where('is_recommended', true)->sortBy('priority_order');
            $notRecommended = $schedulings->where('is_recommended', false);

            if ($recommended->isNotEmpty()) {
                $gaResult = [
                    'recommended_batches' => $recommended->map(function ($s) {
                        return [
                            'product_id'          => $s->product_id,
                            'product_name'        => $s->product->name ?? 'Unknown',
                            'priority_order'      => $s->priority_order,
                            'recommended_quantity' => $s->recommended_quantity,
                            'critical_material'   => $s->critical_material_name,
                            'is_recommended'      => true,
                        ];
                    })->toArray(),
                    'not_recommended_batches' => $notRecommended->map(function ($s) {
                        return [
                            'product_id'   => $s->product_id,
                            'product_name' => $s->product->name ?? 'Unknown',
                            'reason'       => $s->rejection_reason,
                            'is_recommended' => false,
                        ];
                    })->toArray(),
                    'message' => 'Menampilkan rekomendasi jadwal batch dari hasil Algoritma Genetika.',
                ];
            }
        }

        return view('admin.scheduling.index', [
            'productions' => $productions,
            'schedulings' => $schedulings,
            'ga_result_from_db' => $gaResult,
            'stats' => $stats,
            'filter' => $request->get('filter', 'all'),
            'statusCounts' => [
                'pending' => $pendingCount,
                'scheduled' => $scheduledCount,
                'completed' => $completedCount,
                'in_progress' => $inProgressCount,
                'low_stock' => $lowStockMaterials->where('current_stock', '<=', \DB::raw('min_stock_level'))->count(),
                'expiring' => $lowStockMaterials->whereNotNull('expired_date')->where('expired_date', '<=', now()->addDays(14))->count(),
            ],
            'lowStockMaterials' => $lowStockMaterials->take(5),
            'pendingProductions' => $pendingProductions,
        ]);
    }

    public function generateSchedule(Request $request)
    {
        if (!auth()->user()->can('admin')) {
            abort(403);
        }

        try {
            $recommender = app(GeneticBatchRecommender::class);
            $result = $recommender->recommend();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($result);
            }

            if (!$result['success']) {
                return back()->with('warning', $result['message']);
            }

            return redirect()->route('admin.scheduling.index')
                ->with('success', $result['message'])
                ->with('ga_result', $result);
        } catch (\Exception $e) {
            Log::error('generateSchedule GA error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $msg = 'Gagal menjalankan algoritma genetika: ' . $e->getMessage();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg]);
            }
            return back()->with('error', $msg);
        }
    }

    public function reviewSchedule(Request $request)
    {
        if (!auth()->user()->can('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'production_ids' => 'required|array|min:1',
            'production_ids.*' => 'exists:productions,id',
            'action' => 'required|in:approve,reset,regenerate',
        ]);

        $scheduler = app(ProductionSchedulerService::class);

        try {
            $result = match ($validated['action']) {
                'approve' => $scheduler->approveSchedule($validated['production_ids']),
                'reset' => $scheduler->resetSchedule($validated['production_ids']),
                'regenerate' => $this->regenerateSchedule($validated['production_ids'], $scheduler),
            };
        } catch (\BadMethodCallException $e) {
            Log::error('reviewSchedule relationship error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses data: Relasi model tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('reviewSchedule error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Gagal memproses permintaan: ' . $e->getMessage());
        }

        if ($result['success']) {
            return back()->with('success', 'Jadwal berhasil disetujui!');
        }

        if (!empty($result['stock_warnings'])) {
            return back()->with('warning', 'Stok bahan baku tidak mencukupi untuk batch ini.');
        }

        return back()->with('error', $result['message']);
    }

    private function regenerateSchedule(array $productionIds, ProductionSchedulerService $scheduler): array
    {
        Production::whereIn('id', $productionIds)
            ->where('status', 'pending')
            ->update(['algorithm_generated' => false, 'scheduled_start' => null, 'scheduled_end' => null]);

        return $scheduler->generateOptimalSchedule();
    }

    private function scheduleRelationshipError(Request $request)
    {
        $msg = 'Gagal memproses data: Relasi model tidak ditemukan.';
        Log::error($msg);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $msg]);
        }
        return back()->with('error', $msg);
    }

    private function scheduleGenericError(Request $request, \Exception $e)
    {
        $msg = 'Gagal menjalankan algoritma: ' . $e->getMessage();
        Log::error('generateSchedule error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $msg]);
        }
        return back()->with('error', $msg);
    }

    public function destroy(Production $production)
    {

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        if ($production->status !== 'draft') {
            return back()->with('error', 'Hanya produksi dengan status draft yang dapat dihapus.');
        }

        $production->delete();

        return redirect()->route('operator.productions.index')
            ->with('success', 'Produksi berhasil dihapus.');
    }
}