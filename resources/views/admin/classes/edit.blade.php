@extends('layouts.app')

@section('page_title', 'Edit Kelas')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-chalkboard-teacher me-2"></i> Edit Kelas
            </h5>
            <span class="badge bg-light text-primary fw-semibold px-3 py-2 shadow-sm">
                <i class="fas fa-user-graduate me-1"></i> {{ $classRoom->students()->count() }} Siswa
            </span>
        </div>

        <!-- Body -->
        <div class="card-body bg-light bg-gradient p-4">
            <!-- Alert Error -->
            {{-- @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif --}}

            {{-- @if($errors->any())
                <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4">
                    <h6 class="fw-semibold mb-2">
                        <i class="fas fa-triangle-exclamation me-2"></i>
                        Terdapat {{ $errors->count() }} kesalahan pada form:
                    </h6>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

            <!-- Form -->
            <form action="{{ route('admin.classes.update', $classRoom) }}" method="POST" class="p-3 bg-white shadow-sm rounded-3 border">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Nama Kelas -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-school me-1 text-primary"></i> Nama Kelas
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $classRoom->name) }}"
                            placeholder="Contoh: X IPA 1"
                            class="form-control border-0 shadow-sm rounded-3 p-2" required>
                        <small class="text-muted">Masukkan nama kelas yang unik</small>
                    </div>

                    <!-- Level -->
                    <div class="col-md-6">
                        <label for="level" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-layer-group me-1 text-primary"></i> Level
                        </label>
                        <select name="level" id="level" class="form-select border-0 shadow-sm rounded-3 p-2" required>
                            <option value="10" {{ old('level', $classRoom->level) == '10' ? 'selected' : '' }}>Kelas 10</option>
                            <option value="11" {{ old('level', $classRoom->level) == '11' ? 'selected' : '' }}>Kelas 11</option>
                            <option value="12" {{ old('level', $classRoom->level) == '12' ? 'selected' : '' }}>Kelas 12</option>
                        </select>
                    </div>

                    <!-- Kapasitas -->
                    <div class="col-md-6">
                        <label for="capacity" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-users me-1 text-primary"></i> Kapasitas
                            <small class="text-muted">(Saat ini: {{ $classRoom->students()->count() }} siswa)</small>
                        </label>
                        <input type="number" name="capacity" id="capacity"
                            value="{{ old('capacity', $classRoom->capacity) }}"
                            min="{{ $classRoom->students()->count() }}" max="50"
                            class="form-control border-0 shadow-sm rounded-3 p-2">
                        <small class="text-muted">Kapasitas minimal harus sama dengan jumlah siswa saat ini</small>
                    </div>

                    <!-- Ruangan -->
                    <div class="col-md-6">
                        <label for="room" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-door-closed me-1 text-primary"></i> Ruangan
                        </label>
                        <input type="text" name="room" id="room"
                            value="{{ old('room', $classRoom->room) }}"
                            placeholder="Contoh: Ruang 101"
                            class="form-control border-0 shadow-sm rounded-3 p-2">
                        <small class="text-muted">Nama ruangan atau lokasi kelas (opsional)</small>
                    </div>

                    <!-- Wali Kelas -->
                    <div class="col-md-6">
                        <label for="homeroom_teacher_id" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-chalkboard-user me-1 text-primary"></i> Wali Kelas
                        </label>
                        <select name="homeroom_teacher_id" id="homeroom_teacher_id"
                            class="form-select border-0 shadow-sm rounded-3 p-2">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" 
                                    {{ old('homeroom_teacher_id', $classRoom->homeroom_teacher_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->user->name }} 
                                    @if($t->nip) ({{ $t->nip }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Menampilkan guru yang belum menjadi wali kelas (atau wali kelas saat ini)</small>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                    <a href="{{ route('admin.classes') }}"
                        class="btn btn-outline-secondary rounded-pill px-4 fw-semibold shadow-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit"
                        class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 🎨 Style Tambahan -->
<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-3px);
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0062cc, #007bff) !important;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #6c757d, #adb5bd) !important;
        color: #fff !important;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
</style>
@endsection
