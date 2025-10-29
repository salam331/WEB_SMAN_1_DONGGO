@extends('layouts.public')

@section('title', 'Galeri')

@section('content')
    <div class="container-fluid">

        <!-- 🌈 HERO SECTION -->
        <section class="hero-section text-white py-5 mt-4 mb-4 rounded-5 shadow-lg position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-attachment: fixed;">
            <div class="container text-center position-relative" style="z-index: 2;">
                <h1 class="display-5 fw-bold mb-3 animate__animated animate__fadeInDown">
                    Galeri Sekolah
                </h1>
                <p class="lead animate__animated animate__fadeInUp">
                    Kumpulan momen dan kegiatan terbaik di {{ $school->name ?? 'SMAN 1 Donggo' }}
                </p>
            </div>
            <div class="hero-overlay"></div>
        </section>

        <!-- 🖼️ GALLERY GRID -->
        <section class="gallery-section fade-section">
            <div class="container">
                @if($galleries->count() > 0)
                    <div class="gallery-wrapper d-flex flex-wrap justify-content-center gap-4" id="gallery-grid">
                        @foreach($galleries as $gallery)
                            <div class="gallery-card" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                data-image="{{ Storage::url($gallery->image) }}" data-title="{{ $gallery->title }}"
                                data-description="{{ $gallery->description }}"
                                data-date="{{ $gallery->created_at->format('d F Y') }}">
                                <div class="card-img-container">
                                    <img src="{{ Storage::url($gallery->image) }}" class="gallery-image"
                                        alt="{!! e($gallery->title) !!}" loading="lazy" />
                                    <div class="gallery-overlay">
                                        <div class="gallery-overlay-content">
                                            <i class="fas fa-search-plus fa-2x mb-2 text-white"></i>
                                            <h6 class="mb-1">{{ $gallery->title }}</h6>
                                            <small>{{ $gallery->created_at->format('d F Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body text-center mt-2">
                                    <h6 class="fw-bold">{{ $gallery->title }}</h6>
                                    @if($gallery->description)
                                        <p class="text-muted small">{{ Str::limit($gallery->description, 100) }}</p>
                                    @endif
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $gallery->created_at->format('d F Y') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Jika overflow, tambahkan scroll -->
                    <div class="d-flex justify-content-center mt-5">
                        {{ $galleries->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-images fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Galeri masih kosong</h4>
                        <p class="text-muted">Foto dan dokumentasi kegiatan akan segera ditampilkan di sini.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- 📸 GALLERY MODAL -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass-effect border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-primary" id="galleryModalLabel">Detail Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded-3 mb-3 shadow-sm" style="max-height: 70vh;">
                    <h5 id="modalTitle" class="fw-bold mb-2 text-dark"></h5>
                    <p id="modalDescription" class="text-muted"></p>
                    <small id="modalDate" class="text-muted"></small>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ✨ Modal Galeri
            const galleryModal = document.getElementById('galleryModal');
            galleryModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                document.getElementById('modalImage').src = button.getAttribute('data-image');
                document.getElementById('modalTitle').textContent = button.getAttribute('data-title');
                document.getElementById('modalDescription').textContent = button.getAttribute('data-description');
                document.getElementById('modalDate').textContent = button.getAttribute('data-date');
            });

            // ✨ Animasi muncul setiap section saat discroll
            const observer = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) e.target.classList.add('visible');
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.fade-section').forEach(sec => observer.observe(sec));
        });
    </script>
@endpush

@push('styles')
    <style>
        /* 🌈 Hero Section */
        .hero-section {
            position: relative;
            border-radius: 0 0 50px 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            z-index: 1;
        }

        /* 🖼️ Gallery */
        .gallery-item {
            cursor: pointer;
            border-radius: 20px;
            transition: all 0.35s ease;
        }

        .gallery-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 1.5rem 2rem rgba(0, 0, 0, 0.1);
        }

        .card-img-container {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .gallery-item:hover .gallery-image {
            transform: scale(1.07);
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.35s ease;
            color: white;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay-content {
            text-align: center;
            transform: translateY(10px);
            transition: transform 0.35s ease;
        }

        .gallery-item:hover .gallery-overlay-content {
            transform: translateY(0);
        }

        /* ✨ Modal efek kaca */
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }

        /* 🌟 Fade-in animasi */
        .fade-section {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsif */
        @media (max-width: 768px) {
            .card-img-container {
                height: 200px;
            }
        }

        .gallery-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            overflow-x: auto;
            padding-bottom: 1rem;
            scroll-behavior: smooth;
        }

        /* Card gallery tetap sama ukuran */
        .gallery-card {
            width: 280px;
            flex: 0 0 auto;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.35s ease;
            cursor: pointer;
        }

        .gallery-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 1.5rem 2rem rgba(0, 0, 0, 0.1);
        }

        /* Container gambar */
        .card-img-container {
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
            border-radius: 15px;
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Menyesuaikan gambar dalam card */
            transition: transform 0.35s ease;
        }

        .gallery-card:hover .gallery-image {
            transform: scale(1.07);
        }

        /* Overlay */
        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.35s ease;
            color: white;
        }

        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay-content {
            text-align: center;
            transform: translateY(10px);
            transition: transform 0.35s ease;
        }

        .gallery-card:hover .gallery-overlay-content {
            transform: translateY(0);
        }

        /* Scroll bar custom */
        .gallery-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .gallery-wrapper::-webkit-scrollbar-thumb {
            background: rgba(100, 100, 100, 0.4);
            border-radius: 4px;
        }

        .gallery-wrapper::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush