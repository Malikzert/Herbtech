<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\RawMaterial;
use App\Models\QualityControl;
use App\Models\Product;

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

        return view('admin.dashboard', compact(
            'totalProductions',
            'lowStockCount',
            'pendingQcCount',
            'totalProducts',
            'recentProductions',
            'passedQcCount',
            'reworkQcCount',
            'rejectedQcCount',
            'qcPassRate'
        ));
    }

    public function operator()
    {
        $myProductions = Production::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();
            
        $activeProductions = Production::with('product')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['in_progress', 'qc_check'])
            ->latest()
            ->get();

        return view('operator.dashboard', compact('myProductions', 'activeProductions'));
    }
}