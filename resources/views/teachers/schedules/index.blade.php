@extends('layouts.app')

@section('title', 'Jadwal Mengajar - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Jadwal Mengajar
                    </h5>
                </div>
                <div class="card-body">
                    @if($schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Hari</th>
                                        <th>Waktu</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Ruangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $schedule->day }}</span>
                                        </td>
                                        <td>
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </td>
                                        <td>
                                            <strong>{{ $schedule->subject->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $schedule->classRoom->name }}</span>
                                        </td>
                                        <td>
                                            {{ $schedule->classRoom->room ?? 'Belum ditentukan' }}
                                        </td>
                                        <td>
                                            @if($schedule->day == now()->format('l'))
                                                <span class="badge bg-warning text-dark">Hari Ini</span>
                                            @else
                                                <span class="badge bg-secondary">Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Kalender Mingguan -->
                        <div class="mt-4">
                            <h6 class="mb-3">
                                <i class="fas fa-calendar-week text-primary me-2"></i>
                                Kalender Mingguan
                            </h6>
                            <div class="row g-2">
                                @php
                                    $days = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
                                @endphp
                                @foreach($days as $english => $indonesian)
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $indonesian }}</h6>
                                            @if($english == now()->format('l'))
                                                <span class="badge bg-primary">Hari Ini</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $daySchedules = $schedules->where('day', $english);
                                            @endphp
                                            @if($daySchedules->count() > 0)
                                                <div class="row g-2">
                                                    @foreach($daySchedules as $schedule)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="border rounded p-3 bg-white">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <strong class="text-primary">{{ $schedule->subject->name }}</strong>
                                                                <small class="text-muted">{{ $schedule->start_time }} - {{ $schedule->end_time }}</small>
                                                            </div>
                                                            <div class="mb-2">
                                                                <i class="fas fa-school text-success me-1"></i>
                                                                {{ $schedule->classRoom->name }}
                                                                @if($schedule->classRoom->room)
                                                                    <small class="text-muted">(Ruang {{ $schedule->classRoom->room }})</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-3 text-muted">
                                                    <i class="fas fa-calendar-times me-1"></i>
                                                    Tidak ada jadwal
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Belum Ada Jadwal Mengajar</h5>
                            <p class="text-muted">Jadwal mengajar Anda belum ditentukan oleh administrator.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
