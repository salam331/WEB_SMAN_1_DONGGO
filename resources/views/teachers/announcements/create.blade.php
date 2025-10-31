@extends('layouts.app')

@section('title', 'Buat Pengumuman - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus text-primary me-2"></i>Buat Pengumuman
                    </h5>
                    <a href="{{ route('teachers.announcements') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('teachers.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" rows="8" class="form-control" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="target_audience" class="form-label">Target Audience</label>
                                    <select name="target_audience" id="target_audience" class="form-control">
                                        <option value="">Pilih Target</option>
                                        <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Semua</option>
                                        <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                                        <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                                        <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>Orang Tua</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Lampiran</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)</small>
                                    @error('attachment')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" id="is_published" {{ old('is_published', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            Terbitkan Pengumuman
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('teachers.announcements') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
