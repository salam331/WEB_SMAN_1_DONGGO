@extends('layouts.app')

@section('page_title', 'Edit Jadwal Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-calendar-alt me-2"></i> Edit Jadwal Pelajaran
                </h5>
                <a href="{{ route('admin.schedules.index') }}"
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
                <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="p-2">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Kelas -->
                        <div class="col-md-6">
                            <label for="class_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-school me-1 text-primary"></i> Kelas <span class="text-danger">*</span>
                            </label>
                            <select id="class_id" name="class_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                                required>
                                <option value="">Pilih Kelas</option>
                                @foreach(\App\Models\ClassRoom::all() as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="col-md-6">
                            <label for="subject_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-book me-1 text-primary"></i> Mata Pelajaran <span
                                    class="text-danger">*</span>
                            </label>
                            <select id="subject_id" name="subject_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                                required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach(\App\Models\Subject::all() as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Guru -->
                        <div class="col-md-6">
                            <label for="teacher_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-user-tie me-1 text-primary"></i> Guru <span class="text-danger">*</span>
                            </label>
                            <select id="teacher_id" name="teacher_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                                required>
                                <option value="">Pilih Guru</option>
                                @foreach(\App\Models\Teacher::with('user')->get() as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }} {{ $teacher->nip ? '(' . $teacher->nip . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Materi Pembelajaran -->
                        <div class="col-md-6">
                            <label for="material_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-book-reader me-1 text-primary"></i> Materi Pembelajaran
                            </label>
                            <select id="material_id" name="material_id" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Pilih Materi (Opsional)</option>
                                @if($schedule->subject)
                                    @foreach($schedule->subject->materials as $material)
                                        <option value="{{ $material->id }}" {{ old('material_id', $schedule->material_id) == $material->id ? 'selected' : '' }}>
                                            {{ $material->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">Materi yang akan digunakan dalam jadwal ini</small>
                        </div>

                        <!-- Hari -->
                        <div class="col-md-6">
                            <label for="day" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-calendar-day me-1 text-primary"></i> Hari <span
                                    class="text-danger">*</span>
                            </label>
                            <select id="day" name="day" class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                <option value="">Pilih Hari</option>
                                @foreach(['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'] as $key => $day)
                                    <option value="{{ $key }}" {{ old('day', $schedule->day) == $key ? 'selected' : '' }}>
                                        {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Waktu -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-clock me-1 text-primary"></i> Waktu Mulai <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="time" id="start_time" name="start_time"
                                value="{{ old('start_time', $schedule->start_time) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-clock me-1 text-primary"></i> Waktu Selesai <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="time" id="end_time" name="end_time"
                                value="{{ old('end_time', $schedule->end_time) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" required>
                        </div>

                        <!-- Ruangan -->
                        <div class="col-12">
                            <label for="room" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-door-closed me-1 text-primary"></i> Ruangan
                            </label>
                            <input type="text" id="room" name="room" value="{{ old('room', $schedule->room) }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2"
                                placeholder="Contoh: Ruang 101, Lab Komputer">
                            <small class="text-muted">Opsional - nama ruangan atau lokasi</small>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('admin.schedules.index') }}"
                            class="btn btn-outline-secondary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
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

        .form-control,
        .form-select {
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        .btn-outline-secondary:hover {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff);
            transform: translateY(-2px);
        }

        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }
    </style>
@endsection