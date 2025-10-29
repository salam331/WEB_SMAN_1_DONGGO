@extends('layouts.public')

@section('title', 'SMAN 1 DONGGO - Beranda')

@section('content')
    <div class="container-fluid">

        <!-- 🌈 HERO SECTION -->
        <section class="hero-section text-white py-4 mt-4 rounded-bottom-5 shadow-sm mb-4 rounded-5"
            style="background: linear-gradient(135deg, #7ebf99 0%, #667eea 100%);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                        <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInDown">
                            Selamat Datang di <br><span class="text-primary">SMAN 1 Donggo</span>
                        </h1>
                        <p class="lead mb-4 animate__animated animate__fadeInUp">
                            Sekolah berkualitas dengan pendidikan yang unggul dan berintegritas.
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-semibold shadow-sm">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk Sistem
                            </a>
                            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-info-circle me-2"></i>Tentang Kami
                            </a>
                            <a href="{{ route('public.gallery') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-images me-2"></i>Galeri
                            </a>
                            <a href="{{ route('public.announcements') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-bullhorn me-2"></i>Pengumuman
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-phone me-2"></i>Kontak
                            </a>
                        </div>
                    </div>
                    <div
                        class="col-lg-6 text-center d-flex align-items-center justify-content-center justify-content-lg-end gap-4 flex-wrap">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo Sekolah"
                            class="img-fluid animate__animated animate__fadeIn"
                            style="max-width: 250px; animation-duration: 2s;">
                    </div>
                </div>
            </div>
        </section>


        <!-- 💠 QUICK STATS -->
        <section class="stats-section mb-5">
            <div class="row g-4">
                @php
                    $stats = [
                        ['icon' => 'fa-users', 'color' => 'primary', 'title' => 'Siswa', 'desc' => 'Jumlah siswa aktif', 'value' => $school->total_students ?? '500+'],
                        ['icon' => 'fa-chalkboard-teacher', 'color' => 'success', 'title' => 'Guru', 'desc' => 'Tenaga pengajar profesional', 'value' => $school->total_teachers ?? '50+'],
                        ['icon' => 'fa-graduation-cap', 'color' => 'info', 'title' => 'Program', 'desc' => 'Program studi unggulan', 'value' => '6'],
                        ['icon' => 'fa-trophy', 'color' => 'warning', 'title' => 'Prestasi', 'desc' => 'Penghargaan dan prestasi', 'value' => '100+']
                    ];
                @endphp
                @foreach($stats as $s)
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center h-100 rounded-4 stat-card">
                            <div class="card-body py-4">
                                <div
                                    class="icon-wrapper bg-{{ $s['color'] }} bg-opacity-10 text-{{ $s['color'] }} mb-3 mx-auto">
                                    <i class="fas {{ $s['icon'] }} fa-3x"></i>
                                </div>
                                <h5 class="fw-semibold mb-1">{{ $s['title'] }}</h5>
                                <p class="text-muted small mb-2">{{ $s['desc'] }}</p>
                                <h3 class="fw-bold text-{{ $s['color'] }}">{{ $s['value'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 📣 ANNOUNCEMENTS -->
        @if($announcements->count() > 0)
            <section class="announcements-section mb-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-gradient border-0 py-3 px-4"
                        style="background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);">
                        <h4 class="fw-bold mb-0 text-primary">
                            <i class="fas fa-bullhorn me-2"></i>Pengumuman Terbaru
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($announcements as $a)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                @if($a->pinned)
                                                    <i class="fas fa-thumbtack text-warning me-2 mt-1"></i>
                                                @endif
                                                <div>
                                                    <h5 class="fw-semibold mb-2">{{ $a->title }}</h5>
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-calendar me-1"></i>{{ $a->created_at->format('d M Y') }}
                                                        @if($a->postedBy)
                                                            • {{ $a->postedBy->name }}
                                                        @endif
                                                    </p>
                                                    <p class="text-muted">{{ Str::limit($a->content, 120) }}</p>
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill"
                                                        data-id="{{ $a->id }}" onclick="showAnnouncementDetail(this)">
                                                        Baca Selengkapnya
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <!-- 📢 Modal Detail Pengumuman -->
            <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg animate__animated animate__fadeInUp">
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-bullhorn me-2"></i> Detail Pengumuman
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h4 id="announcementTitle" class="fw-bold text-primary mb-2"></h4>
                            <p class="text-muted small mb-3" id="announcementMeta"></p>
                            <hr>
                            <div id="announcementContent" class="text-secondary" style="line-height: 1.8;"></div>
                        </div>
                        <div class="modal-footer border-0">
                            <button class="btn btn-outline-primary rounded-pill px-4" data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        @endif

        <!-- 📞 CONTACT INFO -->
        <section class="contact-section">
            <div class="card border-0 shadow-sm bg-primary text-white text-center rounded-4 overflow-hidden">
                <div class="card-body py-5">
                    <h3 class="fw-bold mb-4"><i class="fas fa-envelope-open me-2"></i>Hubungi Kami</h3>
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-4">
                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                            <p>Jl. Pesanggrahan No.19,
                                Doridungga, Kec. Donggo, <br>Kabupaten Bima, Nusa Tenggara Bar.</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-phone fa-2x mb-2"></i>
                            <p>(+62) 85339458990</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-envelope fa-2x mb-2"></i>
                            <p>info@sman1donggo.sch.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

{{-- @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('nav.navbar');
        const sections = document.querySelectorAll('section');
        if (!header || sections.length === 0) return;

        let isScrolling;
        const headerHeight = header.offsetHeight;

        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const fadeStart = headerHeight + 10;
            const fadeEnd = headerHeight + 800;

            // Efek aktif saat scroll
            sections.forEach(sec => {
                const rect = sec.getBoundingClientRect();
                const offsetTop = rect.top + window.scrollY;
                const distance = scrollTop - offsetTop + window.innerHeight / 3;
                let fadeAmount = (distance - fadeStart) / (fadeEnd - fadeStart);
                fadeAmount = Math.min(Math.max(fadeAmount, 0), 1);

                // Efek blur & pudar
                sec.style.opacity = 1 - fadeAmount * 1.2;
                sec.style.filter = `blur(${fadeAmount * 10}px)`;
                sec.style.transform = `translateY(${fadeAmount * 10}px)`;
            });

            // Deteksi saat scroll berhenti → tampilkan kembali semua section
            clearTimeout(isScrolling);
            isScrolling = setTimeout(() => {
                sections.forEach(sec => {
                    sec.style.transition = 'opacity 0.4s ease-out, filter 0.4s ease-out, transform 0.4s ease-out';
                    sec.style.opacity = 1;
                    sec.style.filter = 'blur(0)';
                    sec.style.transform = 'translateY(0)';
                });
            }, 100); // 100ms setelah berhenti scroll
        });
    });
</script>
@endpush --}}

