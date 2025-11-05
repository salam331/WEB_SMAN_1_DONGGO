@extends('layouts.app')

@section('title', 'Dashboard Siswa - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card overflow-hidden">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard Siswa
                        </h5>
                        @php
                            $now = now()->setTimezone('Asia/Makassar');
                        @endphp
                        <small class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        <div class="mb-4 text-center">
                            <h5 class="fw-bold text-primary mb-1">Selamat Datang, {{ auth()->user()->name }}!</h5>
                            <p class="text-muted small">Pantau aktivitas belajar, jadwal, dan kemajuan Anda di sini.</p>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Jadwal Hari Ini -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 rounded-4 shadow-hover overflow-hidden position-relative">
                                    <div class="subject-header"
                                        style="height: 50px; background: linear-gradient(135deg, #4e73df, #764ba2);"></div>
                                    <div class="card-body bg-white position-relative p-4"
                                        style="margin-top: -45px; border-radius: 20px;">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-wrapper me-3">
                                                <i class="fas fa-calendar-day text-primary"></i>
                                            </div>
                                            <h6 class="fw-bold text-primary mb-0">Jadwal Hari Ini</h6>
                                        </div>

                                        @php
                                            $todayEnglish = now()->format('l');
                                            $dayNames = [
                                                'Monday' => 'Senin',
                                                'Tuesday' => 'Selasa',
                                                'Wednesday' => 'Rabu',
                                                'Thursday' => 'Kamis',
                                                'Friday' => 'Jumat',
                                                'Saturday' => 'Sabtu',
                                                'Sunday' => 'Minggu',
                                            ];
                                            $todaySchedules = $schedules->where('day', $todayEnglish);
                                        @endphp

                                        @if($todaySchedules->count() > 0)
                                            <div class="mt-2">
                                                @foreach($todaySchedules as $schedule)
                                                    <div class="p-3 mb-2 border rounded-3 bg-light hover-schedule shadow-sm">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <strong class="text-primary">{{ $schedule->subject->name }}</strong>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ $schedule->start_time->format('H:i') }} -
                                                                {{ $schedule->end_time->format('H:i') }}
                                                            </small>
                                                        </div>
                                                        <div class="text-muted small mt-1">
                                                            <i class="fas fa-school text-success me-1"></i>
                                                            {{ $schedule->classRoom->name }}
                                                            @if($schedule->classRoom->room)
                                                                <span>(Ruang {{ $schedule->classRoom->room }})</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center mt-4">
                                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                                <p class="text-muted small mb-0">Tidak ada jadwal hari ini</p>
                                            </div>
                                        @endif

                                        <small class="d-block text-muted mt-3 text-center">
                                            <i class="fas fa-calendar-day me-1 text-primary"></i>
                                            {{ $dayNames[$todayEnglish] }}, {{ now()->format('d F Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Materi Pembelajaran -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 rounded-4 shadow-hover overflow-hidden position-relative">
                                    <div class="subject-header"
                                        style="height: 50px; background: linear-gradient(135deg, #f093fb, #f5576c);"></div>
                                    <div class="card-body bg-white position-relative p-4"
                                        style="margin-top: -45px; border-radius: 20px;">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-wrapper me-3">
                                                <i class="fas fa-book-reader text-danger"></i>
                                            </div>
                                            <h6 class="fw-bold text-primary mb-0">Materi Pembelajaran</h6>
                                        </div>
                                        <h2 class="fw-bold text-success text-center mb-1">{{ $stats['total_materials'] }}
                                        </h2>
                                        <p class="text-muted text-center small">Total materi yang tersedia untuk Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Akses Cepat -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-bolt me-2"></i> Akses Cepat
                            </h6>
                            <div class="row g-3">
                                @php
                                    $quickLinks = [
                                        ['icon' => 'fa-calendar-alt', 'color' => 'text-primary', 'title' => 'Jadwal', 'desc' => 'Lihat jadwal pelajaran', 'route' => 'student.schedule'],
                                        ['icon' => 'fa-calendar-check', 'color' => 'text-success', 'title' => 'Absensi', 'desc' => 'Riwayat kehadiran', 'route' => 'student.attendance'],
                                        ['icon' => 'fa-graduation-cap', 'color' => 'text-warning', 'title' => 'Nilai', 'desc' => 'Hasil ujian', 'route' => 'student.grades'],
                                        ['icon' => 'fa-book-reader', 'color' => 'text-info', 'title' => 'Materi', 'desc' => 'Bahan ajar', 'route' => 'student.materials'],
                                        ['icon' => 'fa-bullhorn', 'color' => 'text-danger', 'title' => 'Pengumuman', 'desc' => 'Info terbaru', 'route' => 'student.announcements'],
                                        ['icon' => 'fa-file-invoice-dollar', 'color' => 'text-secondary', 'title' => 'Tagihan', 'desc' => 'Pembayaran', 'route' => 'student.invoices'],
                                    ];
                                @endphp

                                @foreach ($quickLinks as $link)
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <a href="{{ route($link['route']) }}" class="text-decoration-none">
                                            <div class="card border-0 rounded-4 shadow-hover text-center p-3 h-100">
                                                <div class="card-body">
                                                    <i class="fas {{ $link['icon'] }} fa-2x {{ $link['color'] }} mb-3"></i>
                                                    <h6 class="fw-bold text-dark mb-1">{{ $link['title'] }}</h6>
                                                    <small class="text-muted">{{ $link['desc'] }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .subject-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            opacity: 0.95;
        }

        .icon-wrapper {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .hover-schedule {
            transition: all 0.25s ease;
        }

        .hover-schedule:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
    </style>
@endsection