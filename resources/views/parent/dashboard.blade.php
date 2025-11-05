@extends('layouts.app')

@section('title', 'Dashboard Orang Tua - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-user-friends me-2"></i> Dashboard Orang Tua
                        </h5>
                        @php
                            $now = now()->setTimezone('Asia/Makassar');
                        @endphp
                        <small class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        @if($children->count() > 0)
                            <div class="row g-4 justify-content-center">
                                @foreach($children as $child)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 border-0 rounded-4 shadow-hover position-relative overflow-hidden">

                                            <!-- Decorative gradient header -->
                                            <div class="subject-header"
                                                style="height: 55px; background: linear-gradient(135deg, #667eea, #764ba2);">
                                            </div>

                                            <!-- Card Content -->
                                            <div class="card-body bg-white position-relative p-4"
                                                style="margin-top: -50px; border-radius: 20px;">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="icon-wrapper me-3">
                                                        <i class="fas fa-child text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-primary mb-1">{{ $child->user->name }}</h6>
                                                        <small class="text-muted">Siswa SMAN 1 DONGGO</small>
                                                    </div>
                                                </div>

                                                <!-- Jadwal Hari Ini -->
                                                <div class="mb-3">
                                                    <span class="text-gray-600 fw-semibold d-block mb-2">
                                                        <i class="fas fa-calendar-day me-2 text-secondary"></i> Jadwal Hari Ini
                                                    </span>
                                                    @if($stats[$child->id]['today_schedules']->count() > 0)
                                                        <div class="space-y-2">
                                                            @foreach($stats[$child->id]['today_schedules'] as $schedule)
                                                                <div
                                                                    class="bg-blue-50 rounded-3 p-3 border-start border-4 border-primary shadow-sm mb-2">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <span
                                                                                class="fw-semibold text-primary d-block">{{ $schedule->subject->name ?? '-' }}</span>
                                                                            <small class="text-muted">
                                                                                <i class="fas fa-clock me-1"></i>
                                                                                {{ $schedule->start_time->format('H:i') }} -
                                                                                {{ $schedule->end_time->format('H:i') }}
                                                                                @if($schedule->classRoom)
                                                                                    <span class="ms-2">
                                                                                        <i
                                                                                            class="fas fa-map-marker-alt me-1"></i>{{ $schedule->classRoom->name }}
                                                                                    </span>
                                                                                @endif
                                                                            </small>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <small class="fw-semibold text-gray-700">
                                                                                {{ $schedule->teacher->user->name ?? '-' }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center text-muted fst-italic small mt-2">
                                                            Tidak ada jadwal hari ini
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Tombol Aksi -->
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('parent.child.detail', $child->id) }}"
                                                        class="btn btn-outline-primary rounded-pill flex-fill">
                                                        <i class="fas fa-user me-1"></i> Detail Anak
                                                    </a>
                                                    <a href="{{ route('parent.child.attendance', $child->id) }}"
                                                        class="btn btn-outline-success rounded-pill flex-fill">
                                                        <i class="fas fa-check-circle me-1"></i> Kehadiran
                                                    </a>
                                                    <a href="{{ route('parent.child.grades', $child->id) }}"
                                                        class="btn btn-outline-warning rounded-pill flex-fill">
                                                        <i class="fas fa-star me-1"></i> Nilai
                                                    </a>
                                                    <a href="{{ route('parent.child.invoices', $child->id) }}"
                                                        class="btn btn-outline-purple rounded-pill flex-fill"
                                                        style="border-color:#a855f7; color:#a855f7;">
                                                        <i class="fas fa-receipt me-1"></i> Tagihan
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted fw-bold">Belum ada data anak terdaftar</h5>
                                <p class="text-muted small">Data anak akan muncul di sini setelah siswa terdaftar di sistem
                                    sekolah.</p>
                            </div>
                        @endif

                        <!-- Pengumuman -->
                        <div class="mt-5 text-center">
                            <a href="{{ route('parent.announcements') }}"
                                class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                                <i class="fas fa-bullhorn me-2"></i> Lihat Pengumuman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Efek animasi */
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

        /* Kartu dan hover */
        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Header gradien di atas kartu */
        .subject-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            opacity: 0.9;
        }

        /* Ikon lingkaran */
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

        /* Tombol dengan efek lembut */
        .btn-outline-primary,
        .btn-outline-success,
        .btn-outline-warning,
        .btn-outline-purple {
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background-color: #4e73df;
            color: #fff;
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.3);
        }

        .btn-outline-success:hover {
            background-color: #1cc88a;
            color: #fff;
            box-shadow: 0 6px 20px rgba(28, 200, 138, 0.3);
        }

        .btn-outline-warning:hover {
            background-color: #f6c23e;
            color: #fff;
            box-shadow: 0 6px 20px rgba(246, 194, 62, 0.3);
        }

        .btn-outline-purple:hover {
            background-color: #a855f7;
            color: #fff;
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.3);
        }
    </style>
@endsection