@extends('layouts.app')

@section('title', 'Detail Galeri')

@section('content')
    <div class="container-fluid py-12">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-10">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-images me-2"></i> Detail Galeri
                        </h4>
                        <div>
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-warning btn-sm rounded-pill me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.galleries.index') }}" class="btn btn-light btn-sm fw-semibold shadow-sm text-primary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4 align-items-start">
                            <div class="col-md-6 text-center">
                                @if($gallery->image)
                                    <div class="image-container position-relative">
                                        <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}"
                                            class="img-fluid rounded-3 shadow-sm transition-all"
                                            style="max-height: 320px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-light py-5 rounded-3 shadow-sm">
                                        <i class="fas fa-image fa-4x text-muted mb-2"></i>
                                        <p class="text-muted">Tidak ada gambar tersedia</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-muted" width="140">Judul</th>
                                                <td class="fw-semibold text-dark">{{ $gallery->title }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Deskripsi</th>
                                                <td>{{ $gallery->description ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Kategori</th>
                                                <td>{{ $gallery->category ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Status</th>
                                                <td>
                                                    @if($gallery->is_public)
                                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                                            <i class="fas fa-globe me-1"></i> Publik
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                            <i class="fas fa-lock me-1"></i> Privat
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Dibuat</th>
                                                <td>
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $gallery->created_at->translatedFormat('d F Y, H:i') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Diupdate</th>
                                                <td>
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $gallery->updated_at->translatedFormat('d F Y, H:i') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between align-items-center py-3 px-4">
                        <small class="text-muted fst-italic">
                            <i class="fas fa-info-circle me-1"></i> Diperbarui terakhir pada
                            {{ $gallery->updated_at->diffForHumans() }}
                        </small>
                        <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus galeri ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Hapus Galeri
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #6610f2);
        }

        .transition-all {
            transition: all 0.4s ease-in-out;
        }

        .image-container img:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush