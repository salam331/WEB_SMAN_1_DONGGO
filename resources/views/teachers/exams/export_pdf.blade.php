<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Ujian - {{ $exam->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 40px;
            background-color: #fff;
        }

        /* === HEADER === */
        .header {
            text-align: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
            color: #0d6efd;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        /* === INFO SECTION === */
        .exam-info,
        .statistics {
            margin-bottom: 25px;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 8px;
            color: #0d6efd;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        th,
        td {
            padding: 7px 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f1f5fb;
            font-weight: 600;
        }

        td {
            background-color: #fff;
        }

        .exam-info th {
            width: 35%;
        }

        /* === STATISTICS === */
        .statistics th {
            background-color: #e8f0ff;
        }

        /* === RESULTS TABLE === */
        .results-table {
            margin-top: 20px;
        }

        .results-table th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
            font-weight: bold;
        }

        .results-table td {
            text-align: center;
            border-color: #ddd;
        }

        .results-table td.name {
            text-align: left;
        }

        .status-pass {
            color: #198754;
            font-weight: 700;
        }

        .status-fail {
            color: #dc3545;
            font-weight: 700;
        }

        /* === FOOTER & SIGNATURE === */
        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #666;
            text-align: right;
        }

        .signature-section {
            margin-top: 60px;
            text-align: right;
        }

        .signature {
            display: inline-block;
            text-align: center;
            margin-right: 60px;
        }

        .signature .line {
            width: 200px;
            border-bottom: 1px solid #444;
            margin: 40px auto 5px;
        }

        /* === PAGE BREAKS === */
        .page-break {
            page-break-before: always;
        }

        @page {
            margin: 40px;
        }

        @media print {
            body {
                font-size: 11px;
            }

            .results-table th {
                background-color: #cfdcff !important;
                -webkit-print-color-adjust: exact;
            }

            .status-pass,
            .status-fail {
                font-weight: bold;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <div class="header">
        <h1>SMAN 1 DONGGO</h1>
        <h2>Laporan Hasil Ujian</h2>
    </div>

    <!-- EXAM INFO -->
    <div class="exam-info">
        <div class="section-title">Informasi Ujian</div>
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
                <td>{{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('d F Y') }}</td>
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

    <!-- STATISTICS -->
    <div class="statistics">
        <div class="section-title">Statistik Hasil Ujian</div>
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

    <!-- RESULTS -->
    <div class="results">
        <div class="section-title">Detail Hasil Ujian</div>
        <table class="results-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($examResults as $index => $result)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="name">{{ $result->student->user->name ?? 'N/A' }}</td>
                        <td>{{ $result->score }}</td>
                        <td>{{ $result->grade ?? '-' }}</td>
                        <td>
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
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}</p>
        <p>Oleh: {{ auth()->user()->name }}</p>
    </div>

    <!-- SIGNATURE -->
    <div class="signature-section">
        <div class="signature">
            <p>Mengetahui,</p>
            <p><strong>Guru Pengampu</strong></p>
            <div class="line"></div>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>
</body>

</html>