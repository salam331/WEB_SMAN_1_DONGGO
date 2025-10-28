@extends('layouts.app')

@section('page_title', 'Edit Data Pengguna')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-dark d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-user-edit me-2"></i> Edit Data Pengguna
                </h5>
                <a href="{{ route('admin.users') }}"
                    class="btn btn-light btn-sm text-dark fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body bg-light bg-gradient p-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('put')
                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-id-card me-1 text-primary"></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            @error('name')
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            @error('email')
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-lock me-1 text-primary"></i> Password (Kosongkan jika tidak ingin mengubah)
                            </label>
                            <input type="password" name="password"
                                class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="••••••••">
                            @error('password')
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-phone me-1 text-primary"></i> Nomor Telepon
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="+62 ..." maxlength="15">
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-user-tag me-1 text-primary"></i> Role Pengguna
                            </label>
                            <select name="role" class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-toggle-on me-1 text-primary"></i> Status Akun
                            </label>
                            <select name="is_active" class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.users') }}"
                            class="btn btn-outline-secondary px-4 py-2 ms-2 rounded-pill shadow-sm">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm text-dark">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahan efek hover & transisi lembut -->
    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.2) !important;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #e0a800 0%, #ffc107 100%) !important;
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
    </style>
@endsection
