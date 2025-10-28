@extends('layouts.app')

@section('title', 'Dashboard Log Aktivitas - SMAN 1 Donggo')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body bg-gradient-primary text-white py-4 d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);">
                        <div>
                            <h3 class="fw-bold mb-1"><i class="fas fa-history me-2"></i>Dashboard Log Aktivitas</h3>
                            <p class="mb-0 opacity-75">Ringkasan aktivitas sistem dan pengguna</p>
                        </div>
                        <div class="text-end">
                            <div class="h6 mb-0">{{ now()->format('l, d F Y') }}</div>
                            <small class="opacity-75">{{ now()->format('H:i') }} WITA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            @php
                $logCards = [
                    [
                        'title' => 'Log Hari Ini',
                        'icon' => 'fas fa-calendar-day',
                        'color' => 'danger',
                        'value' => $stats['total_logs_today'],
                        'gradient' => 'linear-gradient(135deg, #dc3545 0%, #ff8b94 100%)'
                    ],
                    [
                        'title' => 'Log Minggu Ini',
                        'icon' => 'fas fa-calendar-week',
                        'color' => 'warning',
                        'value' => $stats['total_logs_week'],
                        'gradient' => 'linear-gradient(135deg, #ffc107 0%, #ffe680 100%)'
                    ],
                    [
                        'title' => 'Log Bulan Ini',
                        'icon' => 'fas fa-calendar-alt',
                        'color' => 'info',
                        'value' => $stats['total_logs_month'],
                        'gradient' => 'linear-gradient(135deg, #17a2b8 0%, #7ce7ff 100%)'
                    ],
                ];
            @endphp

            @foreach ($logCards as $card)
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-lg h-100 card-hover" style="border-radius: 1rem;">
                        <div class="card-body d-flex align-items-center p-4 position-relative">
                            <div class="icon-wrapper me-3"
                                style="background: {{ $card['gradient'] }}; padding: 16px; border-radius: 1rem;">
                                <i class="{{ $card['icon'] }} fa-2x text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1 fw-semibold">{{ $card['title'] }}</h6>
                                <h2 class="fw-bold text-{{ $card['color'] }} mb-0">{{ $card['value'] }}</h2>
                            </div>
                            <span class="position-absolute top-0 end-0 p-2 opacity-10">
                                <i class="{{ $card['icon'] }} fa-2x text-{{ $card['color'] }}"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Distribusi Aksi & Role -->
        <div class="row g-4 mb-4">
            <!-- Action Distribution -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <!-- Card Header -->
                    <div class="card-header bg-gradient border-0 py-3"
                        style="background: linear-gradient(90deg, #fff7e6 0%, #fff 100%);">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-tasks me-2"></i> Distribusi Aksi
                        </h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            @php
                                $totalActions = $stats['action_distribution']->sum('count') ?: 0;
                            @endphp

                            @forelse($stats['action_distribution'] as $action)
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Action Label -->
                                    <span class="text-sm fw-semibold text-gray-700 text-capitalize">
                                        {{ $action->action }}
                                    </span>

                                    <!-- Progress & Count -->
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; min-width: 100px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $totalActions > 0 ? ($action->count / $totalActions) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-muted fw-medium">{{ $action->count }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted mb-0">Tidak ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Distribution -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <!-- Card Header -->
                    <div class="card-header bg-gradient border-0 py-3"
                        style="background: linear-gradient(90deg, #e3f2fd 0%, #ffffff 100%);">
                        <h5 class="mb-0 fw-semibold text-info">
                            <i class="fas fa-user-tag me-2"></i> Distribusi Role
                        </h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            @php
                                $totalRoles = $stats['role_distribution']->sum('count') ?: 0;
                            @endphp

                            @forelse($stats['role_distribution'] as $role)
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Role Label -->
                                    <span class="text-sm fw-semibold text-gray-700 text-capitalize">
                                        {{ $role->role }}
                                    </span>

                                    <!-- Progress & Count -->
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; min-width: 100px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $totalRoles > 0 ? ($role->count / $totalRoles) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-muted fw-medium">{{ $role->count }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted mb-0">Tidak ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Most Active Users -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-gradient border-0 py-3"
                style="background: linear-gradient(90deg, #fffde6 0%, #ffffff 100%);">
                <h5 class="mb-0 fw-semibold text-success"><i class="fas fa-user-clock me-2"></i>Pengguna Paling Aktif</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden mb-0"
                        style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: auto">No</th>
                                <th style="width: auto">Nama</th>
                                <th style="width: auto">Role</th>
                                <th style="width: auto">Jumlah Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($stats['most_active_users'] as $userLog)
                                <tr class="align-middle border-bottom text-center">
                                    <td class="fw-semibold text-secondary border-end">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold text-dark border-end">{{ $userLog->user->name ?? 'N/A' }}</td>
                                    <td class="border-end">
                                        @php
                                            $roleName = $userLog->user->roles->first()->name ?? 'N/A';
                                            $roleClass = match ($roleName) {
                                                'admin' => 'bg-danger text-white',
                                                'guru' => 'bg-primary text-white',
                                                'siswa' => 'bg-success text-white',
                                                'orang_tua' => 'bg-warning text-dark',
                                                default => 'bg-secondary text-white'
                                            };
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2 shadow-sm {{ $roleClass }}">
                                            {{ ucfirst($roleName) }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $userLog->count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Tidak ada data aktivitas pengguna
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-gradient border-0 py-3"
                style="background: linear-gradient(90deg, #fff7e6 0%, #fff 100%);">
                <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-bolt me-2 text-warning"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Log
                    </a>
                    <button onclick="runCleanup()" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Bersihkan Log Lama
                    </button>
                    <a href="{{ route('admin.logs.index', ['export' => 'pdf']) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function runCleanup() {
            if (confirm('Apakah Anda yakin ingin membersihkan log yang lebih dari 120 hari?')) {
                alert('Fitur pembersihan log akan dijalankan. Dalam implementasi nyata, ini akan memanggil command Artisan.');
            }
        }
    </script>

    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }

        .icon-wrapper {
            transition: transform 0.3s ease;
        }

        .card-hover:hover .icon-wrapper {
            transform: rotate(10deg) scale(1.1);
        }
    </style>
@endsection