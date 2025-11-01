@extends('layouts.app')

@section('title', 'Jadwal Pelajaran - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
            <div class="card border-0 shadow-lg rounded-4 animate-card overflow-hidden">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelajaran
                    </h5>
                    <small class="fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</small>
                </div>

                <div class="card-body p-4 bg-light-subtle">
                    <h6 class="fw-semibold text-secondary mb-4">
                        <i class="fas fa-clock text-primary me-2"></i> Jadwal Harian
                    </h6>

                    @if($schedules->count() > 0)
                        <div class="table-responsive shadow-sm rounded-3 overflow-hidden mb-5 bg-primary-subtle">
    <table class="table table-bordered align-middle table-hover mb-0 rounded-3">
        <thead class="bg-primary text-white text-center">
            <tr>
                <th style="width: 15%;">Hari</th>
                <th style="width: 20%;">Waktu</th>
                <th style="width: 40%;">Mata Pelajaran</th>
                <th style="width: 25%;">Guru</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($schedules as $schedule)
                <tr class="hover-row">
                    <td class="text-center align-middle">
                        <span class="badge bg-primary shadow-sm px-3 py-2 rounded-pill">
                            @switch($schedule->day)
                                @case('Monday') Senin @break
                                @case('Tuesday') Selasa @break
                                @case('Wednesday') Rabu @break
                                @case('Thursday') Kamis @break
                                @case('Friday') Jumat @break
                                @case('Saturday') Sabtu @break
                                @case('Sunday') Minggu @break
                            @endswitch
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <i class="fas fa-clock text-muted me-1"></i>
                        <span class="fw-semibold">
                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                        </span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-book-open text-primary"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $schedule->subject->name ?? '-' }}</span>
                                @if(!empty($schedule->subject->description))
                                    <small class="text-muted">{{ Str::limit($schedule->subject->description, 40) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <i class="fas fa-user text-secondary me-1"></i>
                        {{ $schedule->teacher->user->name ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 text-center text-muted small">
        <p class="mb-1"><i class="fas fa-coffee me-1"></i> Istirahat Senin - Kamis: <strong>11.10 - 12.00</strong></p>
        <p><i class="fas fa-pray me-1"></i> Istirahat Jumat: <strong>11.10 - 12.30</strong></p>
    </div>
</div>


                        <!-- Jadwal Mingguan -->
                        <div class="mt-5">
                            <h6 class="fw-semibold text-secondary mb-3">
                                <i class="fas fa-calendar-week text-primary me-2"></i> Jadwal Mingguan
                            </h6>

                            <div class="row g-4">
                                @php
                                    $days = [
                                        'Monday' => 'Senin',
                                        'Tuesday' => 'Selasa',
                                        'Wednesday' => 'Rabu',
                                        'Thursday' => 'Kamis',
                                        'Friday' => 'Jumat',
                                        'Saturday' => 'Sabtu',
                                        'Sunday' => 'Minggu'
                                    ];
                                @endphp

                                @foreach($days as $english => $indonesian)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card day-card border-0 shadow-sm rounded-4 overflow-hidden">
                                            <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 
                                                @if($english == now()->format('l')) bg-primary text-white @else bg-softblue text-secondary @endif">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="fas fa-calendar-day me-2"></i>{{ $indonesian }}
                                                </h6>
                                                @if($english == now()->format('l'))
                                                    <span class="badge bg-light text-primary shadow-sm">Hari Ini</span>
                                                @endif
                                            </div>
                                            <div class="card-body bg-white">
                                                @php
                                                    $daySchedules = $schedules->where('day', $english);
                                                @endphp

                                                @if($daySchedules->count() > 0)
                                                    @foreach($daySchedules as $schedule)
                                                        <div class="p-3 mb-3 border rounded-3 hover-schedule">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <strong class="text-primary">{{ $schedule->subject->name }}</strong>
                                                                <small class="text-muted text-end">
                                                                    <i class="fas fa-clock me-1"></i>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                                                </small>
                                                            </div>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-school text-success me-1"></i>{{ $schedule->classRoom->name }}
                                                                @if($schedule->classRoom->room)
                                                                    <small>(Ruang {{ $schedule->classRoom->room }})</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center text-muted py-3 small">
                                                        <i class="fas fa-calendar-times me-1"></i> Tidak ada jadwal
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
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted fw-semibold">Belum ada jadwal pelajaran</h5>
                            <p class="text-muted small">Jadwal akan muncul di sini setelah admin mengaturnya.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth fade-in animation */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hover-row {
        transition: all 0.25s ease-in-out;
        cursor: pointer;
    }
    .hover-row:hover {
        background: #eef6ff;
        transform: scale(1.01);
    }

    .hover-schedule {
        transition: all 0.3s ease;
    }
    .hover-schedule:hover {
        background-color: #f8fbff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .day-card {
        transition: all 0.25s ease-in-out;
    }
    .day-card:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .bg-softblue {
        background-color: #f1f6ff;
    }

    .table-bordered th, 
.table-bordered td {
    border: 1px solid #dee2e6 !important;
}

.table thead th {
    border-bottom: 2px solid #dee2e6 !important;
}

.hover-row:hover {
    background-color: #f8f9fa !important;
    transition: background-color 0.2s ease;
}

.table td, .table th {
    vertical-align: middle !important;
}
</style>
@endsection
