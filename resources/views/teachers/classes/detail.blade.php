@extends('layouts.app')

@section('title', 'Detail Kelas - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users text-primary me-2"></i>
                            Detail Kelas: {{ $class->name }}
                        </h5>
                        <small class="text-muted">Daftar Siswa dan Informasi Kelas</small>
                    </div>
                    <div>
                        <button onclick="window.print()" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                        <a href="{{ route('teachers.classes') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Info Kelas -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-1">{{ $class->students->count() }}</h6>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                                    <h6 class="mb-1">{{ $class->homeroomTeacher ? $class->homeroomTeacher->user->name : 'Belum ada' }}</h6>
                                    <small class="text-muted">Wali Kelas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x text-warning mb-2"></i>
                                    <h6 class="mb-1">{{ $class->subjectTeachers->count() }}</h6>
                                    <small class="text-muted">Mata Pelajaran</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-map-marker-alt fa-2x text-info mb-2"></i>
                                    <h6 class="mb-1">{{ $class->room ?? 'Belum ditentukan' }}</h6>
                                    <small class="text-muted">Ruangan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Siswa -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                Daftar Siswa
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($class->students->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>NIS</th>
                                                <th>Nama Lengkap</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Orang Tua</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($class->students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student->nis }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($student->user && $student->user->profile_photo)
                                                            <img src="{{ asset('storage/' . $student->user->profile_photo) }}"
                                                                 alt="Foto" class="rounded-circle me-2" width="32" height="32">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                                <i class="fas fa-user fa-sm"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ $student->user->name }}</div>
                                                            <small class="text-muted">{{ $student->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $student->gender === 'L' ? 'primary' : 'danger' }}">
                                                        {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($student->parent)
                                                        {{ $student->parent->user->name }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('teachers.attendances') }}?student_id={{ $student->id }}"
                                                           class="btn btn-outline-success btn-sm" title="Lihat Absensi">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </a>
                                                        <a href="{{ route('teachers.grades') }}?student_id={{ $student->id }}"
                                                           class="btn btn-outline-primary btn-sm" title="Lihat Nilai">
                                                            <i class="fas fa-graduation-cap"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada siswa terdaftar di kelas ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mata Pelajaran di Kelas Ini -->
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-book me-2"></i>
                                Mata Pelajaran di Kelas Ini
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($class->subjectTeachers->count() > 0)
                                <div class="row g-3">
                                    @foreach($class->subjectTeachers as $subjectTeacher)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $subjectTeacher->subject->name }}</h6>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        Guru: {{ $subjectTeacher->teacher->user->name }}
                                                    </small>
                                                </p>
                                                @if($subjectTeacher->teacher_id == auth()->user()->teacher->id)
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('teachers.exams.index') }}?subject_id={{ $subjectTeacher->subject_id }}&class_id={{ $class->id }}"
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-file-alt me-1"></i>Ujian
                                                        </a>
                                                        <a href="{{ route('teachers.grades') }}?subject_id={{ $subjectTeacher->subject_id }}&class_id={{ $class->id }}"
                                                           class="btn btn-success btn-sm">
                                                            <i class="fas fa-graduation-cap me-1"></i>Nilai
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <p class="text-muted">Belum ada mata pelajaran yang ditugaskan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
