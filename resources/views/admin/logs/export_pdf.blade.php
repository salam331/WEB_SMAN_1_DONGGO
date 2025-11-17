<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Log Aktivitas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .no { width: 30px; }
        .date { width: 120px; }
        .user { width: 150px; }
        .role { width: 80px; }
        .action { width: 80px; }
        .model { width: 100px; }
        .ip { width: 120px; }

        /* Responsivitas untuk mobile - hapus padding horizontal pada sisi kiri dan kanan */
        @media (max-width: 992px) {
            body {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .header {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            table {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-5">
        <div class="header text-center mb-5">
            <h1>Laporan Log Aktivitas</h1>
            <p>Periode: {{ request('from') ? request('from') : 'Semua' }} - {{ request('to') ? request('to') : 'Semua' }}</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="no">No</th>
                        <th class="date">Waktu</th>
                        <th class="user">User</th>
                        <th class="role">Role</th>
                        <th class="action">Aksi</th>
                        <th class="model">Model</th>
                        <th class="ip">IP Address</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $index => $log)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($log->role ?? 'N/A') }}</td>
                        <td>{{ ucfirst($log->action) }}</td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->ip_address ?? 'N/A' }}</td>
                        <td>{{ $log->description ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 text-end">
            <p>Dicetak oleh: {{ auth()->user()->name }}</p>
            <p>Tanggal: {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
