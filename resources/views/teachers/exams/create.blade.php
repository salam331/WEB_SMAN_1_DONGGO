@extends('layouts.app')

@section('title', 'Buat Ujian Baru - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus text-primary me-2"></i>
                            Buat Ujian Baru
                        </h5>
                        <small class="text-muted">Buat ujian untuk siswa</small>
                    </div>
                    <a href="{{ route('teachers.exams.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('teachers.exams.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Judul Ujian -->
                            <div class="col-12">
                                <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mata Pelajaran -->
                            <div class="col-md-6">
                                <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject_id') is-invalid @enderror"
                                        id="subject_id" name="subject_id" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div class="col-md-6">
                                <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror"
                                        id="class_id" name="class_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Ujian -->
                            <div class="col-md-6">
                                <label for="exam_date" class="form-label">Tanggal Ujian <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('exam_date') is-invalid @enderror"
                                       id="exam_date" name="exam_date" value="{{ old('exam_date') }}" required>
                                @error('exam_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Waktu Mulai -->
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                       id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Durasi -->
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                       id="duration" name="duration" value="{{ old('duration', 90) }}" min="1" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Total Soal -->
                            <div class="col-md-6">
                                <label for="total_questions" class="form-label">Total Soal</label>
                                <input type="number" class="form-control @error('total_questions') is-invalid @enderror"
                                       id="total_questions" name="total_questions" value="{{ old('total_questions') }}" min="1">
                                @error('total_questions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Passing Grade -->
                            <div class="col-md-6">
                                <label for="passing_grade" class="form-label">Nilai Kelulusan (%)</label>
                                <input type="number" class="form-control @error('passing_grade') is-invalid @enderror"
                                       id="passing_grade" name="passing_grade" value="{{ old('passing_grade', 70) }}" min="0" max="100">
                                @error('passing_grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Instruksi -->
                            <div class="col-12">
                                <label for="instructions" class="form-label">Instruksi Ujian</label>
                                <textarea class="form-control @error('instructions') is-invalid @enderror"
                                          id="instructions" name="instructions" rows="4">{{ old('instructions') }}</textarea>
                                @error('instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('teachers.exams.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Ujian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const examDateInput = document.getElementById('exam_date');
    const today = new Date().toISOString().split('T')[0];
    examDateInput.setAttribute('min', today);

    // Auto-calculate end time based on start time and duration
    const startTimeInput = document.getElementById('start_time');
    const durationInput = document.getElementById('duration');

    function updateEndTime() {
        const startTime = startTimeInput.value;
        const duration = parseInt(durationInput.value) || 0;

        if (startTime && duration > 0) {
            const [hours, minutes] = startTime.split(':').map(Number);
            const startDate = new Date();
            startDate.setHours(hours, minutes, 0, 0);

            const endDate = new Date(startDate.getTime() + duration * 60000);
            const endTime = endDate.toTimeString().slice(0, 5);

            // You can add an end time display if needed
            console.log('End time would be:', endTime);
        }
    }

    startTimeInput.addEventListener('change', updateEndTime);
    durationInput.addEventListener('change', updateEndTime);
});
</script>
@endpush
@endsection
