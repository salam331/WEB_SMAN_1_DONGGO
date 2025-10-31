@extends('layouts.app')

@section('title', 'Edit Absensi - SMAN 1 DONGGO')

@section('content')

    <style>
        /* ====== GAYA SELARAS HALAMAN DETAIL KELAS ====== */
        .card-custom {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: #fff !important;
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
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
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

        .table-hover tbody tr:hover {
            background-color: #f0f8ff;
            transition: 0.3s ease;
        }
    </style>

    <div class="container-fluid fade-in">
        <div class="row">
            <div class="col-12">
                <div class="card card-custom border-0">
                    <div class="card-header card-header-gradient d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold text-white">
                                <i class="fas fa-edit me-2"></i>Edit Absensi Siswa
                            </h5>
                            <small class="text-white-50">Perbarui data kehadiran siswa pada tanggal ini</small>
                        </div>
                        <a href="{{ route('teachers.attendances.show', [$schedule->id, $date]) }}"
                            class="btn btn-light text-primary fw-semibold btn-soft">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Jadwal -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h6>
                                    <small class="text-muted">Tanggal</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-book fa-2x text-success mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ $schedule->subject->name }}</h6>
                                    <small class="text-muted">Mata Pelajaran</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-users fa-2x text-warning mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ $schedule->classRoom->name }}</h6>
                                    <small class="text-muted">Kelas</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card p-3 text-center">
                                    <i class="fas fa-user-graduate fa-2x text-info mb-2"></i>
                                    <h6 class="fw-bold mb-1">{{ $attendances->count() }}</h6>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Edit Absensi -->
                        <form action="{{ route('teachers.attendances.update', [$schedule->id, $date]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card card-custom mt-3">
                                <div class="card-header bg-light py-2 border-bottom">
                                    <h6 class="mb-0 fw-semibold text-primary">
                                        <i class="fas fa-list-check me-2"></i>Daftar Kehadiran Siswa
                                    </h6>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>NIS</th>
                                                    <th>Status</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($attendances as $attendance)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($attendance->student->user->profile_photo)
                                                                    <img src="{{ asset('storage/' . $attendance->student->user->profile_photo) }}"
                                                                        alt="Foto" class="rounded-circle me-3" width="40"
                                                                        height="40">
                                                                @else
                                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                        style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-user"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-semibold">
                                                                        {{ $attendance->student->user->name }}</div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden"
                                                                name="students[{{ $attendance->student->id }}][student_id]"
                                                                value="{{ $attendance->student->id }}">
                                                        </td>
                                                        <td>{{ $attendance->student->nis }}</td>
                                                        <td>
                                                            <select class="form-select form-select-sm"
                                                                name="students[{{ $attendance->student->id }}][status]"
                                                                required>
                                                                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Hadir</option>
                                                                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Terlambat</option>
                                                                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                                                                <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>Izin</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="students[{{ $attendance->student->id }}][remark]"
                                                                value="{{ $attendance->remark }}" placeholder="Opsional">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('teachers.attendances.show', [$schedule->id, $date]) }}"
                                    class="btn btn-secondary btn-soft">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-soft">
                                    <i class="fas fa-save me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection