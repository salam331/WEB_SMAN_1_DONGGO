@extends('layouts.app')

@section('page_title', 'Detail Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book me-2"></i> Detail Mata Pelajaran
                </h5>
                <a href="{{ route('teachers.subjects.index') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Subject Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i> Informasi Mata Pelajaran
                                </h6>
                                <div class="row">
                                    <div class="col-sm-4 fw-semibold text-muted">Kode:</div>
                                    <div class="col-sm-8">{{ $subject->code }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4 fw-semibold text-muted">Nama:</div>
                                    <div class="col-sm-8">{{ $subject->name }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4 fw-semibold text-muted">KKM:</div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-light text-secondary px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-percentage me-1"></i> {{ $subject->kkm }}
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4 fw-semibold text-muted">Kelas:</div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-school me-1"></i> {{ $subjectTeacher->classRoom->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-success mb-3">
                                    <i class="fas fa-chart-line me-2"></i> Statistik
                                </h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="text-center">
                                            <div class="h4 mb-0 text-primary">{{ $subject->materials->count() }}</div>
                                            <small class="text-muted">Materi</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-center">
                                            <div class="h4 mb-0 text-success">{{ $subject->exams->count() }}</div>
                                            <small class="text-muted">Ujian</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Materials Section -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-file-alt me-2"></i> Materi Pembelajaran
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($subject->materials && $subject->materials->count() > 0)
                            <div class="row">
                                @foreach($subject->materials as $material)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm rounded-3 h-100">
                                            <div class="card-body">
                                                <h6 class="card-title fw-semibold">{{ $material->title }}</h6>
                                                <p class="card-text text-muted small">{{ Str::limit($material->description, 100) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i> {{ $material->created_at->format('d M Y') }}
                                                    </small>
                                                    <a href="{{ route('teachers.materials.show', $material) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                                <p>Belum ada materi untuk mata pelajaran ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Exams Section -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold text-success">
                            <i class="fas fa-clipboard-check me-2"></i> Ujian
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($subject->exams && $subject->exams->count() > 0)
                            <div class="row">
                                @foreach($subject->exams as $exam)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm rounded-3 h-100">
                                            <div class="card-body">
                                                <h6 class="card-title fw-semibold">{{ $exam->name }}</h6>
                                                <p class="card-text text-muted small">Total Score: {{ $exam->total_score }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i> {{ $exam->start_date->format('d M Y') }} - {{ $exam->end_date->format('d M Y') }}
                                                    </small>
                                                    <a href="{{ route('teachers.exams.show', $exam) }}"
                                                        class="btn btn-sm btn-outline-success rounded-pill">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-clipboard-check fa-3x mb-3 text-secondary"></i>
                                <p>Belum ada ujian untuk mata pelajaran ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🎨 Style Tambahan -->
    <style>
        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #0dcaf0, #0d6efd) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-outline-success:hover {
            background: linear-gradient(135deg, #198754, #20c997) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
@endsection
