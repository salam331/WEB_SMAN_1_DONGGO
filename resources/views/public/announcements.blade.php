@extends('layouts.public')

@section('title', 'Pengumuman')

@section('content')
    <div class="container-fluid">

        <!-- Hero Section -->
        <section class="hero-section text-white py-5 mt-4 mb-5 rounded-bottom-5 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                   border-radius: 0 0 50px 50px;
                   position: relative;
                   overflow: hidden;">
            <div class="container text-center position-relative z-2">
                <h1 class="display-5 fw-bold animate__animated animate__fadeInDown">Pengumuman Sekolah</h1>
                <p class="lead mb-0 animate__animated animate__fadeInUp">
                    Informasi penting dan berita terbaru dari {{ $school->name ?? 'SMAN 1 Donggo' }}
                </p>
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background-image: url('{{ asset('image/logo.png') }}');
                        background-repeat: no-repeat;
                        background-position: center;
                        background-size: 100px;">
            </div>
        </section>

        <!-- Announcement List -->
        <section class="announcement-section container pb-5">
            @if($announcements->count() > 0)
                <div class="row justify-content-center">
                    @foreach($announcements as $announcement)
                        <div class="col-md-10 col-lg-8 mb-4">
                            <div
                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        @if($announcement->pinned)
                                            <i class="fas fa-thumbtack text-warning fa-lg me-3 mt-1"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h4 class="card-title mb-2 fw-semibold text-primary">{{ $announcement->title }}</h4>
                                            <div class="text-muted small d-flex align-items-center flex-wrap">
                                                <i class="fas fa-calendar me-2"></i>
                                                <span>{{ $announcement->created_at->format('l, d F Y H:i') }}</span>
                                                @if($announcement->postedBy)
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-user me-1"></i>
                                                    <span>{{ $announcement->postedBy->name }}</span>
                                                @endif
                                                @if($announcement->pinned)
                                                    <span class="badge bg-warning text-dark ms-2">Disematkan</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-text mb-3 text-secondary">
                                        {!! nl2br(e(Str::limit($announcement->content, 300))) !!}
                                        @if(strlen($announcement->content) > 300)
                                            <span class="text-muted">...</span>
                                        @endif
                                    </div>

                                    @if($announcement->attachment)
                                        <div class="mb-3">
                                            <i class="fas fa-paperclip me-2 text-primary"></i>
                                            <a href="{{ Storage::url($announcement->attachment) }}" target="_blank"
                                                class="text-decoration-none fw-semibold">
                                                <i class="fas fa-download me-1"></i>Lampiran
                                            </a>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i> Dibaca {{ $announcement->views ?? 0 }} kali
                                        </small>
                                        <button class="btn btn-outline-primary btn-sm rounded-pill"
                                            onclick="showAnnouncement({{ $announcement->id }})">
                                            <i class="fas fa-eye me-1"></i> Baca Lengkap
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $announcements->links() }}
                </div>
            @else
                <div class="text-center py-5 animate__animated animate__fadeIn">
                    <i class="fas fa-bullhorn fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Belum ada pengumuman</h4>
                    <p class="text-muted">Pengumuman terbaru akan segera dipublikasikan di sini.</p>
                </div>
            @endif
        </section>

    </div>

    <!-- Announcement Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="announcementModalLabel">Detail Pengumuman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="announcementContent" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Konten dinamis di sini -->
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showAnnouncement(id) {
            // sementara hanya placeholder
            alert('Fitur detail pengumuman sedang dikembangkan.');
        }
    </script>
@endpush

@push('styles')
    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: "";
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            height: 80px;
            background: white;
            clip-path: ellipse(75% 100% at 50% 100%);
        }

        .card {
            transition: all 0.3s ease-in-out;
            border-radius: 1.2rem;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .btn-outline-primary:hover {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }

        .animate__animated {
            animation-duration: 0.8s;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 1.9rem;
            }
        }
    </style>
@endpush