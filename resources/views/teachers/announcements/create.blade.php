@extends('layouts.app')

@section('title', 'Buat Pengumuman - SMAN 1 DONGGO')

@section('content')
    <style>
        /* Animasi dan efek lembut */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }

        .card-custom {
            background: linear-gradient(145deg, #ffffff, #f8f9fc);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.15rem rgba(78, 115, 223, 0.25);
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4e73df, #224abe);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #224abe, #4e73df);
            transform: scale(1.05);
        }

        .btn-secondary:hover {
            transform: scale(1.05);
            transition: all 0.2s ease;
        }

        label.form-label {
            font-weight: 600;
            color: #4e73df;
        }

        textarea {
            resize: none;
        }

        .card-header {
            background: linear-gradient(90deg, #4e73df, #224abe);
            color: #fff;
            border-radius: 20px 20px 0 0 !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title i {
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

    <div class="container-fluid animate-fadeInUp">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg card-custom">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-bullhorn me-2"></i>Buat Pengumuman Baru
                        </h5>
                        <a href="{{ route('teachers.announcements') }}"
                            class="btn btn-light text-primary shadow-sm fw-semibold">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('teachers.announcements.store') }}" method="POST"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">Judul Pengumuman <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control form-control-lg"
                                            placeholder="Masukkan judul pengumuman..." value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="content" class="form-label">Isi Pengumuman <span
                                                class="text-danger">*</span></label>
                                        <textarea name="content" id="content" rows="8" class="form-control form-control-lg"
                                            placeholder="Tulis isi pengumuman secara lengkap..."
                                            required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="target_audience" class="form-label">Target Penerima</label>
                                        <select name="target_audience" id="target_audience"
                                            class="form-select form-select-lg">
                                            <option value="">Pilih Target</option>
                                            <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Semua
                                            </option>
                                            <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                                            <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                                            <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>Orang Tua</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="attachment" class="form-label">Lampiran File</label>
                                        <input type="file" name="attachment" id="attachment"
                                            class="form-control form-control-lg" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <small class="text-muted d-block mt-1">
                                            Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                                        </small>
                                        @error('attachment')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1"
                                            id="is_published" {{ old('is_published', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_published">
                                            Terbitkan Pengumuman Sekarang
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-3">
                                <a href="{{ route('teachers.announcements') }}"
                                    class="btn btn-light border fw-semibold px-4 py-2">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm fw-semibold">
                                    <i class="fas fa-save me-1"></i> Simpan Pengumuman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection