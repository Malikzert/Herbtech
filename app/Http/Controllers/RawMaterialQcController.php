<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RawMaterialQc;
use Illuminate\Http\Request;

class RawMaterialQcController extends Controller
{
    public function operatorIndex()
    {
        $materials = RawMaterial::whereIn('qc_status', ['waiting', 'rework'])
            ->with('latestQc')
            ->latest()
            ->paginate(10);

        return view('operator.raw-materials.qc.index', compact('materials'));
    }

    public function operatorStore(Request $request)
    {
        $validated = $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'good_qty' => 'required|integer|min:0',
            'status' => 'required|in:passed,rework,decline',
            'notes' => 'nullable|string|max:500',
        ]);

        $rawMaterial = RawMaterial::findOrFail($validated['raw_material_id']);

        $totalQty = (int) $rawMaterial->current_stock;
        $goodQty = (int) $validated['good_qty'];

        if ($goodQty > $totalQty) {
            return back()->withErrors(['good_qty' => 'Jumlah lolos tidak boleh melebihi total stok (' . $totalQty . ').'])->withInput();
        }

        $badQty = $totalQty - $goodQty;
        $percentage = $totalQty > 0 ? round(($goodQty / $totalQty) * 100, 2) : 0;

        $requestedStatus = $validated['status'];
        $this->validateStatusByThreshold($requestedStatus, $percentage);

        $qcRecord = RawMaterialQc::create([
            'raw_material_id' => $rawMaterial->id,
            'user_id' => auth()->id(),
            'total_qty_checked' => $totalQty,
            'good_qty' => $goodQty,
            'bad_qty' => $badQty,
            'qc_percentage' => $percentage,
            'status' => $requestedStatus,
            'notes' => $validated['notes'] ?? null,
        ]);

        $rawMaterial->qc_status = match ($requestedStatus) {
            'passed' => 'accept',
            default => $requestedStatus,
        };

        if ($requestedStatus === 'passed') {
            $rawMaterial->current_stock = $goodQty;
        }

        $rawMaterial->save();

        $message = match ($requestedStatus) {
            'passed' => 'Bahan baku lolos QC dan siap digunakan.',
            'rework' => 'Bahan baku ditandai untuk QC ulang.',
            'decline' => 'Bahan baku ditolak dan tidak akan digunakan.',
        };

        return redirect()->route('operator.raw-materials.qc.index')
            ->with('success', $message);
    }

    public function adminIndex()
    {
        $qcRecords = RawMaterialQc::with('rawMaterial', 'user')
            ->latest()
            ->paginate(15);

        return view('admin.raw-materials.qc.index', compact('qcRecords'));
    }

    public function adminResend($id)
    {
        $qcRecord = RawMaterialQc::findOrFail($id);
        $rawMaterial = $qcRecord->rawMaterial;

        RawMaterialQc::create([
            'raw_material_id' => $rawMaterial->id,
            'user_id' => auth()->id(),
            'total_qty_checked' => 0,
            'good_qty' => 0,
            'bad_qty' => 0,
            'qc_percentage' => 0,
            'status' => 'waiting',
            'notes' => 'Dikirim ulang untuk QC ulang.',
        ]);

        $rawMaterial->qc_status = 'waiting';
        $rawMaterial->save();

        return redirect()->route('admin.raw-materials.qc.index')
            ->with('success', 'Bahan baku dikirim ulang untuk QC.');
    }

    private function validateStatusByThreshold(string $status, float $percentage): void
    {
        $allowed = match (true) {
            $percentage >= 80 => ['passed'],
            $percentage >= 70 => ['passed', 'rework'],
            $percentage >= 50 => ['rework'],
            default => ['decline'],
        };

        if (!in_array($status, $allowed)) {
            abort(422, "Status '{$status}' tidak diizinkan untuk persentase {$percentage}%.");
        }
    }
}
