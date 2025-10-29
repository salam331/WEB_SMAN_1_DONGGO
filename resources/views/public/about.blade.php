@extends('layouts.public')

@section('title', 'Tentang Kami')

@section('content')
    <div class="container-fluid">

        <!-- 🌈 HERO SECTION -->
        <section class="hero-section text-white py-5 mt-4 mb-4 rounded-5 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                               background-attachment: fixed;">
            <div class="container text-center position-relative" style="z-index: 2;">
                <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInDown">
                    {!! $school->hero_title ?? 'Tentang ' . ($school->name ?? 'SMAN 1 Donggo') !!}
                </h1>
                <p class="lead animate__animated animate__fadeInUp">
                    {{ $school->hero_description ?? 'Membangun Generasi Unggul dengan Pendidikan Berkualitas' }}
                </p>
            </div>
            <div class="hero-overlay"></div>
        </section>

        <!-- 🏫 SCHOOL INFO -->
        <section class="school-info-section fade-section">
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h3 class="mb-4 fw-bold text-primary">Profil Sekolah</h3>
                                    <ul class="list-unstyled text-muted">
                                        <li><strong>Nama:</strong> {{ $school->name ?? 'SMAN 1 Donggo' }}</li>
                                        <li><strong>Alamat:</strong>
                                            {{ $school->address ?? 'Jl. Pendidikan No. 1, Donggo, Bima' }}</li>
                                        <li><strong>Telepon:</strong> {{ $school->phone ?? '(0374) 123456' }}</li>
                                        <li><strong>Email:</strong> {{ $school->email ?? 'info@sman1donggo.sch.id' }}</li>
                                        <li><strong>Akreditasi:</strong> {{ $school->accreditation ?? 'A' }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 text-center">
                                    <img src="{{ asset('image/logo.png') }}" alt="Logo Sekolah"
                                        class="img-fluid rounded shadow-sm"
                                        onerror="this.src='https://via.placeholder.com/400x300/007bff/ffffff?text=SMAN+1+Donggo';">
                                </div>
                            </div>

                            @if($school && $school->school_description)
                                <hr class="my-4">
                                <h4 class="fw-bold text-primary">Deskripsi</h4>
                                <p class="text-muted">{{ $school->school_description }}</p>
                            @endif

                            @if($school && $school->vision)
                                <h4 class="fw-bold text-primary mt-4">Visi</h4>
                                <p class="text-muted">{{ $school->vision }}</p>
                            @endif

                            @if($school && $school->mission)
                                <h4 class="fw-bold text-primary mt-4">Misi</h4>
                                <p class="text-muted">{{ $school->mission }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 🌟 FEATURES -->
        <section class="features-section fade-section mt-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-primary">Keunggulan Kami</h2>
                <p class="lead text-muted">Komitmen kami dalam memberikan pendidikan terbaik</p>
            </div>
            <div class="row g-4">
                @php
                    $features = $school->features ?? [
                        ['icon' => 'fa-graduation-cap', 'color' => 'primary', 'title' => 'Kurikulum Modern', 'desc' => 'Kurikulum terkini yang disesuaikan dengan perkembangan zaman.'],
                        ['icon' => 'fa-users', 'color' => 'success', 'title' => 'Guru Berkompeten', 'desc' => 'Tenaga pengajar profesional dan berpengalaman.'],
                        ['icon' => 'fa-flask', 'color' => 'info', 'title' => 'Fasilitas Lengkap', 'desc' => 'Sarana memadai untuk mendukung pembelajaran.'],
                        ['icon' => 'fa-trophy', 'color' => 'warning', 'title' => 'Prestasi Gemilang', 'desc' => 'Prestasi akademik & non-akademik.'],
                        ['icon' => 'fa-handshake', 'color' => 'danger', 'title' => 'Kerjasama', 'desc' => 'Bermitra dengan banyak institusi.'],
                        ['icon' => 'fa-heart', 'color' => 'secondary', 'title' => 'Nilai Karakter', 'desc' => 'Menanamkan pendidikan karakter kuat.']
                    ];
                @endphp
                @foreach($features as $f)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center feature-card rounded-4">
                            <div class="card-body py-5">
                                <i class="fas {{ $f['icon'] }} fa-4x text-{{ $f['color'] }} mb-4"></i>
                                <h4 class="fw-bold mb-3">{{ $f['title'] }}</h4>
                                <p class="text-muted">{{ $f['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 📊 STATISTICS -->
        <section class="stats-section fade-section mt-5 bg-white">
            <div class="card border-0 shadow-sm bg-gradient text-white rounded-4 overflow-hidden"
                style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                <div class="card-body py-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary mb-4">Statistik Sekolah</h3>
                    </div>
                    <div class="row text-center text-success">
                        @php
                            $statistics = [
                                ['label' => 'Siswa Aktif', 'value' => $studentsCount ?? 0],
                                ['label' => 'Guru & Staff', 'value' => $teachersCount ?? 0],
                                ['label' => 'Kelas', 'value' => $classesCount ?? 0],
                                ['label' => 'Prestasi', 'value' => $school->prestasi_count ?? '100+'] // nanti disesuaikan
                            ];
                        @endphp

                        @foreach($statistics as $stat)
                            <div class="col-md-3 mb-4">
                                <h2 class="display-5 fw-bold">{{ $stat['value'] }}</h2>
                                <p>{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        /* ✨ Hero background parallax overlay */
        .hero-overlay {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            z-index: 1;
        }

        /* Animasi masuk lembut untuk setiap section */
        .fade-section {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.9s ease, transform 0.9s ease;
        }

        .fade-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Efek hover fitur */
        .feature-card {
            transition: all 0.35s ease;
            border-radius: 20px;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 1.8rem 2.2rem rgba(0, 0, 0, 0.12);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Efek muncul saat scroll (tanpa blur/pudar)
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.fade-section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
@endpush