@extends('layouts.app')

@section('title', 'Materi Pembelajaran - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-book-reader me-2"></i> Materi Pembelajaran - {{ $subject->name }}
                        </h5>
                        <a href="{{ route('student.materials') }}"
                            class="btn btn-light btn-sm rounded-pill fw-semibold shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        @if($materials->count() > 0)
                            <div class="row g-4 justify-content-center">
                                @foreach($materials as $material)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 border-0 rounded-4 shadow-hover position-relative overflow-hidden">
                                            <!-- Decorative Gradient Header -->
                                            <div class="subject-header"
                                                style="height: 54px; background: linear-gradient(135deg, #{{ substr(md5($material->title), 0, 6) }}, #667eea);">
                                            </div>

                                            <!-- Card Content -->
                                            <div class="card-body bg-white position-relative p-4"
                                                style="margin-top: -50px; border-radius: 20px;">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="icon-wrapper me-3">
                                                        <i class="fas fa-file-alt text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-primary mb-1">{{ $material->title }}</h6>
                                                        <small class="text-muted">{{ $material->subject->name ?? '-' }}</small>
                                                    </div>
                                                </div>

                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($material->description ?? 'Tidak ada deskripsi', 100) }}
                                                </p>

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i> {{ $material->teacher->user->name ?? '-' }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $material->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>

                                                @if($material->file_path)
                                                    <a href="{{ route('student.materials.download', $material) }}"
                                                        class="btn btn-outline-primary w-100 rounded-pill fw-semibold shadow-sm"
                                                        target="_blank">
                                                        <i class="fas fa-download me-1"></i> Unduh Materi
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary w-100 rounded-pill fw-semibold shadow-sm"
                                                        disabled>
                                                        <i class="fas fa-file me-1"></i> File Tidak Tersedia
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $materials->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted fw-bold">Belum ada materi pembelajaran</h5>
                                <p class="text-muted small">Materi akan muncul di sini setelah guru mengunggah materi untuk mata
                                    pelajaran ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth page fade-in animation */
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

        /* Hover elevation on cards */
        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Gradient header for each material */
        .subject-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            opacity: 0.9;
        }

        /* Icon circle */
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

        /* Button transition */
        .btn-outline-primary,
        .btn-outline-secondary {
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background-color: #4e73df;
            color: #fff;
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.3);
        }
    </style>
@endsection