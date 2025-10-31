@extends('layouts.app')

@section('title', 'Detail Absensi - SMAN 1 DONGGO')

@section('content')
<style>
    /* ===== Tampilan Kartu Utama ===== */
    .card {
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
    }

    .card-header {
        border-bottom: none;
        background: linear-gradient(90deg, #007bff, #0056d2);
        color: white;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .card-title i {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    /* ===== Subcard Informasi ===== */
    .info-card {
        border: none;
        border-radius: 0.75rem;
        background: #f9fbfd;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        background: #f0f7ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.1);
    }

    .info-card h4 {
        color: #0d6efd;
        font-weight: 600;
    }

    /* ===== Tabel ===== */
    .table {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .table thead {
        background: linear-gradient(90deg, #f8f9fa, #e9ecef);
        color: #343a40;
    }

    .table-hover tbody tr {
        transition: all 0.25s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.01);
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.45em 0.65em;
        border-radius: 0.5rem;
    }

    /* ===== Tombol ===== */
    .btn {
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
    }

    /* ===== Animasi Masuk ===== */
    .fade-in {
        animation: fadeIn 0.8s ease both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid fade-in">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-calendar-check me-2"></i> Detail Absensi Siswa
                    </h5>
                    <div>
                        <a href="{{ route('teachers.attendances.edit', [$schedule->id, $date]) }}" class="btn btn-warning text-white fw-semibold me-2">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('teachers.attendances') }}" class="btn btn-light text-primary fw-semibold">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <!-- Informasi Jadwal -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card info-card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2"><i class="fas fa-calendar-day me-1"></i> Tanggal</h6>
                                    <h4>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card info-card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2"><i class="fas fa-book me-1"></i> Mata Pelajaran</h6>
                                    <h4>{{ $schedule->subject->name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card info-card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2"><i class="fas fa-users me-1"></i> Kelas</h6>
                                    <h4>{{ $schedule->classRoom->name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card info-card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2"><i class="fas fa-user-graduate me-1"></i> Total Siswa</h6>
                                    <h4>{{ $attendances->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Absensi -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>NIS</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Dicatat Oleh</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td class="text-start">
                                        <div class="d-flex align-items-center">
                                            @if($attendance->student->user->profile_photo)
                                                <img src="{{ asset('storage/' . $attendance->student->user->profile_photo) }}"
                                                     alt="Foto" class="rounded-circle me-3 shadow-sm" width="42" height="42">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $attendance->student->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->student->nis }}</td>
                                    <td>
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Hadir</span>
                                                @break
                                            @case('late')
                                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Terlambat</span>
                                                @break
                                            @case('absent')
                                                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Tidak Hadir</span>
                                                @break
                                            @case('excused')
                                                <span class="badge bg-info"><i class="fas fa-envelope me-1"></i> Izin</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $attendance->remark ?? '-' }}</td>
                                    <td>{{ $attendance->recordedBy->name }}</td>
                                    <td>{{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- card-body -->
            </div>
        </div>
    </div>
</div>
@endsection
