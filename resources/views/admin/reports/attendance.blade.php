<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran Siswa</title>
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
        .status-present { color: #27ae60; font-weight: bold; }
        .status-absent { color: #e74c3c; font-weight: bold; }
        .status-late { color: #f39c12; font-weight: bold; }
        .status-excused { color: #9b59b6; font-weight: bold; }
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
        <h1>Laporan Kehadiran Siswa</h1>
        <p>SMA Negeri 1 Donggo</p>
        <p>Dibuat pada: {{ now()->format('d/m/Y H:i') }}</p>
        @if($request->filled('start_date') || $request->filled('end_date'))
            <p>Periode: {{ $request->start_date ?? 'Awal' }} - {{ $request->end_date ?? 'Sekarang' }}</p>
        @endif
    </div>

    <div class="summary">
        <h3>Ringkasan Kehadiran</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>{{ $totalStudents }}</strong>
                <span>Total Siswa</span>
            </div>
            <div class="summary-item">
                <strong>{{ $totalPresent }}</strong>
                <span>Hadir</span>
            </div>
            <div class="summary-item">
                <strong>{{ $totalAbsent }}</strong>
                <span>Tidak Hadir</span>
            </div>
            <div class="summary-item">
                <strong>{{ $totalLate }}</strong>
                <span>Terlambat</span>
            </div>
            <div class="summary-item">
                <strong>{{ $totalExcused }}</strong>
                <span>Izin</span>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->student->user->name }}</td>
                <td>{{ $attendance->student->classRoom->name ?? '-' }}</td>
                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                <td>
                    <span class="status-{{ $attendance->status }}">
                        @switch($attendance->status)
                            @case('present')
                                Hadir
                                @break
                            @case('absent')
                                Tidak Hadir
                                @break
                            @case('late')
                                Terlambat
                                @break
                            @case('excused')
                                Izin
                                @break
                            @default
                                {{ ucfirst($attendance->status) }}
                        @endswitch
                    </span>
                </td>
                <td>{{ $attendance->remark ?? '-' }}</td>
                <td>{{ $attendance->recordedBy->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
