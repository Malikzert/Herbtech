<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Production;
use App\Models\RawMaterial;
use App\Models\QualityControl;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalRawMaterials = RawMaterial::sum('current_stock');
        $activeBatches = Production::whereIn('status', ['in_progress', 'qc_check'])->count();
        $totalQc = QualityControl::count();
        
        $qcPassRate = $totalQc > 0 
            ? round((QualityControl::where('status', 'passed')->count() / $totalQc) * 100, 1) 
            : 0;

        $recentProductions = Production::with('product')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalRawMaterials', 'activeBatches', 'qcPassRate', 'recentProductions'));
    }

    public function operator()
    {
        $activeProductions = Production::with('product')
            ->whereIn('status', ['in_progress', 'qc_check'])
            ->latest()
            ->get();
            
        return view('operator.dashboard', compact('activeProductions'));
    }
}
