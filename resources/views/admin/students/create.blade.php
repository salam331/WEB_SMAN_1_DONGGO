@extends('layouts.app')

@section('page_title', 'Tambah Siswa')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-user-graduate me-2"></i> Tambah Data Siswa
            </h5>
            <a href="{{ route('admin.students') }}" class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card-body bg-light bg-gradient p-4">
            <!-- Pesan Error -->
            @if ($errors->any())
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
            @endif

            <form action="{{ route('admin.students.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="row g-4">
                    <!-- Informasi Akun -->
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user me-2"></i> Informasi Akun
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-id-card me-1 text-primary"></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            @error('name')
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            @error('email')
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-lock me-1 text-primary"></i> Password
                                </label>
                                <input type="password" name="password"
                                    class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-lock me-1 text-primary"></i> Konfirmasi Password
                                </label>
                                <input type="password" name="password_confirmation"
                                    class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            </div>
                        </div>
                    </div>

                    <!-- Data Siswa -->
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-school me-2"></i> Data Siswa
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-id-badge me-1 text-primary"></i> NIS
                                </label>
                                <input type="text" name="nis" value="{{ old('nis') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-id-card-alt me-1 text-primary"></i> NISN
                                </label>
                                <input type="text" name="nisn" value="{{ old('nisn') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-map-marker-alt me-1 text-primary"></i> Tempat Lahir
                                </label>
                                <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i> Tanggal Lahir
                                </label>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-venus-mars me-1 text-primary"></i> Jenis Kelamin
                                </label>
                                <select name="gender" class="form-select border-0 shadow-sm rounded-3 p-2">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-chalkboard me-1 text-primary"></i> Kelas
                                </label>
                                <select name="class_id" class="form-select border-0 shadow-sm rounded-3 p-2">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat dan Orang Tua (Full Width) -->
                <div class="row mt-3">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-home me-1 text-primary"></i> Alamat
                        </label>
                        <textarea name="address" rows="3"
                            class="form-control border-0 shadow-sm rounded-3 p-3 w-100"
                            placeholder="Masukkan alamat lengkap siswa...">{{ old('address', $student->address ?? '') }}</textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold text-secondary">
                            <i class="fas fa-user-friends me-1 text-primary"></i> Orang Tua
                        </label>
                        <select name="parent_id" class="form-select border-0 shadow-sm rounded-3 p-3 w-100">
                            <option value="">Pilih Orang Tua</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ old('parent_id', $student->parent_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->user->name ?? 'Orang Tua #' . $p->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('admin.students') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm">
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

<style>
    .form-control:focus, .form-select:focus {
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
</style>
@endsection
