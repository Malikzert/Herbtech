<?php

namespace App\Http\Controllers;

use App\Models\Scheduling;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = Scheduling::with('product:id,name')
            ->where('is_recommended', true)
            ->whereIn('status', $status ? [$status] : ['draft', 'approved'])
            ->orderBy('recom_date', 'desc')
            ->orderBy('priority_order');

        $schedules = $query->get();

        return view('operator.schedules.index', compact('schedules'));
    }
}
