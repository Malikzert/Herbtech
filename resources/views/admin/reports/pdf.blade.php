<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $label }}</title>
    <style>
        @page {
            size: A4 {{ $orientation ?? 'portrait' }};
            margin: 15mm;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .kop { text-align: center; margin-bottom: 10px; }
        .kop .logo { width: 48px; height: 48px; margin-bottom: 3px; }
        .kop h2 { font-size: 16px; font-weight: 900; color: #059669; margin: 0 0 2px; letter-spacing: 1.5px; text-transform: uppercase; }
        .kop p { font-size: 8px; color: #6b7280; margin: 0; line-height: 1.5; }

        hr.kop-line { border: none; border-top: 2.5px solid #10b981; margin: 5px 0 1px; }
        hr.kop-line-thin { border: none; border-top: 0.8px solid #10b981; margin: 0 0 12px; }

        .report-title {
            background: #10b981; color: #ffffff; text-align: center;
            padding: 6px 0; margin-bottom: 10px;
        }
        .report-title h1 { font-size: 13px; font-weight: 800; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
        .report-title p { font-size: 8px; margin: 2px 0 0; opacity: 0.85; }

        .stats { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        .stats td {
            padding: 5px 8px; text-align: center;
            border: 1px solid #d1d5db; background: #f0fdf4;
        }
        .stats td .num { display: block; font-size: 14px; font-weight: 800; color: #059669; }
        .stats td .lbl {
            display: block; font-size: 6px; text-transform: uppercase;
            letter-spacing: 0.5px; color: #6b7280; margin-top: 1px; font-weight: 700;
        }

        table { width: 100%; border-collapse: collapse; font-size: 7.5px; margin-bottom: 4px; }
        thead th {
            background: #10b981; color: #ffffff; padding: 5px 5px; text-align: left;
            text-transform: uppercase; font-size: 7px; letter-spacing: 0.5px; font-weight: 800;
        }
        tbody td { padding: 4px 5px; border-bottom: 1px solid #e5e7eb; }
        tbody tr:nth-child(even) { background: #f0fdf4; }

        .badge-approved { color: #059669; font-weight: 800; font-size: 7.5px; }
        .badge-pending { color: #d97706; font-weight: 800; font-size: 7.5px; }
        .badge-default { color: #6b7280; font-weight: 600; font-size: 7px; }

        .ttd { margin-top: 25px; width: 100%; font-size: 8px; }
        .ttd .left { float: left; width: 50%; text-align: center; }
        .ttd .right { float: right; width: 50%; text-align: center; }
        .ttd .spacer { height: 45px; }
        .ttd .line { width: 160px; border: none; border-top: 0.8px solid #374151; margin: 0 auto; }
        .ttd .clear { clear: both; }

        .footer {
            position: fixed; bottom: 0; left: 15mm; right: 15mm;
            text-align: center; font-size: 6.5px; color: #9ca3af;
            border-top: 1px solid #e5e7eb; padding-top: 4px;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        <img src="{{ public_path('image/logoht.png') }}" class="logo" alt="Logo">
        <h2>HerbTech</h2>
        <p>SISTEM INFORMASI PRODUKSI JAMU MADURA</p>
        <p>Jl. Raya Jamu No. 123, Madura &bull; Telp: (0324) 123-456 &bull; Email: info@herbtech.com</p>
    </div>
    <hr class="kop-line">
    <hr class="kop-line-thin">

    {{-- REPORT HEADER --}}
    <div class="report-title">
        <h1>{{ $label }}</h1>
        <p>Periode: {{ date('d M Y', strtotime($startDate)) }} &mdash; {{ date('d M Y', strtotime($endDate)) }}</p>
    </div>

    {{-- STATISTIK --}}
    @if($reportType === 'production')
    <table class="stats">
        <tr>
            <td><span class="num">{{ $totalProductions ?? 0 }}</span><span class="lbl">Total Batch</span></td>
            <td><span class="num">{{ $completedCount ?? 0 }}</span><span class="lbl">Completed</span></td>
            <td><span class="num">{{ $inProgressCount ?? 0 }}</span><span class="lbl">In Progress</span></td>
            <td><span class="num">{{ $cancelledCount ?? 0 }}</span><span class="lbl">Dibatalkan</span></td>
            <td><span class="num">{{ $completionRate ?? 0 }}%</span><span class="lbl">Completion Rate</span></td>
        </tr>
    </table>
    @elseif($reportType === 'qc')
    <table class="stats">
        <tr>
            <td><span class="num">{{ $totalQc ?? 0 }}</span><span class="lbl">Total QC</span></td>
            <td><span class="num">{{ $passedCount ?? 0 }}</span><span class="lbl">Passed</span></td>
            <td><span class="num">{{ $partialRejectCount ?? 0 }}</span><span class="lbl">Partial</span></td>
            <td><span class="num">{{ $fullRejectCount ?? 0 }}</span><span class="lbl">Full Reject</span></td>
            <td><span class="num">{{ $passRate ?? 0 }}%</span><span class="lbl">Pass Rate</span></td>
        </tr>
    </table>
    @elseif($reportType === 'raw_material')
    <table class="stats">
        <tr>
            <td><span class="num">{{ number_format($totalUsage ?? 0, 2) }}</span><span class="lbl">Total Penggunaan</span></td>
            <td><span class="num">{{ ($groupedByMaterial ?? collect())->count() }}</span><span class="lbl">Jenis Bahan</span></td>
        </tr>
    </table>
    @endif

    {{-- TABEL DATA --}}
    @if($reportType === 'production' && ($productions ?? collect())->isNotEmpty())
    <table>
        <thead><tr><th style="width:22px;text-align:center">No</th><th>No Batch</th><th>Nama Produk</th><th>Kategori</th><th>Operator</th><th>Tanggal</th><th style="text-align:center">Durasi</th><th style="text-align:center">Status</th></tr></thead>
        <tbody>
            @foreach($productions as $i => $p)
            @php
                $badge = in_array($p->status, ['completed','passed','approved']) ? 'badge-approved' : (in_array($p->status, ['pending','in_progress','qc_check']) ? 'badge-pending' : 'badge-default');
                $label = in_array($p->status, ['completed','passed']) ? 'APPROVED' : (in_array($p->status, ['pending']) ? 'PENDING' : strtoupper(str_replace('_', ' ', $p->status)));
            @endphp
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $p->batch_number }}</td>
                <td>{{ $p->product->name ?? '-' }}</td>
                <td>{{ $p->product->category ?? '-' }}</td>
                <td>{{ $p->user->name ?? '-' }}</td>
                <td>{{ $p->created_at->format('d M Y') }}</td>
                <td style="text-align:center">{{ (int) ($p->estimated_duration ?? 0) }}</td>
                <td style="text-align:center"><span class="{{ $badge }}">{{ $label }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @elseif($reportType === 'qc' && ($qcRecords ?? collect())->isNotEmpty())
    <table>
        <thead><tr><th style="width:22px;text-align:center">No</th><th>ID QC</th><th>Batch</th><th>Inspector</th><th>Tanggal</th><th>Hasil</th><th>Tindakan</th></tr></thead>
        <tbody>
            @foreach($qcRecords as $i => $qc)
            @php
                $badge = $qc->status === 'passed' ? 'badge-approved' : ($qc->status === 'partial_reject' ? 'badge-pending' : 'badge-default');
            @endphp
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>#{{ $qc->id }}</td>
                <td>{{ $qc->production->batch_number ?? '-' }}</td>
                <td>{{ $qc->inspector_name }}</td>
                <td>{{ $qc->created_at->format('d M Y') }}</td>
                <td><span class="{{ $badge }}">{{ strtoupper(str_replace('_', ' ', $qc->status)) }}</span></td>
                <td>{{ strtoupper($qc->action) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @elseif($reportType === 'raw_material' && ($groupedByMaterial ?? collect())->isNotEmpty())
    <table>
        <thead><tr><th style="width:22px;text-align:center">No</th><th>Bahan Baku</th><th>Total Digunakan</th><th>Frekuensi</th></tr></thead>
        <tbody>
            @foreach($groupedByMaterial as $i => $item)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $item['material_name'] }}</td>
                <td>{{ number_format($item['total_used'], 2) }}</td>
                <td>{{ $item['count'] }}x</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center;color:#9ca3af;padding:30px 0;">Tidak ada data pada periode ini.</p>
    @endif

    {{-- TTD --}}
    @if(($reportType === 'production' && ($productions ?? collect())->isNotEmpty()) || ($reportType === 'qc' && ($qcRecords ?? collect())->isNotEmpty()) || ($reportType === 'raw_material' && ($groupedByMaterial ?? collect())->isNotEmpty()))
    <div class="ttd">
        <div class="left">
            <p>Madura, {{ now()->format('d F Y') }}</p>
            <p style="margin-top:6px;font-weight:700;text-transform:uppercase;font-size:7px;">Mengetahui,</p>
            <p style="font-weight:700;text-transform:uppercase;font-size:7px;">Kepala Produksi</p>
            <div class="spacer"></div>
            <hr class="line">
            <p style="margin-top:2px;font-size:7px;color:#374151;">( ________________________ )</p>
            <p style="font-size:6px;color:#9ca3af;margin-top:1px;">NIP. ______________________</p>
        </div>
        <div class="right">
            <p style="visibility:hidden">Madura, {{ now()->format('d F Y') }}</p>
            <p style="margin-top:6px;font-weight:700;text-transform:uppercase;font-size:7px;">Penanggung Jawab,</p>
            <p style="font-weight:700;text-transform:uppercase;font-size:7px;">{{ $reportType === 'qc' ? 'QC Supervisor' : 'Manajer Produksi' }}</p>
            <div class="spacer"></div>
            <hr class="line">
            <p style="margin-top:2px;font-size:7px;color:#374151;">( ________________________ )</p>
            <p style="font-size:6px;color:#9ca3af;margin-top:1px;">NIP. ______________________</p>
        </div>
        <div class="clear"></div>
    </div>
    @endif

    <div class="footer">
        Dokumen ini dicetak secara otomatis &mdash; HerbTech SIP Jamu Madura
    </div>
</body>
</html>