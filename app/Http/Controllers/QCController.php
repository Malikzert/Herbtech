<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\QualityControl;
use App\Models\FinishedGoodsInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QCController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $action = $request->get('action');
        
        $query = QualityControl::with('production.product', 'production.user');
        
        if (!auth()->user()->can('admin')) {
            $query->whereHas('production', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('production', function ($sub) use ($search) {
                    $sub->where('batch_number', 'like', "%{$search}%");
                });
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($action) {
            $query->where('action', $action);
        }
        
        $qualityControls = $query->latest()->paginate(10)->appends($request->query());
        
        $view = auth()->user()->can('admin') ? 'admin.qc.index' : 'operator.qc.index';
        return view($view, compact('qualityControls'));
    }

    public function create()
    {
        $productions = Production::whereIn('status', ['in_progress', 'qc_check'])
            ->with('product')
            ->get();
            
        $defectCategories = \App\Models\DefectCategory::all();

        return view('operator.qc.create', compact('productions', 'defectCategories'));
    }

    public function store(Request $request)
    {
        if ($request->has('defects')) {
            $defects = array_filter($request->input('defects'), function ($defect) {
                return !empty($defect['defect_cat_id']) && !empty($defect['quantity']);
            });
            $request->merge(['defects' => $defects]);
        }

        $validated = $request->validate([
            'production_id' => 'required|exists:productions,id',
            'total_inspected' => 'required|integer|min:1',
            'total_passed' => 'required|integer|min:0',
            'total_rejected' => 'required|integer|min:0',
            'final_status' => 'nullable|in:release,rework,reject',
            'defects' => 'nullable|array',
            'defects.*.defect_cat_id' => 'required|exists:defect_categories,id',
            'defects.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $totalInspected = (int) $request->total_inspected;
        $totalPassed = (int) $request->total_passed;
        $totalRejected = (int) $request->total_rejected;

        if ($totalPassed + $totalRejected !== $totalInspected) {
            return back()->with('error', 'Total passed + rejected harus sama dengan total inspected.');
        }

        $production = Production::with('product')->findOrFail($validated['production_id']);

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        if ($production->status !== 'in_progress' && $production->status !== 'qc_check') {
            return back()->with('error', 'Produksi harus dalam status in_progress untuk QC.');
        }

        try {
            $qc = null;
            DB::transaction(function () use ($request, $validated, $production, &$qc, $totalPassed, $totalRejected, $totalInspected) {
                $finalStatus = $request->final_status ?? $this->determineAction($totalPassed, $totalRejected, $totalInspected);

                $qc = QualityControl::create([
                    'production_id' => $validated['production_id'],
                    'inspector_name' => auth()->user()->name,
                    'inspected_at' => now(),
                    'total_inspected' => $totalInspected,
                    'total_passed' => $totalPassed,
                    'total_rejected' => $totalRejected,
                    'status' => $this->determineStatus($totalPassed, $totalRejected),
                    'action' => $finalStatus,
                    'notes' => $validated['notes'] ?? null,
                ]);

                if (!empty($validated['defects'])) {
                    foreach ($validated['defects'] as $defect) {
                        \App\Models\QcDefect::create([
                            'qc_id' => $qc->id,
                            'defect_cat_id' => $defect['defect_cat_id'],
                            'defect_quantity' => (int) $defect['quantity'],
                        ]);
                    }
                }

                $production->update([
                    'actual_quantity' => $totalPassed,
                    'end_date' => now(),
                    'status' => $this->mapFinalStatusToProductionStatus($finalStatus),
                ]);

                $this->processAction($production, $qc);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan QC: ' . $e->getMessage());
        }

        return redirect()->route('operator.qc.show', $qc->id)
            ->with('success', 'QC berhasil dilakukan.');
    }

    public function show(string $id)
    {
        $qc = QualityControl::with('production.product', 'production.user', 'qcDefects.defectCategory')
            ->findOrFail($id);

        if (!auth()->user()->can('admin') && $qc->production->user_id !== auth()->id()) {
            abort(403);
        }

        $view = auth()->user()->can('admin') ? 'admin.qc.show' : 'operator.qc.show';
        return view($view, compact('qc'));
    }

    public function edit(string $id)
    {
        $qc = QualityControl::with('production')->findOrFail($id);

        if (!auth()->user()->can('admin') && $qc->production->user_id !== auth()->id()) {
            abort(403);
        }

        $productions = Production::whereIn('status', ['in_progress', 'qc_check'])->get();

        return view('operator.qc.edit', compact('qc', 'productions'));
    }

    public function update(Request $request, string $id)
    {
        $qc = QualityControl::with('production')->findOrFail($id);

        if (!auth()->user()->can('admin') && $qc->production->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'production_id' => 'required|exists:productions,id',
            'total_inspected' => 'required|integer|min:1',
            'total_passed' => 'required|integer|min:0',
            'total_rejected' => 'required|integer|min:0',
        ]);

        $production = Production::with('product')->findOrFail($validated['production_id']);

        if ($validated['total_passed'] + $validated['total_rejected'] !== $validated['total_inspected']) {
            return back()->with('error', 'Total passed + rejected harus sama dengan total inspected.');
        }

        $action = $this->determineAction($validated['total_passed'], $validated['total_rejected'], $validated['total_inspected']);

        $qc->update([
            'production_id' => $validated['production_id'],
            'total_inspected' => $validated['total_inspected'],
            'total_passed' => $validated['total_passed'],
            'total_rejected' => $validated['total_rejected'],
            'status' => $this->determineStatus($validated['total_passed'], $validated['total_rejected']),
            'action' => $action,
        ]);

        return redirect()->route('operator.qc.show', $qc->id)
            ->with('success', 'QC berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $qc = QualityControl::with('production')->findOrFail($id);

        if (!auth()->user()->can('admin') && $qc->production->user_id !== auth()->id()) {
            abort(403);
        }

        $qc->delete();

        return redirect()->route('operator.qc.index')
            ->with('success', 'QC berhasil dihapus.');
    }

    private function determineAction(int $passed, int $rejected, int $inspected): string
    {
        if ($rejected === 0) {
            return 'release';
        } elseif ($passed > 0 && $rejected < $inspected) {
            return 'rework';
        } else {
            return 'reject';
        }
    }

    private function determineStatus(int $passed, int $rejected): string
    {
        if ($rejected === 0) {
            return 'passed';
        } elseif ($passed > 0) {
            return 'partial_reject';
        } else {
            return 'full_reject';
        }
    }

    private function mapFinalStatusToProductionStatus(string $finalStatus): string
    {
        return match ($finalStatus) {
            'release' => 'completed',
            'rework' => 'in_progress',
            'reject' => 'cancelled',
            default => 'completed',
        };
    }

    private function processAction(Production $production, QualityControl $qc): void
    {
        switch ($qc->action) {
            case 'release':
                $this->addToInventory($production, $qc->total_passed);
                $production->update(['status' => 'completed', 'end_date' => now()]);
                break;

            case 'rework':
                $this->createReworkProduction($production, $qc);
                $production->update(['end_date' => now()]);
                break;

            case 'reject':
                $production->update(['status' => 'cancelled', 'end_date' => now()]);
                break;
        }
    }

    private function addToInventory(Production $production, int $quantity): void
    {
        FinishedGoodsInventory::create([
            'production_id' => $production->id,
            'product_id' => $production->product_id,
            'quantity_added' => $quantity,
            'expired_date' => now()->addMonths(6),
            'storage_location' => 'warehouse',
        ]);
    }

    private function createReworkProduction(Production $originalProduction, QualityControl $qc): void
    {
        $newBatchNumber = $originalProduction->batch_number . '-R';

        $reworkProduction = Production::create([
            'batch_number' => $newBatchNumber,
            'product_id' => $originalProduction->product_id,
            'start_date' => now(),
            'status' => 'draft',
            'user_id' => $originalProduction->user_id,
            'rework_of' => $originalProduction->id,
            'pic_name' => $originalProduction->pic_name,
        ]);

        $originalProduction->update(['status' => 'completed']);
    }
}