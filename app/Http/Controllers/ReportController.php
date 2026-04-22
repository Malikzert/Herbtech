<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\QualityControl;
use App\Models\ProductionMaterial;
use App\Models\FinishedGoodsInventory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $reportType = $request->get('type', 'production');

        $data = match ($reportType) {
            'production' => $this->productionReport($startDate, $endDate),
            'raw_material' => $this->rawMaterialReport($startDate, $endDate),
            'qc' => $this->qcReport($startDate, $endDate),
            default => $this->productionReport($startDate, $endDate),
        };

        return view('admin.reports.index', array_merge($data, [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'reportType' => $reportType,
        ]));
    }

    private function productionReport(string $startDate, string $endDate)
    {
        $productions = Production::whereBetween('created_at', [$startDate, $endDate])
            ->with('product', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalProductions = $productions->count();
        $completedCount = $productions->where('status', 'completed')->count();
        $cancelledCount = $productions->where('status', 'cancelled')->count();
        $inProgressCount = $productions->whereIn('status', ['in_progress', 'qc_check'])->count();

        $completionRate = $totalProductions > 0 
            ? round(($completedCount / $totalProductions) * 100, 1) 
            : 0;

        return [
            'productions' => $productions,
            'totalProductions' => $totalProductions,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount,
            'inProgressCount' => $inProgressCount,
            'completionRate' => $completionRate,
        ];
    }

    private function rawMaterialReport(string $startDate, string $endDate)
    {
        $usage = ProductionMaterial::whereBetween('created_at', [$startDate, $endDate])
            ->with('rawMaterial', 'production.product')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsage = $usage->sum('quantity_used');

        $groupedByMaterial = $usage->groupBy('raw_material_id')->map(function ($items) {
            return [
                'material_name' => $items->first()->rawMaterial->name,
                'total_used' => $items->sum('quantity_used'),
                'count' => $items->count(),
            ];
        })->values();

        return [
            'materialUsage' => $usage,
            'totalUsage' => $totalUsage,
            'groupedByMaterial' => $groupedByMaterial,
        ];
    }

    private function qcReport(string $startDate, string $endDate)
    {
        $qcRecords = QualityControl::whereBetween('created_at', [$startDate, $endDate])
            ->with('production.product', 'production.user')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalQc = $qcRecords->count();
        $passedCount = $qcRecords->where('status', 'passed')->count();
        $partialRejectCount = $qcRecords->where('status', 'partial_reject')->count();
        $fullRejectCount = $qcRecords->where('status', 'full_reject')->count();

        $totalInspected = $qcRecords->sum('total_inspected');
        $totalPassed = $qcRecords->sum('total_passed');
        $totalRejected = $qcRecords->sum('total_rejected');

        $passRate = $totalInspected > 0 
            ? round(($totalPassed / $totalInspected) * 100, 1) 
            : 0;

        $releaseCount = $qcRecords->where('action', 'release')->count();
        $reworkCount = $qcRecords->where('action', 'rework')->count();
        $rejectCount = $qcRecords->where('action', 'reject')->count();

        return [
            'qcRecords' => $qcRecords,
            'totalQc' => $totalQc,
            'passedCount' => $passedCount,
            'partialRejectCount' => $partialRejectCount,
            'fullRejectCount' => $fullRejectCount,
            'totalInspected' => $totalInspected,
            'totalPassed' => $totalPassed,
            'totalRejected' => $totalRejected,
            'passRate' => $passRate,
            'releaseCount' => $releaseCount,
            'reworkCount' => $reworkCount,
            'rejectCount' => $rejectCount,
        ];
    }
}