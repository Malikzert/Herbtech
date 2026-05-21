<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\QualityControl;
use App\Models\ProductionMaterial;
use App\Models\FinishedGoodsInventory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportCsv(Request $request)
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

        $delimiter = ';';
        $adminName = auth()->user()->name ?? 'Sistem';
        $fileDate  = now()->format('d-m-Y H:i');
        $filename  = $reportType . '_' . $startDate . '_' . $endDate . '.csv';

        $label = match ($reportType) {
            'production'   => 'LAPORAN PRODUKSI HERBTECH - UTM',
            'raw_material' => 'LAPORAN BAHAN BAKU HERBTECH - UTM',
            'qc'           => 'LAPORAN QUALITY CONTROL HERBTECH - UTM',
            default        => 'LAPORAN HERBTECH - UTM',
        };

        $callback = function () use ($reportType, $data, $delimiter, $adminName, $fileDate, $label) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($output, [$label], $delimiter);
            fputcsv($output, ['Dicetak: ' . $fileDate . '; Admin: ' . $adminName], $delimiter);
            fputcsv($output, [], $delimiter);

            if ($reportType === 'production') {
                fputcsv($output, ['NO;NO_BATCH;NAMA_PRODUK;JENISS;OPERATOR;TANGGAL;DURASI;STATUS'], $delimiter);
                foreach ($data['productions'] ?? [] as $i => $p) {
                    fputcsv($output, [$i + 1, trim($p->batch_number ?? ''), trim($p->product->name ?? '-'), trim($p->product->jeniss ?? '-'), trim($p->user->name ?? '-'), $p->created_at->format('d-m-Y H:i'), (int) ($p->estimated_duration ?? 0), trim($p->status)], $delimiter);
                }
            } elseif ($reportType === 'qc') {
                fputcsv($output, ['NO;ID_QC;NO_BATCH;INSPECTOR;TANGGAL;HASIL;TINDAKAN'], $delimiter);
                foreach ($data['qcRecords'] ?? [] as $i => $qc) {
                    fputcsv($output, [$i + 1, '#' . $qc->id, trim($qc->production->batch_number ?? ''), trim($qc->inspector_name), $qc->created_at->format('d-m-Y H:i'), trim($qc->status), trim($qc->action)], $delimiter);
                }
            } elseif ($reportType === 'raw_material') {
                fputcsv($output, ['NO;NAMA_BAHAN_BAKU;TOTAL_DIGUNAKAN;FREKUENSI'], $delimiter);
                foreach ($data['groupedByMaterial'] ?? [] as $i => $item) {
                    fputcsv($output, [$i + 1, trim($item['material_name']), number_format($item['total_used'], 2, ',', '.'), $item['count']], $delimiter);
                }
            }
            fputcsv($output, [], $delimiter);
            fputcsv($output, ['Dicetak oleh: ' . $adminName . '; ' . $fileDate], $delimiter);
            fclose($output);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportExcel(Request $request)
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

        $label = match ($reportType) {
            'production'   => 'LAPORAN PRODUKSI HERBTECH - UTM',
            'raw_material' => 'LAPORAN BAHAN BAKU HERBTECH - UTM',
            'qc'           => 'LAPORAN QUALITY CONTROL HERBTECH - UTM',
            default        => 'LAPORAN HERBTECH - UTM',
        };

        $adminName = auth()->user()->name ?? 'Sistem';
        $fileDate  = now()->format('d M Y H:i');
        $filename  = $reportType . '_' . $startDate . '_' . $endDate . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        // --- Column widths ---
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(16);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(14);

        // --- Colors ---
        $emerald     = 'FF10B981';
        $emeraldDark = 'FF059669';
        $white       = 'FFFFFFFF';
        $bgLight     = 'FFF0FDF4';
        $borderGray  = 'FFD1D5DB';

        $styleCenter = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];

        // --- KOP SURAT ---
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'HerbTech');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setARGB($emeraldDark);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'SISTEM INFORMASI PRODUKSI JAMU MADURA');
        $sheet->getStyle('A2')->getFont()->setSize(9)->setBold(true)->getColor()->setARGB('FF374151');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', 'Jl. Raya Jamu No. 123, Madura | Telp: (0324) 123-456 | Email: info@herbtech.com');
        $sheet->getStyle('A3')->getFont()->setSize(8)->getColor()->setARGB('FF6B7280');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');

        // Green separator row
        $sheet->mergeCells('A4:H4');
        $sheet->getRowDimension(4)->setRowHeight(3);
        $sheet->getStyle('A4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($emerald);

        $sheet->mergeCells('A5:H5');
        $sheet->getRowDimension(5)->setRowHeight(1);

        // --- TITLE BAR ---
        $sheet->mergeCells('A6:H6');
        $sheet->setCellValue('A6', $label);
        $sheet->getStyle('A6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($emerald);
        $sheet->getStyle('A6')->getFont()->setSize(11)->setBold(true)->getColor()->setARGB($white);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
        $sheet->getRowDimension(6)->setRowHeight(22);

        // --- PERIOD ---
        $sheet->mergeCells('A7:H7');
        $sheet->setCellValue('A7', 'Periode: ' . date('d M Y', strtotime($startDate)) . ' s/d ' . date('d M Y', strtotime($endDate)));
        $sheet->getStyle('A7')->getFont()->setSize(8)->setItalic(true)->getColor()->setARGB('FF6B7280');
        $sheet->getStyle('A7')->getAlignment()->setHorizontal('center');
        $sheet->getRowDimension(7)->setRowHeight(16);

        $row = 9; // Data starts here

        // --- HEADER ROW ---
        $headers = match ($reportType) {
            'production' => ['NO', 'NO BATCH', 'NAMA PRODUK', 'KATEGORI', 'OPERATOR', 'TANGGAL', 'DURASI', 'STATUS'],
            'qc'         => ['NO', 'ID QC', 'NO BATCH', 'INSPECTOR', 'TANGGAL', 'HASIL', 'TINDAKAN', ''],
            'raw_material' => ['NO', 'NAMA BAHAN BAKU', 'TOTAL DIGUNAKAN', 'SATUAN', 'FREKUENSI', '', '', ''],
        };

        foreach ($headers as $col => $h) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $cell = $colLetter . $row;
            $sheet->setCellValue($cell, $h);
            $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($emerald);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(8)->getColor()->setARGB($white);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->getStyle($cell)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB($borderGray);
        }
        $sheet->getRowDimension($row)->setRowHeight(18);
        $headerRow = $row;
        $row++;

        // --- DATA ROWS ---
        if ($reportType === 'production') {
            foreach ($data['productions'] ?? [] as $i => $p) {
                $vals = [
                    $i + 1,
                    $p->batch_number,
                    $p->product->name ?? '-',
                    $p->product->jeniss ?? '-',
                    $p->user->name ?? '-',
                    $p->created_at->format('d M Y'),
                    (int) ($p->estimated_duration ?? 0),
                    strtoupper(str_replace('_', ' ', $p->status)),
                ];
                foreach ($vals as $col => $v) {
                    $cl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . $row;
                    $sheet->setCellValue($cl, $v);
                    $sheet->getStyle($cl)->getFont()->setSize(8);
                    $sheet->getStyle($cl)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB($borderGray);
                    if ($col === 0 || $col === 6) {
                        $sheet->getStyle($cl)->getAlignment()->setHorizontal('center');
                    }
                }
                if ($i % 2 === 1) {
                    for ($c = 1; $c <= 8; $c++) {
                        $cl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $row;
                        $sheet->getStyle($cl)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($bgLight);
                    }
                }
                $row++;
            }
        } elseif ($reportType === 'qc') {
            foreach ($data['qcRecords'] ?? [] as $i => $qc) {
                $vals = [
                    $i + 1,
                    '#' . $qc->id,
                    $qc->production->batch_number ?? '-',
                    $qc->inspector_name,
                    $qc->created_at->format('d M Y'),
                    strtoupper(str_replace('_', ' ', $qc->status)),
                    strtoupper($qc->action),
                    '',
                ];
                foreach ($vals as $col => $v) {
                    $cl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . $row;
                    $sheet->setCellValue($cl, $v);
                    $sheet->getStyle($cl)->getFont()->setSize(8);
                    $sheet->getStyle($cl)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB($borderGray);
                    if ($col === 0) {
                        $sheet->getStyle($cl)->getAlignment()->setHorizontal('center');
                    }
                }
                if ($i % 2 === 1) {
                    for ($c = 1; $c <= 7; $c++) {
                        $cl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $row;
                        $sheet->getStyle($cl)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($bgLight);
                    }
                }
                $row++;
            }
        } elseif ($reportType === 'raw_material') {
            foreach ($data['groupedByMaterial'] ?? [] as $i => $item) {
                $vals = [
                    $i + 1,
                    $item['material_name'],
                    number_format($item['total_used'], 2, ',', '.'),
                    $item['unit'] ?? 'kg',
                    $item['count'],
                    '', '', '',
                ];
                foreach ($vals as $col => $v) {
                    $cl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . $row;
                    $sheet->setCellValue($cl, $v);
                    $sheet->getStyle($cl)->getFont()->setSize(8);
                    $sheet->getStyle($cl)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->getColor()->setARGB($borderGray);
                    if ($col === 0 || $col === 4) {
                        $sheet->getStyle($cl)->getAlignment()->setHorizontal('center');
                    }
                }
                if ($i % 2 === 1) {
                    for ($c = 1; $c <= 5; $c++) {
                        $cl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $row;
                        $sheet->getStyle($cl)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($bgLight);
                    }
                }
                $row++;
            }
        }

        $row++; // blank row before TTD

        // --- TTD ---
        $ttdRow = $row + 2;
        $sheet->setCellValue('C' . $ttdRow, 'Madura, ' . now()->format('d F Y'));
        $sheet->getStyle('C' . $ttdRow)->getFont()->setSize(9);
        $sheet->getStyle('C' . $ttdRow)->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('G' . $ttdRow, 'Madura, ' . now()->format('d F Y'));
        $sheet->getStyle('G' . $ttdRow)->getFont()->setSize(9);
        $sheet->getStyle('G' . $ttdRow)->getAlignment()->setHorizontal('center');

        $ttdRow2 = $ttdRow + 1;
        $sheet->setCellValue('C' . $ttdRow2, 'Mengetahui,');
        $sheet->getStyle('C' . $ttdRow2)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle('C' . $ttdRow2)->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('G' . $ttdRow2, 'Penanggung Jawab,');
        $sheet->getStyle('G' . $ttdRow2)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle('G' . $ttdRow2)->getAlignment()->setHorizontal('center');

        $ttdRow3 = $ttdRow2 + 1;
        $sheet->setCellValue('C' . $ttdRow3, 'Kepala Produksi');
        $sheet->getStyle('C' . $ttdRow3)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle('C' . $ttdRow3)->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('G' . $ttdRow3, $reportType === 'qc' ? 'QC Supervisor' : 'Manajer Produksi');
        $sheet->getStyle('G' . $ttdRow3)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle('G' . $ttdRow3)->getAlignment()->setHorizontal('center');

        $ttdRow4 = $ttdRow3 + 4; // skip 4 rows for signature space
        $sheet->setCellValue('C' . $ttdRow4, '( ________________________ )');
        $sheet->getStyle('C' . $ttdRow4)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C' . $ttdRow4)->getFont()->setSize(9)->getColor()->setARGB('FF374151');
        $sheet->setCellValue('G' . $ttdRow4, '( ________________________ )');
        $sheet->getStyle('G' . $ttdRow4)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G' . $ttdRow4)->getFont()->setSize(9)->getColor()->setARGB('FF374151');

        $ttdRow5 = $ttdRow4 + 1;
        $sheet->setCellValue('C' . $ttdRow5, 'NIP. ______________________');
        $sheet->getStyle('C' . $ttdRow5)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C' . $ttdRow5)->getFont()->setSize(8)->getColor()->setARGB('FF9CA3AF');
        $sheet->setCellValue('G' . $ttdRow5, 'NIP. ______________________');
        $sheet->getStyle('G' . $ttdRow5)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G' . $ttdRow5)->getFont()->setSize(8)->getColor()->setARGB('FF9CA3AF');

        // --- FOOTER NOTE ---
        $footerRow = $ttdRow5 + 2;
        $sheet->mergeCells('A' . $footerRow . ':H' . $footerRow);
        $sheet->setCellValue('A' . $footerRow, 'Dicetak oleh: ' . $adminName . ' | ' . $fileDate);
        $sheet->getStyle('A' . $footerRow)->getFont()->setSize(7)->setItalic(true)->getColor()->setARGB('FF9CA3AF');
        $sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal('center');

        // --- OUTPUT ---
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $response = response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma'       => 'no-cache',
        ]);
        return $response;
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $reportType = $request->get('type', 'production');
        $orientation = in_array($request->get('orientation', 'landscape'), ['portrait', 'landscape'])
            ? $request->get('orientation', 'landscape')
            : 'landscape';

        $data = match ($reportType) {
            'production' => $this->productionReport($startDate, $endDate),
            'raw_material' => $this->rawMaterialReport($startDate, $endDate),
            'qc' => $this->qcReport($startDate, $endDate),
            default => $this->productionReport($startDate, $endDate),
        };

        $label = match ($reportType) {
            'production'   => 'LAPORAN PRODUKSI',
            'raw_material' => 'LAPORAN BAHAN BAKU',
            'qc'           => 'LAPORAN QUALITY CONTROL',
            default        => 'LAPORAN',
        };

        $pdf = Pdf::loadView('admin.reports.pdf', array_merge($data, [
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'reportType'  => $reportType,
            'label'       => $label,
            'orientation' => $orientation,
        ]));

        $pdf->setPaper('A4', $orientation);

        $filename = $reportType . '_' . $startDate . '_' . $endDate . '.pdf';

        return $pdf->download($filename);
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