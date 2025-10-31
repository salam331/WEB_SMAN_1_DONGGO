@extends('layouts.app')

@section('title', 'Detail Absensi - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        Detail Absensi Siswa
                    </h5>
                    <div>
                        <a href="{{ route('teachers.attendances.edit', [$schedule->id, $date]) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('teachers.attendances') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-calendar-day me-1"></i>Tanggal
                                    </h6>
                                    <h4 class="card-text mb-0">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-book me-1"></i>Mata Pelajaran
                                    </h6>
                                    <h4 class="card-text mb-0">{{ $schedule->subject->name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-users me-1"></i>Kelas
                                    </h6>
                                    <h4 class="card-text mb-0">{{ $schedule->classRoom->name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-user-graduate me-1"></i>Total Siswa
                                    </h6>
                                    <h4 class="card-text mb-0">{{ $attendances->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($attendance->student->user->profile_photo)
                                                <img src="{{ asset('storage/' . $attendance->student->user->profile_photo) }}"
                                                     alt="Foto" class="rounded-circle me-3" width="40" height="40">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $attendance->student->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->student->nis }}</td>
                                    <td>
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Hadir
                                                </span>
                                                @break
                                            @case('late')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Terlambat
                                                </span>
                                                @break
                                            @case('absent')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Tidak Hadir
                                                </span>
                                                @break
                                            @case('excused')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-envelope me-1"></i>Izin
                                                </span>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
