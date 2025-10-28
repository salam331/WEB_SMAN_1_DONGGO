@extends('layouts.app')

@section('page_title', 'Edit Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-user-graduate me-2"></i> Edit Data Siswa
                </h5>
                <a href="{{ route('admin.students') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <form action="{{ route('admin.students.update', $student) }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-user me-1 text-primary"></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" value="{{ old('name', $student->user->name ?? '') }}"
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
                            <input type="email" name="email" value="{{ old('email', $student->user->email ?? '') }}"
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
                                <i class="fas fa-lock me-1 text-primary"></i> Password (Kosongkan jika tidak ingin diubah)
                            </label>
                            <input type="password" name="password" class="form-control border-0 shadow-sm rounded-3 p-2"
                                placeholder="••••••••">
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-lock me-1 text-primary"></i> Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation"
                                class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="••••••••">
                        </div>

                        <!-- NIS & NISN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-id-card me-1 text-primary"></i> NIS
                            </label>
                            <input type="text" name="nis" value="{{ old('nis', $student->nis) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-id-badge me-1 text-primary"></i> NISN
                            </label>
                            <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2">
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-map-marker-alt me-1 text-primary"></i> Tempat Lahir
                            </label>
                            <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i> Tanggal Lahir
                            </label>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2">
                        </div>

                        <!-- Jenis Kelamin & Kelas -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-venus-mars me-1 text-primary"></i> Jenis Kelamin
                            </label>
                            <select name="gender" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-school me-1 text-primary"></i> Kelas
                            </label>
                            <select name="class_id" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id', $student->rombel_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Alamat -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-home me-1 text-primary"></i> Alamat
                            </label>
                            <textarea name="address" rows="2"
                                class="form-control border-0 shadow-sm rounded-3 p-2">{{ old('address', $student->address) }}</textarea>
                        </div>

                        <!-- Orang Tua -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-users me-1 text-primary"></i> Orang Tua
                            </label>
                            <select name="parent_id" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Pilih Orang Tua</option>
                                @foreach($parents as $p)
                                    <option value="{{ $p->id }}" {{ old('parent_id', $student->parent_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->user->name ?? 'Orang Tua #' . $p->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.students') }}"
                            class="btn btn-outline-secondary px-4 py-2 ms-2 rounded-pill shadow-sm">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm text-white">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ✨ Style Tambahan -->
    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056d2 0%, #0d6efd 100%) !important;
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

        label.form-label i {
            opacity: 0.9;
        }
    </style>
@endsection