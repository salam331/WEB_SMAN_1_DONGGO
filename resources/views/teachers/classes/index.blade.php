@extends('layouts.app')

@section('title', 'Kelola Kelas - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-school text-primary me-2"></i>
                        Kelas yang Anda Ajar
                    </h5>
                </div>
                <div class="card-body">
                    @if($classes->count() > 0)
                        <div class="row g-4">
                            @foreach($classes as $class)
                            <div class="col-lg-6 col-xl-4">
                                <div class="card h-100 border">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0 d-flex align-items-center">
                                            <i class="fas fa-chalkboard me-2 text-primary"></i>
                                            {{ $class->name }}
                                            @if($class->homeroomTeacher)
                                                <small class="text-muted ms-auto">
                                                    (Wali: {{ $class->homeroomTeacher->user->name }})
                                                </small>
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3 mb-3">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-primary mb-0">{{ $class->students->count() }}</div>
                                                    <small class="text-muted">Siswa</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-success mb-0">
                                                        {{ $class->subjectTeachers->where('teacher_id', auth()->user()->teacher->id)->count() }}
                                                    </div>
                                                    <small class="text-muted">Mapel</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted fw-semibold">Mata Pelajaran yang Anda Ajar:</small>
                                            <div class="mt-2">
                                                @foreach($class->subjectTeachers->where('teacher_id', auth()->user()->teacher->id) as $subjectTeacher)
                                                    <span class="badge bg-primary me-1 mb-1">{{ $subjectTeacher->subject->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('teachers.classes.detail', $class->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-users me-1"></i>Lihat Detail Siswa
                                            </a>
                                            <a href="{{ route('teachers.attendances') }}?class_id={{ $class->id }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-calendar-check me-1"></i>Input Absensi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-school fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Belum Ada Kelas yang Ditugaskan</h5>
                            <p class="text-muted">Anda belum ditugaskan mengajar di kelas manapun.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
