@extends('layouts.app')

@section('page_title', 'Tambah Materi Pembelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book-reader me-2"></i> Tambah Materi Pembelajaran
                </h5>
                <a href="{{ route('teacher.materials.index') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Error Alerts -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Terdapat kesalahan dalam input:
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data" class="p-2">
                    @csrf

                    <div class="row g-4">
                        <!-- Mata Pelajaran -->
                        <div class="col-md-6">
                            <label for="subject_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-book me-1 text-primary"></i> Mata Pelajaran <span
                                    class="text-danger">*</span>
                            </label>
                            <select id="subject_id" name="subject_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                                required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih mata pelajaran untuk materi ini</small>
                        </div>

                        <!-- Kelas -->
                        <div class="col-md-6">
                            <label for="class_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-door-open me-1 text-primary"></i> Kelas <span
                                    class="text-danger">*</span>
                            </label>
                            <select id="class_id" name="class_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                                required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih kelas yang akan menerima materi ini</small>
                        </div>

                        <!-- Judul Materi -->
                        <div class="col-md-6">
                            <label for="title" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-heading me-1 text-primary"></i> Judul Materi <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2"
                                placeholder="Contoh: Bab 1 - Pengenalan Topik"
                                required>
                            <small class="text-muted">Judul singkat materi pembelajaran</small>
                        </div>

                        <!-- Status Publikasi -->
                        <div class="col-md-6">
                            <label for="is_published" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-globe me-1 text-primary"></i> Status Publikasi
                            </label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                    value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publikasi materi (tersedia untuk siswa)
                                </label>
                            </div>
                            <small class="text-muted">Centang untuk membuat materi dapat dilihat siswa</small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-md-12">
                            <label for="description" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-align-left me-1 text-primary"></i> Deskripsi
                            </label>
                            <textarea id="description" name="description" class="form-control border-0 shadow-sm rounded-3 p-2"
                                placeholder="Masukkan deskripsi materi pembelajaran..." rows="4">{{ old('description') }}</textarea>
                            <small class="text-muted">Penjelasan detail tentang konten materi</small>
                        </div>

                        <!-- File -->
                        <div class="col-md-12">
                            <label for="file_path" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-file-upload me-1 text-primary"></i> Unggah File
                            </label>
                            <div class="input-group rounded-3 overflow-hidden shadow-sm">
                                <input type="file" id="file_path" name="file_path" class="form-control border-0"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov"
                                    onchange="updateFileName(this)">
                                <span class="input-group-text bg-light border-0 text-muted">
                                    <i class="fas fa-upload"></i>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, PNG, GIF, MP4, AVI, MOV
                                <br>Ukuran maksimal: 50MB
                            </small>
                            <div id="file-name" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('teacher.materials.index') }}"
                            class="btn btn-outline-secondary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-save me-1"></i> Simpan Materi
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

        .form-control:focus,
        .form-select:focus {
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

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
    </style>

    <script>
        function updateFileName(input) {
            const fileName = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                fileName.innerHTML = `
                    <div class="alert alert-info rounded-pill px-3 py-2 mb-0">
                        <i class="fas fa-file me-2"></i>
                        <strong>${file.name}</strong> (${sizeMB} MB)
                    </div>
                `;
            } else {
                fileName.innerHTML = '';
            }
        }
    </script>
@endsection
