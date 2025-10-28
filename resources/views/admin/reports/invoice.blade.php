<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tagihan Siswa</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .summary {
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .summary-item {
            background: white;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #3498db;
        }
        .summary-item strong {
            display: block;
            font-size: 18px;
            color: #2c3e50;
        }
        .summary-item span {
            color: #7f8c8d;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-paid { color: #27ae60; font-weight: bold; }
        .status-unpaid { color: #e74c3c; font-weight: bold; }
        .status-partial { color: #f39c12; font-weight: bold; }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Tagihan Siswa</h1>
        <p>SMA Negeri 1 Donggo</p>
        <p>Dibuat pada: {{ now()->format('d/m/Y H:i') }}</p>
        @if($request->filled('month') || $request->filled('year'))
            <p>Periode: {{ $request->month ? 'Bulan ' . $request->month : '' }} {{ $request->year ? 'Tahun ' . $request->year : '' }}</p>
        @endif
    </div>

    <div class="summary">
        <h3>Ringkasan Tagihan</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>{{ $totalInvoices }}</strong>
                <span>Total Tagihan</span>
            </div>
            <div class="summary-item">
                <strong>Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong>
                <span>Total Nominal</span>
            </div>
            <div class="summary-item">
                <strong>Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong>
                <span>Total Dibayar</span>
            </div>
            <div class="summary-item">
                <strong>Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</strong>
                <span>Total Belum Dibayar</span>
            </div>
            <div class="summary-item">
                <strong>Rp {{ number_format($totalPartial, 0, ',', '.') }}</strong>
                <span>Total Pembayaran Sebagian</span>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jumlah Tagihan</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $index => $invoice)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $invoice->student->user->name }}</td>
                <td>{{ $invoice->student->classRoom->name ?? '-' }}</td>
                <td class="amount">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                <td>
                    <span class="status-{{ $invoice->status }}">
                        @switch($invoice->status)
                            @case('paid')
                                Lunas
                                @break
                            @case('unpaid')
                                Belum Dibayar
                                @break
                            @case('partial')
                                Sebagian
                                @break
                            @default
                                {{ ucfirst($invoice->status) }}
                        @endswitch
                    </span>
                </td>
                <td>{{ $invoice->createdBy->name ?? '-' }}</td>
                <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
