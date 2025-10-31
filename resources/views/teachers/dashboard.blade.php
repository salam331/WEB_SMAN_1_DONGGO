@extends('layouts.app')

@section('title', 'SMAN 1 DONGGO - Manajemen Sistem Sekolah')

@section('content')
    <div class="container-fluid animate-container">
        <!-- 🌟 Header Section -->
        <div class="text-center mb-5 fade-in-up">
            <h2 class="fw-bold text-gradient mb-1">
                <i class="fas fa-chalkboard-teacher me-2"></i> Manajemen Sistem Sekolah
            </h2>
            <p class="text-muted">Kelola seluruh aktivitas guru dan proses pembelajaran dengan mudah dan interaktif</p>
        </div>

        <!-- 💼 Modul Guru -->
        <div class="row g-4 mb-5">
            @php
                $modules = [
                    ['route' => 'teachers.dashboard', 'icon' => 'fas fa-home', 'color' => 'primary', 'title' => 'Dashboard Guru', 'desc' => 'Lihat ringkasan kegiatan, statistik, dan jadwal hari ini.'],
                    ['route' => 'teachers.classes', 'icon' => 'fas fa-school', 'color' => 'success', 'title' => 'Kelas', 'desc' => 'Kelola data kelas dan wali kelas yang dibimbing.'],
                    ['route' => 'teachers.attendances', 'icon' => 'fas fa-calendar-check', 'color' => 'info', 'title' => 'Absensi', 'desc' => 'Pantau kehadiran siswa setiap pertemuan.'],
                    ['route' => 'teachers.schedules', 'icon' => 'fas fa-calendar-alt', 'color' => 'warning', 'title' => 'Jadwal Pelajaran', 'desc' => 'Atur dan lihat jadwal mengajar mingguan.'],
                    ['route' => 'teachers.materials.index', 'icon' => 'fas fa-book-reader', 'color' => 'danger', 'title' => 'Materi Pembelajaran', 'desc' => 'Unggah, ubah, dan bagikan materi pembelajaran.'],
                    ['route' => 'teachers.subjects.index', 'icon' => 'fas fa-book', 'color' => 'secondary', 'title' => 'Mata Pelajaran', 'desc' => 'Lihat dan atur mata pelajaran yang diampu.'],
                    ['route' => 'teachers.announcements', 'icon' => 'fas fa-bullhorn', 'color' => 'dark', 'title' => 'Pengumuman', 'desc' => 'Bagikan pengumuman kepada siswa.'],
                    ['route' => 'teachers.notifications', 'icon' => 'fas fa-bell', 'color' => 'primary', 'title' => 'Notifikasi', 'desc' => 'Kelola dan baca notifikasi terbaru.'],
                    ['route' => 'teachers.grades', 'icon' => 'fas fa-graduation-cap', 'color' => 'success', 'title' => 'Nilai', 'desc' => 'Input dan pantau nilai siswa secara terstruktur.'],
                    ['route' => 'teachers.exams.index', 'icon' => 'fas fa-file-alt', 'color' => 'info', 'title' => 'Ujian', 'desc' => 'Atur jadwal ujian dan penilaian hasil.'],
                ];
            @endphp

            @foreach ($modules as $m)
                <div class="col-xl-3 col-md-6 fade-in-up">
                    <div class="card module-card border-0 shadow-lg position-relative overflow-hidden"
                        onclick="window.location.href='{{ route($m['route']) }}'">
                        <div class="module-glow"></div>
                        <div class="card-body text-center py-5 position-relative z-2">
                            <div class="floating-icon mb-3 mx-auto">
                                <i class="{{ $m['icon'] }} fa-2x text-{{ $m['color'] }}"></i>
                            </div>
                            <h6 class="fw-bold text-dark">{{ $m['title'] }}</h6>
                            <p class="text-muted small mb-3">{{ $m['desc'] }}</p>
                            <span class="badge bg-{{ $m['color'] }} px-3 py-2 rounded-pill"></span>
                        </div>

                        <!-- 🚀 Quick Action Menu -->
                        <div class="quick-action d-flex justify-content-center align-items-center">
                            <button class="btn btn-light btn-sm me-2 shadow-sm" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-{{ $m['color'] }} btn-sm shadow-sm" title="Aksi Cepat">
                                <i class="fas fa-bolt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 🎨 CSS Effects -->
    <style>
        /* Container Animation */
        .animate-container {
            animation: fadeInScale 0.8s ease forwards;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(.97)
            }

            100% {
                opacity: 1;
                transform: scale(1)
            }
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp .8s ease forwards;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Gradient Title */
        .text-gradient {
            background: linear-gradient(90deg, #007bff, #00c9a7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Card Style */
        .module-card {
            background: #fff;
            border-radius: 1.2rem;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .module-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 1.25rem 2rem rgba(0, 0, 0, 0.15);
        }

        /* Glow Background */
        .module-glow {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 40% 40%, rgba(0, 123, 255, 0.15), transparent 60%);
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .module-card:hover .module-glow {
            opacity: 1;
            animation: glowMove 3s infinite alternate ease-in-out;
        }

        @keyframes glowMove {
            0% {
                background-position: 0% 0%;
            }

            100% {
                background-position: 100% 100%;
            }
        }

        /* Floating Icon */
        .floating-icon {
            width: 80px;
            height: 80px;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            animation: floatIcon 3s ease-in-out infinite;
        }

        @keyframes floatIcon {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* Quick Action Menu */
        .quick-action {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            opacity: 0;
            transform: translateY(20px);
            transition: all .4s ease;
            z-index: 3;
        }

        .module-card:hover .quick-action {
            opacity: 1;
            transform: translateY(0);
        }

        /* Button Animation */
        .quick-action button {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            transition: transform .3s ease, background-color .3s ease;
        }

        .quick-action button:hover {
            transform: scale(1.15);
            filter: brightness(1.1);
        }

        /* Pulse Effect */
        .module-card:hover .badge {
            animation: pulse 1.3s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 rgba(0, 123, 255, 0);
            }

            50% {
                box-shadow: 0 0 15px rgba(0, 123, 255, 0.4);
            }
        }
    </style>

    <!-- 🧩 Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('.fade-in-up');
            items.forEach((el, i) => el.style.animationDelay = `${i * 0.1}s`);
        });
    </script>
@endsection