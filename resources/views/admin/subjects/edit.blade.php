@extends('layouts.app')

@section('page_title', 'Edit Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book me-2"></i> Edit Mata Pelajaran
                </h5>
                <a href="{{ route('admin.subjects.index') }}"
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
                <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" class="p-2">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Kode Mata Pelajaran -->
                        <div class="col-md-6">
                            <label for="code" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-code me-1 text-primary"></i> Kode Mata Pelajaran <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" id="code" name="code" value="{{ old('code', $subject->code) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="Contoh: MTK, IPA, IPS"
                                required>
                            <small class="text-muted">Kode unik (maksimal 10 karakter)</small>
                        </div>

                        <!-- Nama Mata Pelajaran -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-book-open me-1 text-primary"></i> Nama Mata Pelajaran <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $subject->name) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2"
                                placeholder="Contoh: Matematika, Fisika, Bahasa Indonesia" required>
                            <small class="text-muted">Nama lengkap mata pelajaran</small>
                        </div>

                        <!-- KKM -->
                        <div class="col-md-6">
                            <label for="kkm" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-percentage me-1 text-primary"></i> Kriteria Ketuntasan Minimal (KKM)
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="kkm" name="kkm" value="{{ old('kkm', $subject->kkm) }}" min="0"
                                max="100" class="form-control border-0 shadow-sm rounded-3 p-2"
                                placeholder="Masukkan nilai KKM (0–100)" required>
                            <small class="text-muted">Nilai antara 0–100</small>
                        </div>

                        <!-- Materi Pembelajaran -->
                        <div class="col-md-12">
                            <label for="material_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-book-reader me-1 text-primary"></i> Materi Pembelajaran Utama
                            </label>
                            <select id="material_id" name="material_id" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Pilih Materi (Opsional)</option>
                                @foreach($subject->materials as $material)
                                    <option value="{{ $material->id }}" {{ old('material_id', $subject->material_id) == $material->id ? 'selected' : '' }}>
                                        {{ $material->title }} - {{ $material->classRoom->name ?? 'Kelas Tidak Ditemukan' }} - {{ $material->teacher->user->name ?? 'Guru Tidak Ditemukan' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Materi utama yang mewakili mata pelajaran ini</small>
                        </div>

                        <!-- Guru Utama -->
                        <div class="col-md-12">
                            <label for="teacher_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-user-tie me-1 text-primary"></i> Guru Utama
                            </label>
                            <select id="teacher_id" name="teacher_id" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Pilih Guru (Opsional)</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name ?? 'Nama tidak tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Guru utama yang bertanggung jawab atas mata pelajaran ini</small>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('admin.subjects.index') }}"
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
    </div>

    <!-- 🎨 Style Tambahan -->
    <style>
        .form-label {
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-secondary:hover {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: #fff;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff);
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }
    </style>
@endsection