<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\RawMaterial;
use App\Models\QualityControl;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalProductions = Production::count();
        $lowStockCount = RawMaterial::where('current_stock', '<', 10)->count();
        $pendingQcCount = Production::where('status', 'qc_check')->count();
        $totalProducts = Product::count();
        
        $recentProductions = Production::with('product', 'user')->latest()->take(5)->get();
        
        $passedQcCount = QualityControl::where('status', 'passed')->count();
        $reworkQcCount = QualityControl::where('action', 'rework')->count();
        $rejectedQcCount = QualityControl::where('action', 'reject')->count();
        
        $totalQc = $passedQcCount + $reworkQcCount + $rejectedQcCount;
        $qcPassRate = $totalQc > 0 ? round(($passedQcCount / $totalQc) * 100, 1) : 0;

        // Chart data: Stok Bahan Baku (exclude packaging / pcs)
        $rawMaterials = RawMaterial::select('name', 'current_stock')
            ->where(function($q) {
                $q->where('type', '!=', 'packaging')
                  ->orWhereNull('type');
            })
            ->where('unit', '!=', 'pcs')
            ->orderBy('current_stock', 'asc')
            ->take(15)
            ->get();
        $matLabels = $rawMaterials->pluck('name')->toArray();
        $matStocks = $rawMaterials->pluck('current_stock')->toArray();

        // Chart data: QC Check per month (last 12 months)
        $qcTrend = QualityControl::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'passed' THEN 1 ELSE 0 END) as passed"),
            DB::raw("SUM(CASE WHEN action = 'rework' THEN 1 ELSE 0 END) as rework"),
            DB::raw("SUM(CASE WHEN action = 'reject' THEN 1 ELSE 0 END) as rejected")
        )
        ->where('created_at', '>=', now()->subYear()->startOfDay())
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        $qcDates = collect($qcTrend->pluck('date')->toArray())->map(function($d) {
            return \Carbon\Carbon::parse($d . '-01')->format('M Y');
        })->toArray();
        $qcPassed = $qcTrend->pluck('passed')->toArray();
        $qcRework = $qcTrend->pluck('rework')->toArray();
        $qcRejected = $qcTrend->pluck('rejected')->toArray();

        return view('admin.dashboard', compact(
            'totalProductions',
            'lowStockCount',
            'pendingQcCount',
            'totalProducts',
            'recentProductions',
            'passedQcCount',
            'reworkQcCount',
            'rejectedQcCount',
            'qcPassRate',
            'matLabels',
            'matStocks',
            'qcDates',
            'qcPassed',
            'qcRework',
            'qcRejected'
        ));
    }

    public function operator()
    {
        $userId = auth()->id();

        $myProductions = Production::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $activeProductions = Production::with('product')
            ->where('user_id', $userId)
            ->whereIn('status', ['in_progress', 'qc_check'])
            ->latest()
            ->get();

        $inProgressCount = Production::where('user_id', $userId)->where('status', 'in_progress')->count();
        $qcCheckCount = Production::where('user_id', $userId)->where('status', 'qc_check')->count();
        $completedCount = Production::where('user_id', $userId)->where('status', 'completed')->count();

        // QC stats for this user's productions
        $userProductionIds = Production::where('user_id', $userId)->pluck('id');
        $qcTotal = QualityControl::whereIn('production_id', $userProductionIds)->count();
        $qcPassed = QualityControl::whereIn('production_id', $userProductionIds)->where('status', 'passed')->count();
        $qcRework = QualityControl::whereIn('production_id', $userProductionIds)->where('action', 'rework')->count();
        $qcRejected = QualityControl::whereIn('production_id', $userProductionIds)->where('action', 'reject')->count();
        $qcInspected = QualityControl::whereIn('production_id', $userProductionIds)->sum('total_inspected');
        $qcPassedTotal = QualityControl::whereIn('production_id', $userProductionIds)->sum('total_passed');
        $qcPassRate = $qcInspected > 0 ? round(($qcPassedTotal / $qcInspected) * 100, 1) : 0;

        // Material stock health
        $totalMaterials = RawMaterial::count();
        $safeStockCount = RawMaterial::where('current_stock', '>=', DB::raw('min_stock_level'))->count();
        $lowStockCount = RawMaterial::where('current_stock', '>', 0)->where('current_stock', '<', DB::raw('min_stock_level'))->count();
        $outStockCount = RawMaterial::where('current_stock', '<=', 0)->count();

        // Weekly production activity (last 7 days)
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weeklyLabels[] = now()->subDays($i)->format('D');
            $weeklyData[] = Production::where('user_id', $userId)->whereDate('created_at', $date)->count();
        }

        // Status distribution for donut
        $statusCounts = [
            'in_progress' => $inProgressCount,
            'qc_check' => $qcCheckCount,
            'completed' => $completedCount,
        ];

        $totalActive = $inProgressCount + $qcCheckCount + $completedCount;

        return view('operator.dashboard', compact(
            'myProductions',
            'activeProductions',
            'inProgressCount',
            'qcCheckCount',
            'completedCount',
            'safeStockCount',
            'lowStockCount',
            'outStockCount',
            'totalMaterials',
            'qcTotal',
            'qcPassed',
            'qcRework',
            'qcRejected',
            'qcInspected',
            'qcPassedTotal',
            'qcPassRate',
            'weeklyLabels',
            'weeklyData',
            'statusCounts',
            'totalActive',
        ));
    }
}