@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')
    <div class="container py-12">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <!-- Header Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-pen-alt me-2"></i> Edit Ujian
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teachers.exams.show', $exam) }}" class="btn btn-info btn-sm shadow-sm">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </a>
                            <a href="{{ route('teachers.exams.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="card-body bg-light p-5">
                        <form action="{{ route('teachers.exams.update', $exam) }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-4">

                                <!-- Nama Ujian -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="name">
                                        <i class="fas fa-book-open me-2 text-primary"></i>Nama Ujian <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $exam->name) }}"
                                        class="form-control shadow-sm @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mata Pelajaran -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="subject_id">
                                        <i class="fas fa-book me-2 text-primary"></i>Mata Pelajaran <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select id="subject_id" name="subject_id"
                                        class="form-select shadow-sm @error('subject_id') is-invalid @enderror" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                    <label class="form-label fw-semibold" for="class_id">
                                        <i class="fas fa-users me-2 text-primary"></i>Kelas <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select id="class_id" name="class_id"
                                        class="form-select shadow-sm @error('class_id') is-invalid @enderror" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $exam->class_id) == $class->id ? 'selected' : '' }}>
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
                                    <label class="form-label fw-semibold" for="exam_date">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Tanggal Ujian <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="exam_date" name="exam_date"
                                        value="{{ old('exam_date', \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d')) }}"
                                        class="form-control shadow-sm @error('exam_date') is-invalid @enderror" required>
                                    @error('exam_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Waktu & Durasi -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="start_time">
                                        <i class="fas fa-clock me-2 text-primary"></i>Waktu Mulai <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="time" id="start_time" name="start_time"
                                        value="{{ old('start_time', $exam->start_time) }}"
                                        class="form-control shadow-sm @error('start_time') is-invalid @enderror" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="duration">
                                        <i class="fas fa-hourglass-half me-2 text-primary"></i>Durasi (menit) <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="duration" name="duration" min="1"
                                        value="{{ old('duration', $exam->duration) }}"
                                        class="form-control shadow-sm @error('duration') is-invalid @enderror" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Jumlah Soal & Nilai Kelulusan -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="total_questions">
                                        <i class="fas fa-list-ol me-2 text-primary"></i>Jumlah Soal
                                    </label>
                                    <input type="number" id="total_questions" name="total_questions" min="1"
                                        value="{{ old('total_questions', $exam->total_questions) }}"
                                        class="form-control shadow-sm @error('total_questions') is-invalid @enderror">
                                    @error('total_questions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="passing_grade">
                                        <i class="fas fa-percentage me-2 text-primary"></i>Nilai Kelulusan
                                    </label>
                                    <input type="number" id="passing_grade" name="passing_grade" min="0" max="100"
                                        value="{{ old('passing_grade', $exam->passing_grade) }}"
                                        class="form-control shadow-sm @error('passing_grade') is-invalid @enderror">
                                    @error('passing_grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Total Skor & Status -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="total_score">
                                        <i class="fas fa-star me-2 text-primary"></i>Total Skor <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="total_score" name="total_score" min="1"
                                        value="{{ old('total_score', $exam->total_score) }}"
                                        class="form-control shadow-sm @error('total_score') is-invalid @enderror" required>
                                    @error('total_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="status">
                                        <i class="fas fa-toggle-on me-2 text-primary"></i>Status <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select id="status" name="status"
                                        class="form-select shadow-sm @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', $exam->status) == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="published" {{ old('status', $exam->status) == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="description">
                                        <i class="fas fa-align-left me-2 text-primary"></i>Deskripsi
                                    </label>
                                    <textarea id="description" name="description" rows="3"
                                        class="form-control shadow-sm @error('description') is-invalid @enderror">{{ old('description', $exam->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Instruksi -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="instructions">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>Instruksi
                                    </label>
                                    <textarea id="instructions" name="instructions" rows="3"
                                        class="form-control shadow-sm @error('instructions') is-invalid @enderror">{{ old('instructions', $exam->instructions) }}</textarea>
                                    @error('instructions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-5 text-end">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('teachers.exams.index') }}"
                                    class="btn btn-outline-secondary px-4 shadow-sm">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection