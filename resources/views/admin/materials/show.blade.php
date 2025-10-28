@extends('layouts.app')

@section('page_title', 'Detail Materi Pembelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book-reader me-2"></i> Detail Materi Pembelajaran
                </h5>
                <div>
                    <a href="{{ route('admin.materials.index') }}"
                        class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('admin.materials.edit', $material) }}"
                        class="btn btn-warning btn-sm text-dark fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill shadow-sm fw-semibold"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Informasi Materi -->
                <div class="row g-4 mb-5">
                    <div class="col-md-8">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                            <h6 class="fw-bold text-primary mb-4">
                                <i class="fas fa-heading me-2"></i> Informasi Materi
                            </h6>
                            <div class="mb-3">
                                <strong class="text-secondary">Judul:</strong>
                                <p class="text-dark">{{ $material->title }}</p>
                            </div>
                            <div class="mb-3">
                                <strong class="text-secondary">Deskripsi:</strong>
                                <p class="text-dark" style="white-space: pre-wrap;">{{ $material->description ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i> Informasi Tambahan
                            </h6>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1"><i class="fas fa-globe me-1"></i> Status:</small>
                                @if($material->is_published)
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-check me-1"></i> Dipublikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-lock me-1"></i> Draft
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1"><i class="fas fa-calendar me-1"></i> Dibuat:</small>
                                <small class="text-dark">{{ $material->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1"><i class="fas fa-sync me-1"></i> Diperbarui:</small>
                                <small class="text-dark">{{ $material->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Terkait -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-book me-2"></i> Mata Pelajaran
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-book-open text-success fs-5"></i>
                                <div>
                                    <strong class="d-block">{{ $material->subject->name ?? '-' }}</strong>
                                    <small class="text-muted">{{ $material->subject->code ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-door-open me-2"></i> Kelas
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-users text-info fs-5"></i>
                                <div>
                                    <strong class="d-block">{{ $material->classRoom->name ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user-tie me-2"></i> Guru Pembuat
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-user-circle text-success fs-5"></i>
                                <div>
                                    <strong class="d-block">{{ $material->teacher->user->name ?? '-' }}</strong>
                                    <small class="text-muted">NIP: {{ $material->teacher->nip ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-file-alt me-2"></i> File
                            </h6>
                            @if($material->file_path)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-file-download text-warning fs-5"></i>
                                    <div>
                                        <a href="{{ route('admin.materials.download', $material) }}" 
                                            class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold text-decoration-none">
                                            <i class="fas fa-download me-1"></i> Unduh File
                                        </a>
                                    </div>
                                </div>
                            @else
                                <small class="text-muted">Belum ada file yang diunggah</small>
                            @endif
                        </div>
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

        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection
