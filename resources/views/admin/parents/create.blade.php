@extends('layouts.app')

@section('title', 'Tambah Orang Tua')

@section('content')
    <div class="container-fluid py-12">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div
                        class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-user-plus me-2"></i> Tambah Orang Tua Baru
                        </h4>
                        <a href="{{ route('admin.parents.index') }}"
                            class="btn btn-light btn-sm text-primary fw-semibold shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.parents.store') }}" method="POST" class="p-4">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control shadow-sm @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Masukkan nama lengkap..." required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control shadow-sm @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="contoh: nama@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control shadow-sm @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="Masukkan nomor aktif...">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="relation_to_student" class="form-label fw-semibold">Hubungan dengan
                                    Siswa</label>
                                <select name="relation_to_student" id="relation_to_student"
                                    class="form-select shadow-sm @error('relation_to_student') is-invalid @enderror">
                                    <option value="Ayah" {{ old('relation_to_student') == 'Ayah' ? 'selected' : '' }}>Ayah
                                    </option>
                                    <option value="Ibu" {{ old('relation_to_student') == 'Ibu' ? 'selected' : '' }}>Ibu
                                    </option>
                                    <option value="Wali" {{ old('relation_to_student') == 'Wali' ? 'selected' : '' }}>Wali
                                    </option>
                                    <option value="Ayah/Ibu" {{ old('relation_to_student', 'Ayah/Ibu') == 'Ayah/Ibu' ? 'selected' : '' }}>Ayah/Ibu</option>
                                </select>
                                @error('relation_to_student')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="password" class="form-label fw-semibold">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" name="password" id="password"
                                    class="form-control shadow-sm @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password..." required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Minimal 8 karakter
                                    disarankan kombinasi huruf & angka.</small>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm fw-semibold">
                                <i class="fas fa-save me-2"></i> Simpan
                            </button>
                            <a href="{{ route('admin.parents.index') }}"
                                class="btn btn-outline-secondary px-4 py-2 shadow-sm">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .form-control,
        .form-select {
            border-radius: 0.6rem;
            transition: all 0.25s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 0.6rem;
            transition: transform 0.15s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        label.form-label {
            color: #2b2b2b;
        }

        .card {
            backdrop-filter: blur(6px);
            background-color: rgba(255, 255, 255, 0.97);
        }

        @media (max-width: 576px) {
            .card-header h4 {
                font-size: 1rem;
            }

            .btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }
        }
    </style>
@endsection