@extends('layouts.app')

@section('title', 'Kelola Kelas - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid animate-container">

        <!-- 🌟 Header -->
        <div class="text-center mb-5 fade-in-up">
            <h2 class="fw-bold text-gradient mb-1">
                <i class="fas fa-school me-2"></i> Kelas yang Anda Ajar
            </h2>
            <p class="text-muted">Kelola aktivitas belajar, absensi, dan siswa di setiap kelas Anda</p>
        </div>

        <!-- 🏫 Daftar Kelas -->
        <div class="row g-4 fade-in-up justify-content-center">
            @if($classes->count() > 0)
                @foreach($classes as $class)
                    <div class="col-lg-6 col-xl-4">
                        <div class="card class-card border-0 shadow-lg h-100 position-relative overflow-hidden">
                            <div class="class-card-bg"></div>
                            <div class="card-body position-relative z-2">

                                <!-- Header Kelas -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                        <i class="fas fa-chalkboard text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-0">{{ $class->name }}</h5>
                                        @if($class->homeroomTeacher)
                                            <small class="text-muted">Wali: {{ $class->homeroomTeacher->user->name }}</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Statistik -->
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="stats-box">
                                            <h4 class="fw-bold text-primary mb-0">{{ $class->students->count() }}</h4>
                                            <small class="text-muted">Siswa</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stats-box">
                                            <h4 class="fw-bold text-success mb-0">
                                                {{ $class->subjectTeachers->where('teacher_id', auth()->user()->teacher->id)->count() }}
                                            </h4>
                                            <small class="text-muted">Mapel</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mata Pelajaran -->
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold d-block mb-2">Mata Pelajaran yang Anda Ajar:</small>
                                    @php
                                        $taughtSubjects = $class->subjectTeachers->where('teacher_id', auth()->user()->teacher->id);
                                    @endphp
                                    @if($taughtSubjects->count() > 0)
                                        @foreach($taughtSubjects as $subjectTeacher)
                                            <span class="badge rounded-pill bg-gradient-primary me-1 mb-1">
                                                {{ $subjectTeacher->subject->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <small class="text-muted fst-italic">Belum ada mata pelajaran</small>
                                    @endif
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('teachers.classes.detail', $class->id) }}"
                                        class="btn btn-outline-primary btn-sm flex-grow-1">
                                        <i class="fas fa-users me-1"></i> Detail Siswa
                                    </a>
                                    <a href="{{ route('teachers.attendances') }}?class_id={{ $class->id }}"
                                        class="btn btn-outline-success btn-sm flex-grow-1">
                                        <i class="fas fa-calendar-check me-1"></i> Input Absensi
                                    </a>
                                </div>
                            </div>

                            <!-- 🚀 Quick Action -->
                            {{-- <div class="quick-action">
                                <button onclick="window.location='{{ route('teachers.schedules') }}?class_id={{ $class->id }}'"
                                    class="btn btn-primary btn-sm shadow-sm" title="Lihat Jadwal">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                                <button onclick="window.location='{{ route('teachers.classes.detail', $class->id) }}'"
                                    class="btn btn-info btn-sm shadow-sm" title="Lihat Siswa">
                                    <i class="fas fa-users"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5 fade-in-up">
                    <i class="fas fa-school fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted">Belum Ada Kelas yang Ditugaskan</h5>
                    <p class="text-muted mb-4">Anda belum ditugaskan untuk mengajar di kelas manapun.</p>
                    <a href="{{ route('teachers.dashboard') }}" class="btn btn-primary px-4 py-2 shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- 🌈 Style & Animation -->
    <style>
        .animate-container {
            animation: fadeIn 0.7s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }

        .text-gradient {
            background: linear-gradient(90deg, #007bff, #00c9a7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .class-card {
            border-radius: 1.2rem;
            transition: all .4s ease;
            cursor: pointer;
        }

        .class-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }

        .class-card-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 40% 30%, rgba(0, 123, 255, 0.08), transparent 70%);
            transition: opacity .4s ease;
            opacity: 0;
        }

        .class-card:hover .class-card-bg {
            opacity: 1;
        }

        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00c9a7);
        }

        .stats-box {
            transition: transform .3s ease;
        }

        .class-card:hover .stats-box {
            transform: scale(1.07);
        }

        /* Quick Action */
        .quick-action {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 0.4rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: all .3s ease;
        }

        .class-card:hover .quick-action {
            opacity: 1;
            transform: translateY(0);
        }

        .quick-action button {
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform .2s ease, background .3s ease;
        }

        .quick-action button:hover {
            transform: scale(1.2);
            filter: brightness(1.1);
        }
    </style>

    <!-- ⚙️ Script Animasi -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fadeElements = document.querySelectorAll('.fade-in-up');
            fadeElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
@endsection