@push('styles')
    <style>
        /* Efek awal semua section */
        section {
            transition: opacity 0.25s ease-in-out, filter 0.25s ease-in-out, transform 0.25s ease-in-out;
            will-change: opacity, filter, transform;
        }

        /* Saat user hover pada card agar tetap elegan */
        .stat-card,
        .hover-card {
            transition: all .35s ease;
        }

        .stat-card:hover,
        .hover-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 1.25rem 2rem rgba(0, 0, 0, 0.1);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all .3s ease;
        }

        .hover-card:hover .icon-wrapper {
            transform: rotate(8deg) scale(1.1);
        }
    </style>
@endpush


{{-- @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('nav.navbar');
        const hero = document.querySelector('.hero-section');
        if (!header || !hero) return;

        const headerHeight = header.offsetHeight;

        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const fadeStart = headerHeight;       // mulai pudar tepat setelah header
            const fadeEnd = headerHeight + 200;   // jarak pendek = pudar cepat

            let fadeAmount = (scrollTop - fadeStart) / (fadeEnd - fadeStart);
            fadeAmount = Math.min(Math.max(fadeAmount, 0), 1);

            hero.style.opacity = 1 - fadeAmount * 1.5;
            hero.style.filter = `blur(${fadeAmount * 10}px)`;
            hero.style.transform = `scale(${1 - fadeAmount * 0.05}) translateY(-${fadeAmount * 20}px)`;
        });
    });
</script>
@endpush --}}

@push('scripts')
    <script>
        function showAnnouncementDetail(button) {
            const id = button.getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('announcementModal'));
            const titleEl = document.getElementById('announcementTitle');
            const contentEl = document.getElementById('announcementContent');
            const metaEl = document.getElementById('announcementMeta');

            // Reset modal content sementara
            titleEl.innerHTML = '<span class="text-muted">Memuat...</span>';
            contentEl.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            metaEl.textContent = '';

            modal.show();

            // Ambil data lewat AJAX
            fetch(`/announcement/detail/${id}`)
                .then(res => res.json())
                .then(data => {
                    titleEl.textContent = data.title;
                    metaEl.textContent = `${data.date} • ${data.author}`;
                    contentEl.innerHTML = data.content;
                })
                .catch(err => {
                    console.error(err);
                    contentEl.innerHTML = '<p class="text-danger">Gagal memuat detail pengumuman.</p>';
                });
        }
    </script>
@endpush


@push('styles')
    <style>
        .hero-section {
            opacity: 1;
            filter: blur(0);
            transform: translateY(0) scale(1);
            transition: opacity 0.3s ease-out, filter 0.3s ease-out, transform 0.3s ease-out;
            will-change: opacity, filter, transform;
        }

        .modal-content {
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endpush