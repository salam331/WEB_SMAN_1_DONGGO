@extends('layouts.app')

@section('title', 'Riwayat Absensi - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <div class="card-header bg-gradient bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-calendar-check me-2"></i> Riwayat Absensi
                    </h5>
                    @php
                        $now = now()->setTimezone('Asia/Makassar');
                    @endphp
                    <small class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</small>
                </div>

                <div class="card-body p-4 bg-light-subtle">
                    @if($attendances->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
    <table class="table table-bordered align-middle table-hover mb-0">
        <thead class="bg-primary text-white text-center">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Mata Pelajaran</th>
                <th style="width: 25%;">Guru</th>
                <th style="width: 15%;">Hari</th>
                <th style="width: 10%;">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @php
                // Filter agar hanya tampil satu baris per mata pelajaran unik
                $uniqueAttendances = $attendances->unique(fn($item) => $item->schedule->subject->name ?? 'N/A')->values();
            @endphp

            @foreach($uniqueAttendances as $i => $attendance)
                <tr class="hover-row">
                    <td class="text-center fw-semibold">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-light-primary me-3">
                                <i class="fas fa-book-open text-primary"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">
                                    {{ $attendance->schedule->subject->name ?? 'N/A' }}
                                </span>
                                <small class="text-muted">
                                    Kelas: {{ $attendance->schedule->classRoom->name ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <i class="fas fa-user text-secondary me-2"></i>
                        {{ $attendance->schedule->teacher->user->name ?? 'N/A' }}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-gradient bg-info text-white px-3 py-2 rounded-pill shadow-sm">
                            @switch($attendance->schedule->day)
                                @case('Monday') Senin @break
                                @case('Tuesday') Selasa @break
                                @case('Wednesday') Rabu @break
                                @case('Thursday') Kamis @break
                                @case('Friday') Jumat @break
                                @case('Saturday') Sabtu @break
                                @default Minggu
                            @endswitch
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('student.attendance.show', $attendance->schedule->id) }}" 
                           class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 shadow-sm">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
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
@endsection

<style>
/* Efek hover baris tabel */
.hover-row:hover {
    background-color: #f8fbff !important;
    transform: scale(1.01);
    transition: all 0.2s ease-in-out;
}

/* Gaya ikon bundar di kolom mata pelajaran */
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f1f7ff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 0 4px rgba(0,0,0,0.1);
}

/* Tabel dan border */
.table-bordered th, .table-bordered td {
    border: 1px solid #dee2e6 !important;
}

.table thead th {
    border-bottom: 2px solid #dee2e6 !important;
    vertical-align: middle;
}

.table td {
    vertical-align: middle !important;
}

/* Header animasi */
.animate-card {
    animation: slideUp 0.4s ease-in-out;
}

@keyframes slideUp {
    from {
        transform: translateY(15px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Warna lembut tambahan */
.bg-light-subtle {
    background-color: #f9fbfd !important;
}

.bg-light-primary {
    background-color: #e8f1ff !important;
}
</style>
