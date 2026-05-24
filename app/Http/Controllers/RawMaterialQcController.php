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
        ], [
            'raw_material_id.required' => 'Bahan baku wajib dipilih untuk proses QC.',
            'raw_material_id.exists' => 'Bahan baku yang dipilih tidak valid atau tidak ditemukan.',
            'good_qty.required' => 'Jumlah bahan baku yang lolos QC wajib diisi.',
            'good_qty.integer' => 'Jumlah bahan baku yang lolos harus berupa angka bulat.',
            'good_qty.min' => 'Jumlah bahan baku yang lolos tidak boleh negatif.',
            'status.required' => 'Status hasil QC (PASSED / REWORK / DECLINE) wajib dipilih sebelum menyimpan.',
            'status.in' => 'Status QC tidak valid. Pilih antara PASSED, REWORK, atau DECLINE.',
            'notes.max' => 'Catatan QC maksimal 500 karakter.',
        ]);

        $rawMaterial = RawMaterial::findOrFail($validated['raw_material_id']);

        // Cek apakah bahan baku masih aktif
        if (!$rawMaterial->is_active) {
            return back()->with('error', 'Aksi ditolak! Bahan baku ini sudah dinonaktifkan karena gagal QC sebelumnya.')->withInput();
        }

        // Cek apakah bahan baku sudah memiliki hasil QC final
        if (in_array($rawMaterial->qc_status, ['accept', 'decline'])) {
            return back()->with('error', 'Aksi ditolak! Bahan baku ini sudah memiliki hasil QC akhir (Accept/Decline). Tidak dapat diproses ulang.')->withInput();
        }

        $totalQty = (int) $rawMaterial->current_stock;
        $goodQty = (int) $validated['good_qty'];

        if ($goodQty > $totalQty) {
            return back()->withErrors(['good_qty' => 'Jumlah lolos tidak boleh melebihi total stok (' . $totalQty . ').'])->withInput();
        }

        $badQty = $totalQty - $goodQty;
        $percentage = $totalQty > 0 ? round(($goodQty / $totalQty) * 100, 2) : 0;

        $requestedStatus = $validated['status'];
        $this->validateStatusByThreshold($requestedStatus, $percentage);

        RawMaterialQc::create([
            'raw_material_id' => $rawMaterial->id,
            'user_id' => auth()->id(),
            'total_qty_checked' => $totalQty,
            'good_qty' => $goodQty,
            'bad_qty' => $badQty,
            'qc_percentage' => $percentage,
            'status' => $requestedStatus,
            'notes' => $validated['notes'] ?? null,
        ]);

        // ============================================================
        //  LOGIKA OTOMATISASI PASCA-QC
        // ============================================================

        if ($requestedStatus === 'passed') {
            // PASSED: Update current_stock dengan good_qty dari QC record
            $rawMaterial->current_stock = $goodQty;
            $rawMaterial->qc_status = 'accept';
            $rawMaterial->is_active = true;
        } elseif ($requestedStatus === 'rework') {
            // REWORK: Stock TIDAK BOLEH berubah. Set status agar admin bisa kirim ulang
            $rawMaterial->qc_status = 'rework';
        } elseif ($requestedStatus === 'decline') {
            // DECLINE: Stock TIDAK bertambah. Nonaktifkan bahan baku.
            $rawMaterial->qc_status = 'decline';
            $rawMaterial->is_active = false;
        }

        $rawMaterial->save();

        $message = match ($requestedStatus) {
            'passed' => 'Bahan baku lolos QC dan stok berhasil diperbarui.',
            'rework' => 'Bahan baku ditandai untuk QC ulang. Stok tidak berubah.',
            'decline' => 'Bahan baku ditolak permanen dan telah dinonaktifkan dari sistem.',
        };

        return redirect()->route('operator.raw-materials.qc.index')
            ->with('success', $message);
    }

    public function operatorBulkPass()
    {
        $materials = RawMaterial::whereIn('qc_status', ['waiting', 'rework'])
            ->where('is_active', true)
            ->get();

        if ($materials->isEmpty()) {
            return redirect()->route('operator.raw-materials.qc.index')
                ->with('warning', 'Tidak ada bahan baku yang menunggu QC.');
        }

        $count = 0;
        foreach ($materials as $rawMaterial) {
            $totalQty = (int) $rawMaterial->current_stock;

            RawMaterialQc::create([
                'raw_material_id' => $rawMaterial->id,
                'user_id' => auth()->id(),
                'total_qty_checked' => $totalQty,
                'good_qty' => $totalQty,
                'bad_qty' => 0,
                'qc_percentage' => 100,
                'status' => 'passed',
                'notes' => 'Bulk pass otomatis.',
            ]);

            $rawMaterial->current_stock = $totalQty;
            $rawMaterial->qc_status = 'accept';
            $rawMaterial->is_active = true;
            $rawMaterial->save();

            $count++;
        }

        return redirect()->route('operator.raw-materials.qc.index')
            ->with('success', "{$count} bahan baku berhasil diloloskan QC (100% PASSED).");
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
