@extends('layouts.app')

@section('page_title', 'Edit Pengumuman')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-bullhorn me-2"></i> Edit Pengumuman
                </h5>
                <a href="{{ route('admin.announcements') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Error Alerts -->
                {{-- @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Terdapat kesalahan dalam input:
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif --}}

                <!-- Form -->
                <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="p-2">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-heading me-1 text-primary"></i> Judul <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}"
                            class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="Masukkan judul pengumuman"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-align-left me-1 text-primary"></i> Konten <span class="text-danger">*</span>
                        </label>
                        <textarea name="content" id="content" rows="6" class="form-control border-0 shadow-sm rounded-3 p-2"
                            placeholder="Tulis isi pengumuman"
                            required>{{ old('content', $announcement->content) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="is_public" {{ old('is_public', $announcement->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-secondary" for="is_public">
                                <i class="fas fa-globe me-1 text-primary"></i> Publikasikan ke halaman publik
                            </label>
                            <small class="form-text text-muted d-block">
                                Centang jika pengumuman ini akan ditampilkan di halaman pengumuman publik
                            </small>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.announcements') }}"
                            class="btn btn-outline-secondary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 🎨 Style Tambahan -->
        <style>
            .form-label {
                font-size: 0.95rem;
            }

            .form-control {
                transition: all 0.2s ease-in-out;
            }

            .form-control:focus {
                border-color: #0d6efd !important;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
            }

            .btn-outline-secondary:hover {
                background: linear-gradient(135deg, #6c757d, #adb5bd);
                color: #fff;
                transform: translateY(-2px);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #0062cc, #007bff);
                transform: translateY(-2px);
            }

            .card {
                transition: box-shadow 0.3s ease, transform 0.2s ease;
            }

            .card:hover {
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
                transform: translateY(-2px);
            }
        </style>
    </div>
@endsection