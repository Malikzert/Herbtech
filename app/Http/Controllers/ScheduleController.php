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
            ->orderBy('recom_date', 'desc')
            ->orderBy('priority_order');

        if ($status) {
            $query->where('status', $status);
        }

        $schedules = $query->get();

        return view('operator.schedules.index', compact('schedules'));
    }
}
