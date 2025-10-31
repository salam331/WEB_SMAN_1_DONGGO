@extends('layouts.app')

@section('title', 'Detail Kelas - SMAN 1 DONGGO')

@section('content')
    <style>
        /* ====== STYLE GLOBAL SELARAS DENGAN HALAMAN INDEX ====== */
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
            background-color: #fff;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: white !important;
            border-radius: 15px 15px 0 0;
        }

        .btn-soft {
            border-radius: 10px;
            transition: all 0.25s ease;
            font-weight: 500;
        }

        .btn-soft:hover {
            transform: scale(1.05);
        }

        .info-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
        }

        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .badge {
            border-radius: 8px;
            font-weight: 500;
            padding: 6px 10px;
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-in-out both;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(15px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="container-fluid fade-in">
        <div class="row">
            <div class="col-12">
                <div class="card card-custom border-0">
                    <div class="card-header d-flex justify-content-between align-items-center semi-gradient bg-primary">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold text-white">
                                <i class="fas fa-user-check me-2"></i>Detail Kelas: {{ $class->name }}
                            </h5>
                            <small class="text-white">Daftar Siswa dan Informasi Kelas</small>
                        </div>
                        <div>
                            <button onclick="window.print()" class="btn btn-success text-white fw-semibold">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                            <a href="{{ route('teachers.classes') }}" class="btn btn-light text-primary fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Kelas -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ $class->students->count() }}</h6>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                                    <h6 class="fw-bold mb-1">
                                        {{ $class->homeroomTeacher ? $class->homeroomTeacher->user->name : 'Belum ada' }}
                                    </h6>
                                    <small class="text-muted">Wali Kelas</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-book fa-2x text-warning mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ $class->subjectTeachers->count() }}</h6>
                                    <small class="text-muted">Mata Pelajaran</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-map-marker-alt fa-2x text-info mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ $class->room ?? 'Belum ditentukan' }}</h6>
                                    <small class="text-muted">Ruangan</small>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Siswa -->
                        <div class="card card-custom mt-3">
                            <div class="card-header card-header-gradient py-2">
                                <h6 class="mb-0 fw-semibold"><i class="fas fa-list me-2"></i>Daftar Siswa</h6>
                            </div>
                            <div class="card-body">
                                @if($class->students->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover">
                                            <thead>
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
                                                                        alt="Foto" class="rounded-circle me-2" width="36" height="36">
                                                                @else
                                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                                        style="width: 36px; height: 36px;">
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
                                                            <span
                                                                class="badge bg-{{ $student->gender === 'L' ? 'primary' : 'danger' }}">
                                                                {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {{ $student->parent ? $student->parent->user->name : '-' }}
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('teachers.attendances') }}?student_id={{ $student->id }}"
                                                                    class="btn btn-outline-success btn-sm btn-soft"
                                                                    title="Lihat Absensi">
                                                                    <i class="fas fa-calendar-check"></i>
                                                                </a>
                                                                <a href="{{ route('teachers.grades') }}?student_id={{ $student->id }}"
                                                                    class="btn btn-outline-primary btn-sm btn-soft"
                                                                    title="Lihat Nilai">
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

                        <!-- Mata Pelajaran -->
                        <div class="card card-custom mt-4">
                            <div class="card-header bg-success text-white py-2 rounded-top">
                                <h6 class="mb-0 fw-semibold"><i class="fas fa-book me-2"></i>Mata Pelajaran di Kelas Ini
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($class->subjectTeachers->count() > 0)
                                    <div class="row g-3">
                                        @foreach($class->subjectTeachers as $subjectTeacher)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="info-card p-3 h-100">
                                                    <h6 class="fw-semibold text-primary">{{ $subjectTeacher->subject->name }}</h6>
                                                    <p class="mb-2">
                                                        <small class="text-muted">Guru:
                                                            {{ $subjectTeacher->teacher->user->name }}</small>
                                                    </p>
                                                    @if($subjectTeacher->teacher_id == auth()->user()->teacher->id)
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ route('teachers.exams.index') }}?subject_id={{ $subjectTeacher->subject_id }}&class_id={{ $class->id }}"
                                                                class="btn btn-primary btn-sm btn-soft">
                                                                <i class="fas fa-file-alt me-1"></i>Ujian
                                                            </a>
                                                            <a href="{{ route('teachers.grades') }}?subject_id={{ $subjectTeacher->subject_id }}&class_id={{ $class->id }}"
                                                                class="btn btn-success btn-sm btn-soft">
                                                                <i class="fas fa-graduation-cap me-1"></i>Nilai
                                                            </a>
                                                        </div>
                                                    @endif
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