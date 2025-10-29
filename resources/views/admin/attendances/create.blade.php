@extends('layouts.app')

@section('page_title', 'Tambah Data Kehadiran')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Header -->
        <div class="card-header bg-success text-white d-flex align-items-center justify-content-between rounded-top-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-plus me-2"></i> Tambah Data Kehadiran
            </h5>
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-light btn-sm text-success fw-semibold rounded-pill shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Body -->
        <div class="card-body bg-light bg-gradient p-4">
            <form action="{{ route('admin.attendances.store') }}" method="POST" class="row g-4">
                @csrf

                <!-- Student Selection -->
                <div class="col-md-6">
                    <label for="student_id" class="form-label fw-semibold text-secondary">
                        <i class="fas fa-user-graduate me-1"></i> Siswa <span class="text-danger">*</span>
                    </label>
                    <select name="student_id" id="student_id" class="form-select shadow-sm rounded-pill border-0 @error('student_id') is-invalid @enderror" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->user->name }} - {{ $student->classRoom->name ?? 'Tidak ada kelas' }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Schedule Selection -->
                <div class="col-md-6">
                    <label for="schedule_id" class="form-label fw-semibold text-secondary">
                        <i class="fas fa-calendar-alt me-1"></i> Jadwal Pelajaran <span class="text-danger">*</span>
                    </label>
                    <select name="schedule_id" id="schedule_id" class="form-select shadow-sm rounded-pill border-0 @error('schedule_id') is-invalid @enderror" required>
                        <option value="">Pilih Jadwal</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->subject->name }} - {{ $schedule->classRoom->name }} - {{ $schedule->teacher->user->name }} ({{ $schedule->day }} {{ $schedule->start_time }})
                            </option>
                        @endforeach
                    </select>
                    @error('schedule_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date -->
                <div class="col-md-6">
                    <label for="date" class="form-label fw-semibold text-secondary">
                        <i class="fas fa-calendar-day me-1"></i> Tanggal <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control shadow-sm rounded-pill border-0 @error('date') is-invalid @enderror" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold text-secondary">
                        <i class="fas fa-check-circle me-1"></i> Status Kehadiran <span class="text-danger">*</span>
                    </label>
                    <select name="status" id="status" class="form-select shadow-sm rounded-pill border-0 @error('status') is-invalid @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="excused" {{ old('status') == 'excused' ? 'selected' : '' }}>Izin</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remark -->
                <div class="col-12">
                    <label for="remark" class="form-label fw-semibold text-secondary">
                        <i class="fas fa-comment me-1"></i> Keterangan
                    </label>
                    <textarea name="remark" id="remark" rows="3" class="form-control shadow-sm rounded-3 border-0 @error('remark') is-invalid @enderror" placeholder="Tambahkan keterangan jika diperlukan...">{{ old('remark') }}</textarea>
                    @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary rounded-pill px-4 shadow-sm fw-semibold">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 🎨 Style Tambahan -->
    <style>
        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .form-select:focus, .form-control:focus {
            border-color: #198754 !important;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15) !important;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #157347, #198754) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }
    </style>
</div>
@endsection
