@extends('layouts.app')

@section('title', 'Jadwal Mengajar - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-gradient bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-calendar-alt me-2"></i> Jadwal Mengajar
                        </h5>
                        <div>
                            <span class="badge bg-light text-primary shadow-sm px-3 py-2 fw-medium">
                                <i class="fas fa-clock me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body bg-light">
                        @if($schedules->count() > 0)
                            <!-- TABEL JADWAL MENGAJAR -->
                            <div class="table-responsive shadow-sm rounded-3 overflow-hidden mb-5">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-primary text-center">
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
                                            <tr class="hover-row">
                                                <td class="text-center">
                                                    <span class="badge bg-primary shadow-sm px-3">{{ $schedule->day }}</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-clock text-muted me-1"></i>
                                                    <span class="fw-semibold">{{ $schedule->start_time }} -
                                                        {{ $schedule->end_time }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold text-dark">
                                                        <i
                                                            class="fas fa-book-open text-primary me-1"></i>{{ $schedule->subject->name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success px-3">{{ $schedule->classRoom->name }}</span>
                                                </td>
                                                <td>
                                                    {{ $schedule->classRoom->room ?? 'Belum ditentukan' }}
                                                </td>
                                                <td class="text-center">
                                                    @if($schedule->day == now()->format('l'))
                                                        <span class="badge bg-warning text-dark shadow-sm">
                                                            <i class="fas fa-sun me-1"></i> Hari Ini
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success shadow-sm">
                                                            <i class="fas fa-check me-1"></i> Aktif
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- KALENDER MINGGUAN -->
                            <div class="mt-4">
                                <h6 class="fw-semibold text-secondary mb-3">
                                    <i class="fas fa-calendar-week text-primary me-2"></i> Kalender Mingguan
                                </h6>

                                <div class="row g-3">
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
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden day-card">
                                                <div
                                                    class="card-header d-flex justify-content-between align-items-center bg-softblue py-2 px-3">
                                                    <h6 class="mb-0 fw-semibold text-secondary">
                                                        <i class="fas fa-calendar-day text-primary me-2"></i>{{ $indonesian }}
                                                    </h6>
                                                    @if($english == now()->format('l'))
                                                        <span class="badge bg-gradient bg-primary shadow-sm">Hari Ini</span>
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
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-clock me-1"></i>{{ $schedule->start_time }} -
                                                                        {{ $schedule->end_time }}
                                                                    </small>
                                                                </div>
                                                                <div class="text-muted">
                                                                    <i class="fas fa-school text-success me-1"></i>
                                                                    {{ $schedule->classRoom->name }}
                                                                    @if($schedule->classRoom->room)
                                                                        <small>(Ruang {{ $schedule->classRoom->room }})</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="text-center text-muted py-3">
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
                            <!-- EMPTY STATE -->
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                                <h5 class="fw-semibold text-muted">Belum Ada Jadwal Mengajar</h5>
                                <p class="text-muted mb-0">Jadwal Anda belum ditentukan oleh administrator.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style Enhancement -->
    <style>
        .hover-row:hover {
            background-color: #f6faff;
            transform: scale(1.01);
            transition: all 0.2s ease-in-out;
        }

        .hover-schedule {
            transition: all 0.3s ease;
            background-color: #ffffff;
        }

        .hover-schedule:hover {
            background-color: #f0f9ff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }

        .day-card {
            transition: transform 0.25s ease-in-out;
        }

        .day-card:hover {
            transform: scale(1.02);
        }

        .badge {
            font-size: 0.8rem;
        }

        .card-header {
            transition: background-color 0.3s ease;
        }

        .card-header:hover {
            background-color: #e9f2ff;
        }
    </style>
@endsection