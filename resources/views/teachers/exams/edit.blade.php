@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Ujian</h3>
                    <div class="card-tools">
                        <a href="{{ route('teachers.exams.show', $exam) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('teachers.exams.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('teachers.exams.update', $exam) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Ujian <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $exam->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror"
                                            id="subject_id" name="subject_id" required>
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-control @error('class_id') is-invalid @enderror"
                                            id="class_id" name="class_id" required>
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exam_date">Tanggal Ujian <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('exam_date') is-invalid @enderror"
                                           id="exam_date" name="exam_date" value="{{ old('exam_date', \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d')) }}" required>
                                    @error('exam_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                           id="start_time" name="start_time" value="{{ old('start_time', $exam->start_time) }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration">Durasi (menit) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                           id="duration" name="duration" value="{{ old('duration', $exam->duration) }}" min="1" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_questions">Jumlah Soal</label>
                                    <input type="number" class="form-control @error('total_questions') is-invalid @enderror"
                                           id="total_questions" name="total_questions" value="{{ old('total_questions', $exam->total_questions) }}" min="1">
                                    @error('total_questions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="passing_grade">Nilai Kelulusan</label>
                                    <input type="number" class="form-control @error('passing_grade') is-invalid @enderror"
                                           id="passing_grade" name="passing_grade" value="{{ old('passing_grade', $exam->passing_grade) }}" min="0" max="100">
                                    @error('passing_grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_score">Total Skor <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('total_score') is-invalid @enderror"
                                           id="total_score" name="total_score" value="{{ old('total_score', $exam->total_score) }}" min="1" required>
                                    @error('total_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status', $exam->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $exam->status) == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="instructions">Instruksi</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror"
                                      id="instructions" name="instructions" rows="3">{{ old('instructions', $exam->instructions) }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('teachers.exams.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
