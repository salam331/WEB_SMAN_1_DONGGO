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
                            <i class="fas fa-book-reader me-2"></i> Materi Pembelajaran
                        </h5>
                        @php
                            $now = now()->setTimezone('Asia/Makassar');
                        @endphp
                        <small class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</small>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">
                        @if($subjects->count() > 0)
                            <div class="row g-4 justify-content-center">
                                @foreach($subjects as $subject)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 border-0 rounded-4 shadow-hover position-relative overflow-hidden">
                                            <!-- Decorative Gradient Header -->
                                            <div class="subject-header"
                                                style="height: 64px; background: linear-gradient(135deg, #{{ substr(md5($subject->name), 0, 6) }}, #667eea);">
                                            </div>

                                            <!-- Content -->
                                            <div class="card-body bg-white position-relative p-4"
                                                style="margin-top: -60px; border-radius: 20px;">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="icon-wrapper me-3">
                                                        <i class="fas fa-book text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-primary mb-1">{{ $subject->name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $subject->teacher->user->name ?? ($subject->subjectTeachers->first()?->teacher->user->name ?? '-') }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <p class="text-muted small mb-3">
                                                    <i class="fas fa-layer-group me-1 text-secondary"></i>
                                                    {{ $subject->materials->count() }} materi tersedia
                                                </p>

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $subject->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>

                                                <a href="{{ route('student.materials.show', $subject) }}"
                                                    class="btn btn-outline-primary w-100 rounded-pill fw-semibold shadow-sm">
                                                    <i class="fas fa-eye me-1"></i> Lihat Materi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted fw-bold">Belum ada materi pembelajaran</h5>
                                <p class="text-muted small">Materi akan muncul di sini setelah guru mengunggah materi untuk
                                    kelas Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth hover + fade animation */
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

        /* Card hover effect */
        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Gradient header on top of card */
        .subject-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            opacity: 0.9;
        }

        /* Icon styling */
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
        .btn-outline-primary {
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background-color: #4e73df;
            color: #fff;
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.3);
        }
    </style>
@endsection