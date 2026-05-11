<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Recipe;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Production::with([
            'product:id,name',
            'productionMaterials.rawMaterial:id,name,current_stock,expired_date,unit',
        ])
            ->whereIn('status', ['in_progress', 'qc_check', 'rework'])
            ->whereNotNull('scheduled_start')
            ->orderBy('scheduled_start')
            ->get();

        $hasExpiryWarnings = false;

        foreach ($schedules as $production) {
            $expiringMaterials = collect();

            foreach ($production->productionMaterials as $pm) {
                $material = $pm->rawMaterial;
                if ($material && $material->expired_date) {
                    $daysToExpiry = Carbon::now()->diffInDays($material->expired_date, false);
                    $material->days_to_expiry = (int) $daysToExpiry;
                    if ($daysToExpiry >= 0 && $daysToExpiry <= 14) {
                        $expiringMaterials->push($material);
                        $hasExpiryWarnings = true;
                    }
                }
            }

            $production->expiring_materials = $expiringMaterials;
        }

        return view('operator.schedules.index', [
            'schedules' => $schedules,
            'hasExpiryWarnings' => $hasExpiryWarnings,
        ]);
    }
}
