@extends('layouts.app')

@section('page_title', 'Tambah Jadwal Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-calendar-alt me-2"></i> Tambah Jadwal Pelajaran
                </h5>
                <a href="{{ route('admin.schedules.index') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Pesan Error -->
                {{-- @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                        <h6 class="fw-semibold mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i> Terdapat {{ $errors->count() }} kesalahan:
                        </h6>
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <!-- Form Tambah Jadwal Pelajaran -->
                <form action="{{ route('admin.schedules.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-4">
                        <!-- Informasi Utama -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i> Informasi Jadwal
                            </h6>

                            <div class="mb-3">
                                <label for="class_id" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-school me-1 text-primary"></i> Kelas <span class="text-danger">*</span>
                                </label>
                                <select id="class_id" name="class_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                                    required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach(\App\Models\ClassRoom::all() as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="subject_id" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-book me-1 text-primary"></i> Mata Pelajaran <span
                                        class="text-danger">*</span>
                                </label>
                                <select id="subject_id" name="subject_id"
                                    class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach(\App\Models\Subject::all() as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->code }} - {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="teacher_id" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-user-tie me-1 text-primary"></i> Guru <span
                                        class="text-danger">*</span>
                                </label>
                                <select id="teacher_id" name="teacher_id"
                                    class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach(\App\Models\Teacher::with('user')->get() as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }} {{ $teacher->nip ? '(' . $teacher->nip . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="day" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-calendar-day me-1 text-primary"></i> Hari <span
                                        class="text-danger">*</span>
                                </label>
                                <select id="day" name="day" class="form-select border-0 shadow-sm rounded-3 p-2" required>
                                    <option value="">Pilih Hari</option>
                                    @foreach(['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'] as $key => $day)
                                        <option value="{{ $key }}" {{ old('day') == $key ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Detail Waktu dan Ruangan -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-cogs me-2"></i> Waktu & Ruangan
                            </h6>

                            <div class="mb-3">
                                <label for="start_time" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-clock me-1 text-primary"></i> Waktu Mulai <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_time" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-clock me-1 text-primary"></i> Waktu Selesai <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2" required>
                            </div>

                            <div class="mb-3">
                                <label for="room" class="form-label fw-semibold text-secondary">
                                    <i class="fas fa-door-closed me-1 text-primary"></i> Ruangan
                                </label>
                                <input type="text" id="room" name="room" value="{{ old('room') }}"
                                    class="form-control border-0 shadow-sm rounded-3 p-2"
                                    placeholder="Contoh: Ruang 101, Lab Komputer">
                                <div class="form-text">Opsional - nama ruangan atau lokasi.</div>
                            </div>

                            <div class="alert alert-info border-0 shadow-sm rounded-3 small">
                                <i class="fas fa-lightbulb me-1 text-primary"></i> Pastikan semua data sudah benar sebelum
                                menyimpan.
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 d-flex justify-content-end border-top pt-4">
                        <a href="{{ route('admin.schedules.index') }}"
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

        .form-text {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .alert ul {
            margin-bottom: 0;
        }
    </style>
@endsection