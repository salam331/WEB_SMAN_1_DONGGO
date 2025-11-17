@extends('layouts.app')

@section('title', 'SMAN 1 DONGGO - Admin')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Header -->
        {{-- <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body bg-primary text-white py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">
                                    <i class="fas fa-tachometer-alt me-2"></i>Selamat Datang, {{ auth()->user()->name }}!
                                </h2>
                                <p class="mb-0 opacity-75">Dashboard Administrator - Sistem Manajemen Sekolah</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <i class="fas fa-calendar-alt fa-2x me-3 opacity-75"></i>
                                    <div>
                                        <div class="h6 mb-0">{{ now()->format('l, d F Y') }}</div>
                                        <small class="opacity-75">{{ now()->format('H:i') }} WITA</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Statistics Cards -->
        {{-- tambahkan jarak --}}
        <div class="row g-4 mb-4">

            <!-- ==========================
                         💠 MODUL MANAJEMEN
                    =========================== -->
            <div class="card-body d-flex align-items-center p-full position-relative">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-gradient text-dark border-0 py-3 d-flex align-items-center justify-content-between"
                            style="background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-cogs text-secondary me-2"></i>Modul Manajemen
                            </h5>
                            {{-- <small class="text-muted">Kelola seluruh aspek sistem akademik dengan mudah</small> --}}
                        </div>

                        <div class="card-body py-4">
                            <div class="row g-4">
                                @php
                                    $modules = [
                                        [
                                            'route' => 'admin.users',
                                            'icon' => 'fas fa-users',
                                            'color' => 'primary',
                                            'title' => 'Manajemen Pengguna',
                                            'desc' => 'Kelola akun pengguna, role, dan izin akses sistem',
                                        ],
                                        [
                                            'route' => 'admin.teachers',
                                            'icon' => 'fas fa-chalkboard-teacher',
                                            'color' => 'success',
                                            'title' => 'Manajemen Guru',
                                            'desc' => 'Data guru, jadwal mengajar, dan penugasan kelas',
                                        ],
                                        [
                                            'route' => 'admin.students',
                                            'icon' => 'fas fa-user-graduate',
                                            'color' => 'info',
                                            'title' => 'Manajemen Siswa',
                                            'desc' => 'Data siswa, nilai, dan rapor akademik',
                                        ],
                                        [
                                            'route' => 'admin.classes',
                                            'icon' => 'fas fa-school',
                                            'color' => 'warning',
                                            'title' => 'Manajemen Kelas',
                                            'desc' => 'Pengaturan kelas, wali kelas, dan struktur akademik',
                                        ],
                                        [
                                            'route' => 'admin.subjects.index',
                                            'icon' => 'fas fa-book',
                                            'color' => 'danger',
                                            'title' => 'Manajemen Mata Pelajaran',
                                            'desc' => 'Kurikulum, silabus, dan standar kompetensi',
                                        ],
                                        [
                                            'route' => 'admin.schedules.index',
                                            'icon' => 'fas fa-calendar-alt',
                                            'color' => 'secondary',
                                            'title' => 'Manajemen Jadwal',
                                            'desc' => 'Jadwal pelajaran dan kegiatan sekolah',
                                        ],
                                        [
                                            'route' => 'admin.announcements',
                                            'icon' => 'fas fa-bullhorn',
                                            'color' => 'dark',
                                            'title' => 'Pengumuman',
                                            'desc' => 'Informasi dan berita sekolah',
                                        ],
                                        [
                                            'route' => 'admin.logs.dashboard',
                                            'icon' => 'fas fa-history',
                                            'color' => 'primary',
                                            'title' => 'Log Sistem',
                                            'desc' => 'Riwayat aktivitas dan audit trail',
                                        ],
                                    ];
                                @endphp

                                @foreach ($modules as $m)
                                    <div class="col-xl-3 col-md-6">
                                        <div class="card module-card shadow-sm border-0 rounded-4 h-100 position-relative"
                                            onclick="window.location.href='{{ route($m['route']) }}'">
                                            <div class="card-body text-center py-5 position-relative">
                                                <div class="icon-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center"
                                                    style="width: 70px; height: 70px; background-color: var(--bs-{{ $m['color'] }}-bg-subtle); border-radius: 50%; transition: all .3s;">
                                                    <i class="{{ $m['icon'] }} fa-2x text-{{ $m['color'] }}"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-2 text-dark">{{ $m['title'] }}</h6>
                                                <p class="text-muted small mb-3">{{ $m['desc'] }}</p>
                                                <span class="badge bg-{{ $m['color'] }} px-3 py-2">Kelola</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================
                         🌈 CSS Styling Enhancement
                    =========================== -->
            <style>
                .module-card {
                    background: #ffffff;
                    transition: all 0.35s ease;
                    cursor: pointer;
                    border-radius: 1rem;
                }

                .module-card:hover {
                    transform: translateY(-8px) scale(1.02);
                    box-shadow: 0 1.25rem 2rem rgba(0, 0, 0, 0.12);
                    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
                }

                .module-card:hover .icon-wrapper {
                    transform: rotate(8deg) scale(1.1);
                    box-shadow: 0 0 15px rgba(0, 123, 255, 0.25);
                }

                .module-card .badge {
                    border-radius: 50px;
                    font-size: 0.75rem;
                }
            </style>

            <!-- Quick Actions & Recent Activities -->
            <!-- Quick Actions -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient border-0 py-3 d-flex align-items-center"
                        style="background: linear-gradient(90deg, #fff7e6 0%, #fff 100%);">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <!-- Tombol Aksi -->
                            <div class="col-md-6">
                                <a href="{{ route('admin.users.create') }}"
                                    class="quick-action-card bg-gradient bg-white border-0 w-100 text-center p-4 d-block shadow-sm">
                                    <div
                                        class="icon-wrapper bg-primary bg-opacity-10 text-primary mb-3 mx-auto d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-plus fa-2x"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">Tambah Pengguna</h6>
                                    <small class="text-muted">Buat akun baru untuk sistem</small>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{ route('admin.teachers.create') }}"
                                    class="quick-action-card bg-white border-0 w-100 text-center p-4 d-block shadow-sm">
                                    <div
                                        class="icon-wrapper bg-success bg-opacity-10 text-success mb-3 mx-auto d-flex align-items-center justify-content-center">
                                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">Tambah Guru</h6>
                                    <small class="text-muted">Tambahkan data tenaga pengajar</small>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{ route('admin.classes.create') }}"
                                    class="quick-action-card bg-white border-0 w-100 text-center p-4 d-block shadow-sm">
                                    <div
                                        class="icon-wrapper bg-info bg-opacity-10 text-info mb-3 mx-auto d-flex align-items-center justify-content-center">
                                        <i class="fas fa-school fa-2x"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">Tambah Kelas</h6>
                                    <small class="text-muted">Buat struktur kelas baru</small>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{ route('admin.announcements.create') }}"
                                    class="quick-action-card bg-white border-0 w-100 text-center p-4 d-block shadow-sm">
                                    <div
                                        class="icon-wrapper bg-warning bg-opacity-10 text-warning mb-3 mx-auto d-flex align-items-center justify-content-center">
                                        <i class="fas fa-bullhorn fa-2x"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">Buat Pengumuman</h6>
                                    <small class="text-muted">Publikasikan informasi sekolah</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================
                     🌈 CSS Enhancement
                        =========================== -->
            <style>
                .quick-action-card {
                    border-radius: 1rem;
                    transition: all 0.35s ease;
                    text-decoration: none;
                    background: #ffffff;
                    position: relative;
                    overflow: hidden;
                }

                .quick-action-card:hover {
                    transform: translateY(-6px) scale(1.02);
                    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12);
                    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
                }

                .quick-action-card .icon-wrapper {
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .quick-action-card:hover .icon-wrapper {
                    transform: rotate(10deg) scale(1.1);
                    box-shadow: 0 0 15px rgba(255, 193, 7, 0.25);
                }

                .quick-action-card h6 {
                    color: #212529;
                }

                .quick-action-card small {
                    font-size: 0.85rem;
                }
            </style>


            <!-- System Info -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient border-0 py-3 d-flex align-items-center"
                        style="background: linear-gradient(90deg, #e3f2fd 0%, #ffffff 100%);">
                        <h5 class="mb-0 fw-semibold text-info">
                            <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                        </h5>
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <!-- Mata Pelajaran -->
                            <div class="col-6">
                                <div
                                    class="info-card d-flex flex-column align-items-center p-3 rounded-3 shadow-sm bg-white">
                                    <div class="icon-circle bg-primary bg-opacity-10 mb-2">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div class="h4 mb-1 text-primary">{{ number_format($stats['total_subjects']) }}
                                    </div>
                                    <small class="text-muted">Mata Pelajaran</small>
                                </div>
                            </div>

                            <!-- Admin & Staff -->
                            <div class="col-6">
                                <div
                                    class="info-card d-flex flex-column align-items-center p-3 rounded-3 shadow-sm bg-white">
                                    <div class="icon-circle bg-success bg-opacity-10 mb-2">
                                        <i class="fas fa-user-shield text-success"></i>
                                    </div>
                                    <div class="h4 mb-1 text-success">
                                        {{ number_format($stats['total_users'] - $stats['total_teachers'] - $stats['total_students']) }}
                                    </div>
                                    <small class="text-muted">Admin & Staff</small>
                                </div>
                            </div>

                            <!-- Minggu Ke -->
                            <div class="col-6">
                                <div
                                    class="info-card d-flex flex-column align-items-center p-3 rounded-3 shadow-sm bg-white">
                                    <div class="icon-circle bg-info bg-opacity-10 mb-2">
                                        <i class="fas fa-calendar-week text-info"></i>
                                    </div>
                                    <div class="h4 mb-1 text-info">{{ now()->format('W') }}</div>
                                    <small class="text-muted">Minggu Ke</small>
                                </div>
                            </div>

                            <!-- Bulan -->
                            <div class="col-6">
                                <div
                                    class="info-card d-flex flex-column align-items-center p-3 rounded-3 shadow-sm bg-white">
                                    <div class="icon-circle bg-warning bg-opacity-10 mb-2">
                                        <i class="fas fa-calendar-alt text-warning"></i>
                                    </div>
                                    <div class="h4 mb-1 text-warning">{{ now()->format('m') }}</div>
                                    <small class="text-muted">Bulan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================
                 🌈 CSS Enhancement
                    =========================== -->
            <style>
                .info-card {
                    transition: all 0.3s ease;
                    cursor: default;
                    text-align: center;
                }

                .info-card:hover {
                    transform: translateY(-5px) scale(1.02);
                    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.08);
                }

                .icon-circle {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    transition: all 0.3s ease;
                }

                .info-card:hover .icon-circle {
                    transform: scale(1.2) rotate(10deg);
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
                }
            </style>

            <!-- Card Template -->
            <div class="col-lg-20 mb-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient border-0 py-3 d-flex align-items-center"
                        style="background: linear-gradient(90deg, #fffde6 0%, #ffffff 100%);">
                        <h5 class="mb-0 fw-semibold text-success">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Sistem
                        </h5>
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-4">
                            @php
                                $cards = [
                                    [
                                        'title' => 'Total Pengguna',
                                        'icon' => 'fas fa-users',
                                        'color' => 'primary',
                                        'value' => number_format($stats['total_users']),
                                        'gradient' => 'linear-gradient(135deg, #007bff 0%, #6ec6ff 100%)'
                                    ],
                                    [
                                        'title' => 'Total Guru',
                                        'icon' => 'fas fa-chalkboard-teacher',
                                        'color' => 'success',
                                        'value' => number_format($stats['total_teachers']),
                                        'gradient' => 'linear-gradient(135deg, #28a745 0%, #8be78b 100%)'
                                    ],
                                    [
                                        'title' => 'Total Siswa',
                                        'icon' => 'fas fa-user-graduate',
                                        'color' => 'info',
                                        'value' => number_format($stats['total_students']),
                                        'gradient' => 'linear-gradient(135deg, #17a2b8 0%, #7ce7ff 100%)'
                                    ],
                                    [
                                        'title' => 'Total Kelas',
                                        'icon' => 'fas fa-school',
                                        'color' => 'warning',
                                        'value' => number_format($stats['total_classes']),
                                        'gradient' => 'linear-gradient(135deg, #ffc107 0%, #ffe680 100%)'
                                    ],
                                    [
                                        'title' => 'Log Hari Ini',
                                        'icon' => 'fas fa-history',
                                        'color' => 'danger',
                                        'value' => number_format(\App\Models\Log::whereDate('created_at', today())->count()),
                                        'gradient' => 'linear-gradient(135deg, #dc3545 0%, #ff8b94 100%)'
                                    ]
                                ];
                            @endphp

                            @foreach ($cards as $card)
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border-0 shadow-lg h-100 overflow-hidden card-hover"
                                        style="transition: all 0.3s ease; border-radius: 1rem;">
                                        <div class="card-body position-relative d-flex align-items-center p-4">
                                            <div class="icon-wrapper flex-shrink-0 me-3"
                                                style="background: {{ $card['gradient'] }}; border-radius: 1rem; padding: 16px;">
                                                <i class="{{ $card['icon'] }} fa-1x text-white"></i>
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
                    </div>

                    <!-- Tambahkan efek hover dan animasi -->
                    <style>
                        .card-hover:hover {
                            transform: translateY(-6px);
                            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
                        }

                        .icon-wrapper {
                            transition: transform 0.3s ease;
                        }

                        .card-hover:hover .icon-wrapper {
                            transform: rotate(10deg) scale(1.1);
                        }
                    </style>
                </div>
@endsection

            @push('styles')
                <style>
                    .module-card {
                        cursor: pointer;
                        transition: all 0.3s ease;
                    }

                    .module-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
                    }

                    .card {
                        transition: box-shadow 0.3s ease;
                    }

                    .card:hover {
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
                    }

                    /* Responsivitas untuk mobile - hapus padding horizontal pada sisi kiri dan kanan */
                    @media (max-width: 992px) {
                        main {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                        }

                        .card-header {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                        }

                        .card-body {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                        }
                    }
                </style>
            @endpush

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Add click animation to module cards
                        const moduleCards = document.querySelectorAll('.module-card');
                        moduleCards.forEach(card => {
                            card.addEventListener('click', function () {
                                this.style.transform = 'scale(0.95)';
                                setTimeout(() => {
                                    this.style.transform = '';
                                }, 150);
                            });
                        });
                    });
                </script>
            @endpush
