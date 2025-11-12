@extends('layouts.app')

@section('title', 'Edit Orang Tua')

@section('content')
    <div class="container-fluid py-12">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="card shadow border-0 rounded-4">
                    <div
                        class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-user-edit me-2"></i> Edit Data Orang Tua
                        </h4>
                        <a href="{{ route('admin.parents.index') }}"
                            class="btn btn-light btn-sm text-primary fw-semibold shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.parents.update', $parent) }}" method="POST" class="p-4">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control shadow-sm @error('name') is-invalid @enderror"
                                    value="{{ old('name', $parent->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control shadow-sm @error('email') is-invalid @enderror"
                                    value="{{ old('email', $parent->user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control shadow-sm @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $parent->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="relation_to_student" class="form-label fw-semibold">Hubungan dengan
                                    Siswa</label>
                                <select name="relation_to_student" id="relation_to_student"
                                    class="form-select shadow-sm @error('relation_to_student') is-invalid @enderror">
                                    <option value="Ayah" {{ old('relation_to_student', $parent->relation_to_student) == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="Ibu" {{ old('relation_to_student', $parent->relation_to_student) == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="Wali" {{ old('relation_to_student', $parent->relation_to_student) == 'Wali' ? 'selected' : '' }}>Wali</option>
                                    <option value="Ayah/Ibu" {{ old('relation_to_student', $parent->relation_to_student) == 'Ayah/Ibu' ? 'selected' : '' }}>Ayah/Ibu</option>
                                </select>
                                @error('relation_to_student')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password Baru <small
                                        class="text-muted">(Opsional)</small></label>
                                <input type="password" name="password" id="password"
                                    class="form-control shadow-sm @error('password') is-invalid @enderror"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                        value="1" {{ old('is_active', $parent->user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">
                                        <i class="fas fa-toggle-on me-1 text-success"></i> Status Akun Aktif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-end gap-2 align-items-center">
                            <a href="{{ route('admin.parents.index') }}"
                                class="btn btn-outline-secondary px-4 py-2 shadow-sm">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm fw-semibold">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #0062cc);
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 0.5rem;
            transition: transform 0.15s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection