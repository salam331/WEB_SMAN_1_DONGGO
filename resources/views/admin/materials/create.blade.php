@extends('layouts.app')

@section('page_title', 'Tambah Materi')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-plus me-2"></i> Tambah Materi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilih Mata Pelajaran --}}
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id"
                            class="form-select @error('subject_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
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

                    {{-- Pilih Kelas --}}
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Kelas</label>
                        <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($class_rooms as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Judul --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Materi</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- File --}}
                    <div class="mb-3">
                        <label for="file_path" class="form-label">File (PDF, DOC, XLS, JPG, PNG)</label>
                        <input type="file" name="file_path" id="file_path"
                            class="form-control @error('file_path') is-invalid @enderror">
                        @error('file_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Publikasi --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published" class="form-check-label">Terbitkan Materi</label>
                    </div>

                    {{-- Pilih Guru (hanya untuk admin) --}}
                    @if(auth()->user()->hasRole('admin'))
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Guru</label>
                            <select name="teacher_id" id="teacher_id"
                                class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name ?? 'Nama tidak tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Simpan</button>
                    <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary"><i
                            class="fas fa-arrow-left me-2"></i> Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection