@extends('layouts.app')

@section('page_title', 'Tambah Kelas')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-chalkboard me-2"></i> Tambah Kelas
                </h5>
                <a href="{{ route('admin.classes') }}"
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

                {{-- @if (session('error'))
                    <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                        <i class="fas fa-times-circle me-1"></i> {{ session('error') }}
                    </div>
                @endif --}}

                <!-- Form -->
                <form action="{{ route('admin.classes.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-4">
                        <!-- Informasi Kelas -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Informasi Kelas
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-tag me-1 text-primary"></i> Nama Kelas
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    placeholder="Contoh: X IPA 1" class="form-control border-0 shadow-sm rounded-3 p-2">
                                <div class="form-text">Masukkan nama kelas yang unik.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-layer-group me-1 text-primary"></i> Level
                                </label>
                                <select name="level" class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                    <option value="">Pilih Level</option>
                                    <option value="10" {{ old('level') == '10' ? 'selected' : '' }}>Kelas 10</option>
                                    <option value="11" {{ old('level') == '11' ? 'selected' : '' }}>Kelas 11</option>
                                    <option value="12" {{ old('level') == '12' ? 'selected' : '' }}>Kelas 12</option>
                                </select>
                                <div class="form-text">Pilih tingkat kelas (10, 11, atau 12).</div>
                            </div>
                        </div>

                        <!-- Detail Tambahan -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-users-cog me-2"></i> Detail Tambahan
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-user-friends me-1 text-primary"></i> Kapasitas
                                </label>
                                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 30) }}" min="1"
                                    max="50" class="form-control border-0 shadow-sm rounded-3 p-2">
                                <div class="form-text">Maksimal jumlah siswa (1–50).</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-user-tie me-1 text-primary"></i> Wali Kelas
                                </label>
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id"
                                    class="form-select border-0 shadow-sm rounded-3 p-2">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}" {{ old('homeroom_teacher_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->user->name }} @if($t->nip) ({{ $t->nip }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Hanya menampilkan guru yang belum menjadi wali kelas.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-end border-top pt-4">
                        <a href="{{ route('admin.classes') }}"
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