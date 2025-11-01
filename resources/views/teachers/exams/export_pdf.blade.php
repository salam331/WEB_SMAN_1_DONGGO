<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Ujian</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
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
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .school-info {
            margin-bottom: 20px;
            text-align: center;
        }
        .exam-info {
            margin-bottom: 20px;
        }
        .exam-info table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .exam-info th,
        .exam-info td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .exam-info th {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 30%;
        }
        .statistics {
            margin-bottom: 20px;
        }
        .statistics table {
            width: 100%;
            border-collapse: collapse;
        }
        .statistics th,
        .statistics td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .statistics th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .results-table th,
        .results-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .results-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        .results-table .no {
            width: 40px;
            text-align: center;
        }
        .results-table .name {
            width: 200px;
        }
        .results-table .score {
            width: 80px;
            text-align: center;
        }
        .results-table .grade {
            width: 60px;
            text-align: center;
        }
        .results-table .status {
            width: 100px;
            text-align: center;
        }
        .status-pass {
            color: #28a745;
            font-weight: bold;
        }
        .status-fail {
            color: #dc3545;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 0 auto;
            margin-top: 40px;
        }
        .page-break {
            page-break-before: always;
        }
        @media print {
            body {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMAN 1 DONGGO</h1>
        <h2>Laporan Hasil Ujian</h2>
    </div>

    <div class="exam-info">
        <table>
            <tr>
                <th>Nama Ujian</th>
                <td>{{ $exam->name }}</td>
            </tr>
            <tr>
                <th>Mata Pelajaran</th>
                <td>{{ $exam->subject->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td>{{ $exam->classRoom->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Tanggal Ujian</th>
                <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>{{ $exam->start_time }} ({{ $exam->duration }} menit)</td>
            </tr>
            <tr>
                <th>Nilai Kelulusan</th>
                <td>{{ $exam->passing_grade ?? 'Tidak ditentukan' }}</td>
            </tr>
            <tr>
                <th>Total Soal</th>
                <td>{{ $exam->total_questions ?? 'Tidak ditentukan' }}</td>
            </tr>
        </table>
    </div>

    <div class="statistics">
        <h3 style="margin-bottom: 10px;">Statistik Hasil Ujian</h3>
        <table>
            <tr>
                <th>Total Peserta</th>
                <th>Jumlah Lulus</th>
                <th>Jumlah Tidak Lulus</th>
                <th>Rata-rata Nilai</th>
            </tr>
            <tr>
                <td>{{ $totalStudents }}</td>
                <td>{{ $passedStudents }}</td>
                <td>{{ $failedStudents }}</td>
                <td>{{ number_format($averageScore, 2) }}</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px;">Detail Hasil Ujian</h3>
    <table class="results-table">
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="name">Nama Siswa</th>
                <th class="score">Nilai</th>
                <th class="grade">Grade</th>
                <th class="status">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($examResults as $index => $result)
            <tr>
                <td class="no">{{ $index + 1 }}</td>
                <td class="name">{{ $result->student->name ?? 'N/A' }}</td>
                <td class="score">{{ $result->score }}</td>
                <td class="grade">{{ $result->grade ?? '-' }}</td>
                <td class="status">
                    @if($result->score >= ($exam->passing_grade ?? 0))
                        <span class="status-pass">LULUS</span>
                    @else
                        <span class="status-fail">TIDAK LULUS</span>
                    @endif
                </td>
                <td>{{ $result->remark ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>Oleh: {{ auth()->user()->name }}</p>
    </div>

    <div class="signature">
        <p>Mengetahui,</p>
        <p>Guru Pengampu</p>
        <div class="signature-line"></div>
        <p style="margin-top: 5px;">{{ auth()->user()->name }}</p>
    </div>
</body>
</html>
