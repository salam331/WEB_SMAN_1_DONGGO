@extends('layouts.app')

@section('page_title', 'Detail Data Kehadiran')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Header -->
        <div class="card-header bg-info text-white d-flex align-items-center justify-content-between rounded-top-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-eye me-2"></i> Detail Data Kehadiran
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn btn-light btn-sm text-info fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('admin.attendances.index') }}" class="btn btn-light btn-sm text-info fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body bg-light bg-gradient p-4">
            <div class="row g-4">
                <!-- Student Information -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-user-graduate me-2"></i> Informasi Siswa
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Nama Siswa</label>
                                    <p class="mb-0 fw-bold text-dark">{{ $attendance->student->user->name }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Kelas</label>
                                    <p class="mb-0">{{ $attendance->student->classRoom->name ?? 'Tidak ada kelas' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Email</label>
                                    <p class="mb-0">{{ $attendance->student->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-calendar-alt me-2"></i> Informasi Jadwal
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Mata Pelajaran</label>
                                    <p class="mb-0 fw-bold text-dark">{{ $attendance->schedule->subject->name }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Guru</label>
                                    <p class="mb-0">{{ $attendance->schedule->teacher->user->name }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Hari & Waktu</label>
                                    <p class="mb-0">{{ ucfirst($attendance->schedule->day) }}, {{ $attendance->schedule->start_time }} - {{ $attendance->schedule->end_time }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Ruangan</label>
                                    <p class="mb-0">{{ $attendance->schedule->room ?? 'Tidak ditentukan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Details -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-clipboard-check me-2"></i> Detail Kehadiran
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary">Tanggal</label>
                                    <p class="mb-0 fw-bold">{{ $attendance->date->format('d F Y') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary">Status</label>
                                    <p class="mb-0">
                                        @php
                                            $statusColors = [
                                                'present' => 'bg-success text-white',
                                                'absent' => 'bg-danger text-white',
                                                'late' => 'bg-warning text-dark',
                                                'excused' => 'bg-info text-dark',
                                                'default' => 'bg-secondary text-white'
                                            ];
                                        @endphp
                                        <span class="badge px-3 py-2 rounded-pill {{ $statusColors[$attendance->status] ?? $statusColors['default'] }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary">Dicatat Oleh</label>
                                    <p class="mb-0">{{ $attendance->recordedBy->name ?? 'Tidak diketahui' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-secondary">Keterangan</label>
                                    <p class="mb-0">{{ $attendance->remark ?? 'Tidak ada keterangan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold">
                            <i class="fas fa-edit me-1"></i> Edit Data
                        </a>
                        <form action="{{ route('admin.attendances.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kehadiran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="fas fa-trash me-1"></i> Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #e8590c, #fd7e14) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #dc3545) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
</div>
@endsection
