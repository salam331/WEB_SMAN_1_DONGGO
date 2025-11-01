@extends('layouts.app')

@section('title', 'Pengumuman - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-bullhorn me-2"></i> Pengumuman
                        </h5>
                        <small class="fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        @if($announcements->count() > 0)
                            <div class="row g-4">
                                @foreach($announcements as $announcement)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 border-0 rounded-4 shadow-hover position-relative overflow-hidden">
                                            <!-- Header Gradient -->
                                            <div class="announcement-header"
                                                style="height: 59px; background: linear-gradient(135deg, #{{ substr(md5($announcement->title), 0, 6) }}, #764ba2);">
                                            </div>

                                            <!-- Body -->
                                            <div class="card-body bg-white position-relative p-4"
                                                style="margin-top: -55px; border-radius: 20px;">
                                                <div class="d-flex align-items-start justify-content-between mb-3">
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold text-primary mb-1">
                                                            {{ $announcement->title }}
                                                            @if($announcement->pinned)
                                                                <span class="badge bg-danger ms-2">
                                                                    <i class="fas fa-thumbtack me-1"></i> Disematkan
                                                                </span>
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-user me-1"></i>
                                                            {{ $announcement->postedBy->name ?? 'Admin' }}
                                                            <i class="fas fa-calendar ms-3 me-1"></i>
                                                            {{ $announcement->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>

                                                    @if($announcement->target_audience == 'students')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-user-graduate me-1"></i> Siswa
                                                        </span>
                                                    @elseif($announcement->target_audience == 'teachers')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-chalkboard-teacher me-1"></i> Guru
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-users me-1"></i> Semua
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="text-muted small mb-3 announcement-text">
                                                    {!! Str::limit($announcement->content, 200) !!}
                                                    @if(strlen($announcement->content) > 200)
                                                        <a href="#" class="text-primary fw-semibold ms-1"
                                                            onclick="showFullContent(this, '{{ addslashes($announcement->content) }}')">
                                                            Baca selengkapnya...
                                                        </a>
                                                    @endif
                                                </p>

                                                @if($announcement->attachment)
                                                    <div class="mb-3">
                                                        <a href="{{ asset('storage/' . $announcement->attachment) }}"
                                                            class="btn btn-outline-primary btn-sm rounded-pill w-100 shadow-sm"
                                                            target="_blank">
                                                            <i class="fas fa-paperclip me-1"></i> Lihat Lampiran
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $announcements->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted fw-bold">Belum ada pengumuman</h5>
                                <p class="text-muted small">Pengumuman akan muncul di sini setelah admin atau guru membuat
                                    pengumuman baru.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="fas fa-bullhorn me-2"></i> Detail Pengumuman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="announcementContent"></div>
            </div>
        </div>
    </div>

    <script>
        function showFullContent(element, fullContent) {
            document.getElementById('announcementContent').innerHTML = fullContent;
            new bootstrap.Modal(document.getElementById('announcementModal')).show();
        }
    </script>

    <style>
        /* Fade & slide animation */
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

        /* Card hover animation */
        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Decorative header for cards */
        .announcement-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            opacity: 0.9;
        }

        /* Button hover smoothness */
        .btn-outline-primary {
            transition: all 0.25s ease;
        }

        .btn-outline-primary:hover {
            background-color: #4e73df;
            color: #fff;
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.3);
        }

        /* Text spacing */
        .announcement-text {
            line-height: 1.6;
        }

        /* Smooth modal transition */
        .modal-content {
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endsection