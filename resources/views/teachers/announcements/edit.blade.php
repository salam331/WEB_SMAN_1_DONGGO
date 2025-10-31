@extends('layouts.app')

@section('title', 'Edit Pengumuman - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <!-- HEADER -->
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="fas fa-edit me-2"></i>Edit Pengumuman
                        </h5>
                        <a href="{{ route('teachers.announcements') }}" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <!-- BODY -->
                    <div class="card-body bg-light">
                        <form action="{{ route('teachers.announcements.update', $announcement) }}" method="POST"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Kolom Kiri -->
                                <div class="col-md-8">
                                    <div class="form-floating mb-4">
                                        <input type="text" name="title" id="title"
                                            class="form-control form-control-lg shadow-sm border-0"
                                            placeholder="Judul Pengumuman" value="{{ old('title', $announcement->title) }}"
                                            required>
                                        <label for="title">Judul Pengumuman <span class="text-danger">*</span></label>
                                        @error('title')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="content" class="form-label fw-semibold">Isi Pengumuman <span
                                                class="text-danger">*</span></label>
                                        <textarea name="content" id="content" rows="9"
                                            class="form-control shadow-sm border-0"
                                            placeholder="Tuliskan isi pengumuman di sini..."
                                            required>{{ old('content', $announcement->content) }}</textarea>
                                        @error('content')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                                        <div class="mb-3">
                                            <label for="target_audience" class="form-label fw-semibold">Target
                                                Audiens</label>
                                            <select name="target_audience" id="target_audience"
                                                class="form-select shadow-sm border-0">
                                                <option value="">Pilih Target</option>
                                                <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>Semua
                                                </option>
                                                <option value="students" {{ old('target_audience', $announcement->target_audience) == 'students' ? 'selected' : '' }}>Siswa
                                                </option>
                                                <option value="teachers" {{ old('target_audience', $announcement->target_audience) == 'teachers' ? 'selected' : '' }}>Guru
                                                </option>
                                                <option value="parents" {{ old('target_audience', $announcement->target_audience) == 'parents' ? 'selected' : '' }}>Orang
                                                    Tua</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="attachment" class="form-label fw-semibold">Lampiran</label>
                                            <input type="file" name="attachment" id="attachment"
                                                class="form-control shadow-sm border-0"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            @if($announcement->attachment)
                                                <small class="text-muted d-block mt-1">
                                                    File saat ini:
                                                    <a href="{{ Storage::url($announcement->attachment) }}" target="_blank">
                                                        {{ basename($announcement->attachment) }}
                                                    </a>
                                                </small>
                                            @endif
                                            <small class="text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Maks:
                                                2MB)</small>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_published" value="1"
                                                id="is_published" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_published">
                                                <i class="fas fa-bullhorn me-1 text-primary"></i> Terbitkan Pengumuman
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <a href="{{ route('teachers.announcements') }}" class="btn btn-secondary px-4 shadow-sm">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Efek lembut */
        .form-control,
        .form-select {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .card-body.bg-light {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection