@extends('layouts.app')

@section('page_title', 'Tambah Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book me-2"></i> Tambah Mata Pelajaran
                </h5>
                <a href="{{ route('admin.subjects.index') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body bg-light bg-gradient p-4">
                <!-- Pesan Error -->
                {{-- @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                        <h6 class="fw-semibold mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i> Terdapat {{ $errors->count() }} kesalahan:
                        </h6>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <!-- Form Tambah Mata Pelajaran -->
                <form action="{{ route('admin.subjects.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-4">
                        <!-- Informasi Utama -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i> Informasi Mata Pelajaran
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-code me-1 text-primary"></i> Kode Mata Pelajaran
                                </label>
                                <input type="text" name="code" value="{{ old('code') }}" required maxlength="10"
                                    placeholder="Contoh: MTK, IPA, IPS"
                                    class="form-control border-0 shadow-sm rounded-3 p-2">
                                <div class="form-text">Kode unik maksimal 10 karakter.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-book-open me-1 text-primary"></i> Nama Mata Pelajaran
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    placeholder="Contoh: Matematika, Fisika, Bahasa Indonesia"
                                    class="form-control border-0 shadow-sm rounded-3 p-2">
                                <div class="form-text">Masukkan nama lengkap mata pelajaran.</div>
                            </div>
                        </div>

                        <!-- Detail Tambahan -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-cogs me-2"></i> Detail Kriteria
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-chart-line me-1 text-primary"></i> Kriteria Ketuntasan Minimal (KKM)
                                </label>
                                <input type="number" name="kkm" value="{{ old('kkm', 75) }}" min="0" max="100" required
                                    class="form-control border-0 shadow-sm rounded-3 p-2">
                                <div class="form-text">Nilai KKM antara 0–100.</div>
                            </div>

                            <div class="alert alert-info border-0 shadow-sm rounded-3 small">
                                <i class="fas fa-lightbulb me-1 text-primary"></i>
                                Pastikan semua data sudah benar sebelum menyimpan.
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-end border-top pt-4">
                        <a href="{{ route('admin.subjects.index') }}"
                            class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm ms-2">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Style -->
    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc 0%, #007bff 100%) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-outline-secondary:hover {
            background: #e9ecef !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .alert ul {
            margin-bottom: 0;
        }

        .form-text {
            color: #6c757d;
            font-size: 0.85rem;
        }
    </style>
@endsection