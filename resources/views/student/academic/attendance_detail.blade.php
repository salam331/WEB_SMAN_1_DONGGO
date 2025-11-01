@extends('layouts.app')

@section('title', 'Detail Absensi - ' . $schedule->subject->name . ' - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4 shadow-sm">
                    <div>
                        <h4 class="card-title mb-1 fw-bold">
                            <i class="fas fa-calendar-check me-2"></i> Detail Absensi
                        </h4>
                        <small class="text-bold fw-medium">
                            {{ $schedule->subject->name }} &mdash; {{ $schedule->teacher->user->name }}
                        </small>
                    </div>
                    <a href="{{ route('student.attendance') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Body -->
                <div class="card-body p-4 bg-light">
                    <!-- Informasi dan Ringkasan -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-white shadow-sm rounded-4">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3 fw-bold">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Mata Pelajaran
                                    </h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><strong>Mata Pelajaran:</strong> {{ $schedule->subject->name }}</li>
                                        <li class="mb-2"><strong>Guru:</strong> {{ $schedule->teacher->user->name }}</li>
                                        <li class="mb-2"><strong>Hari:</strong> 
                                            <span class="badge bg-info-subtle text-dark px-3 py-2 rounded-pill shadow-sm">
                                                {{ ucfirst($schedule->day) }}
                                            </span>
                                        </li>
                                        <li><strong>Waktu:</strong> 
                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 bg-white shadow-sm rounded-4">
                                <div class="card-body">
                                    <h6 class="card-title text-success mb-3 fw-bold">
                                        <i class="fas fa-chart-pie me-2"></i>Ringkasan Kehadiran
                                    </h6>
                                    @php
                                        $total = $attendances->count();
                                        $present = $attendances->where('status', 'present')->count();
                                        $absent = $attendances->where('status', 'absent')->count();
                                        $late = $attendances->where('status', 'late')->count();
                                        $sick = $attendances->where('status', 'sick')->count();
                                        $permission = $attendances->where('status', 'permission')->count();
                                        $presentPercentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                                    @endphp

                                    <div class="row text-center g-3">
                                        <div class="col-4">
                                            <div class="fw-bold text-success fs-5">{{ $present }}</div>
                                            <small class="text-muted">Hadir</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-warning fs-5">{{ $late }}</div>
                                            <small class="text-muted">Terlambat</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-info fs-5">{{ $sick }}</div>
                                            <small class="text-muted">Sakit</small>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <div class="fw-bold text-secondary fs-5">{{ $permission }}</div>
                                            <small class="text-muted">Izin</small>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <div class="fw-bold text-danger fs-5">{{ $absent }}</div>
                                            <small class="text-muted">Tidak Hadir</small>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <div class="text-center">
                                        <div class="display-6 fw-bold text-primary">{{ $presentPercentage }}%</div>
                                        <small class="text-muted">Persentase Kehadiran</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Detail Kehadiran -->
                    @if($attendances->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
                            <table class="table table-bordered align-middle table-hover mb-0">
                                <thead class="bg-primary text-white text-center">
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th style="width:20%">Tanggal</th>
                                        <th style="width:20%">Status</th>
                                        <th style="width:30%">Catatan</th>
                                        <th style="width:25%">Waktu Absen</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($attendances as $i => $attendance)
                                        <tr class="hover-row">
                                            <td class="text-center fw-semibold">{{ $attendances->firstItem() + $i }}</td>
                                            <td class="text-center">
                                                <i class="fas fa-calendar-day text-primary me-1"></i>
                                                {{ $attendance->date->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                @switch($attendance->status)
                                                    @case('present')
                                                        <span class="badge bg-success-subtle text-success fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-check-circle me-1"></i> Hadir
                                                        </span>
                                                        @break
                                                    @case('absent')
                                                        <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-times-circle me-1"></i> Tidak Hadir
                                                        </span>
                                                        @break
                                                    @case('late')
                                                        <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-clock me-1"></i> Terlambat
                                                        </span>
                                                        @break
                                                    @case('sick')
                                                        <span class="badge bg-info-subtle text-info fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-thermometer-half me-1"></i> Sakit
                                                        </span>
                                                        @break
                                                    @case('permission')
                                                        <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-envelope me-1"></i> Izin
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-question-circle me-1"></i> {{ ucfirst($attendance->status) }}
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td>{{ $attendance->notes ?? '-' }}</td>
                                            <td class="text-center">
                                                <i class="fas fa-clock text-muted me-1"></i>
                                                {{ $attendance->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $attendances->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada data absensi</h5>
                            <p class="text-muted">Riwayat absensi akan muncul di sini setelah guru menginput data kehadiran Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #4f7df3);
    }
    .hover-row:hover {
        background-color: #f1f6ff !important;
        transition: background-color 0.3s ease;
    }
    .badge {
        font-weight: 600;
    }
    .bg-success-subtle { background-color: #e8f6ef !important; }
    .bg-danger-subtle { background-color: #fdeaea !important; }
    .bg-warning-subtle { background-color: #fff7e6 !important; }
    .bg-info-subtle { background-color: #e8f4fb !important; }
    .bg-secondary-subtle { background-color: #f0f0f5 !important; }
</style>
@endsection
