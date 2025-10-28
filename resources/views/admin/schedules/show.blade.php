@extends('layouts.app')

@section('page_title', 'Detail Jadwal Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-calendar-alt me-2"></i> Detail Jadwal Pelajaran
                </h5>
                <div>
                    <a href="{{ route('admin.schedules.index') }}"
                        class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('admin.schedules.edit', $schedule) }}"
                        class="btn btn-warning btn-sm text-dark fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill shadow-sm fw-semibold"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Informasi Jadwal -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i> Informasi Jadwal
                            </h6>
                            <div class="mb-2"><strong>Hari:</strong> {{ ucfirst($schedule->day) }}</div>
                            <div class="mb-2"><strong>Waktu:</strong> {{ $schedule->start_time }} -
                                {{ $schedule->end_time }}</div>
                            <div class="mb-2"><strong>Ruangan:</strong> {{ $schedule->room ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Informasi Akademik
                            </h6>
                            <div class="mb-2"><strong>Mata Pelajaran:</strong> {{ $schedule->subject->name }}
                                ({{ $schedule->subject->code }})</div>
                            <div class="mb-2"><strong>Kelas:</strong> {{ $schedule->classRoom->name }}</div>
                            <div class="mb-2"><strong>Guru:</strong> {{ $schedule->teacher->user->name }}
                                @if($schedule->teacher->nip)
                                    <span class="text-muted small">NIP: {{ $schedule->teacher->nip }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Kehadiran -->
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-users me-2"></i> Ringkasan Kehadiran
                    </h6>
                    @if($schedule->attendances->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden border border-light-subtle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-white text-center"
                                    style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                                    <tr>
                                        <th class="py-3">Tanggal</th>
                                        <th class="py-3">Hadir</th>
                                        <th class="py-3">Tidak Hadir</th>
                                        <th class="py-3">Terlambat</th>
                                        <th class="py-3">Izin</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-center">
                                    @php
                                        $groupedAttendances = $schedule->attendances->groupBy(function ($attendance) {
                                            return $attendance->date->format('Y-m-d');
                                        });
                                    @endphp
                                    @foreach($groupedAttendances as $date => $attendances)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                            <td>{{ $attendances->where('status', 'present')->count() }}</td>
                                            <td>{{ $attendances->where('status', 'absent')->count() }}</td>
                                            <td>{{ $attendances->where('status', 'late')->count() }}</td>
                                            <td>{{ $attendances->where('status', 'excused')->count() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                            <span class="text-muted fst-italic">Belum ada data kehadiran untuk jadwal ini.</span>
                        </div>
                    @endif
                </div>

                <!-- Materi Terbaru -->
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-book-reader me-2"></i> Materi Terbaru
                    </h6>
                    @if($schedule->subject->materials->count() > 0)
                        <div class="row g-3">
                            @foreach($schedule->subject->materials->take(6) as $material)
                                <div class="col-md-4">
                                    <div class="p-3 bg-white border rounded-3 shadow-sm h-100">
                                        <h6 class="fw-semibold text-dark">{{ $material->title }}</h6>
                                        <p class="small text-muted">{{ Str::limit($material->description, 100) }}</p>
                                        <div class="text-muted small">
                                            <i class="fas fa-user me-1 text-primary"></i> {{ $material->teacher->user->name }}<br>
                                            <i class="fas fa-clock me-1 text-primary"></i>
                                            {{ $material->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($schedule->subject->materials->count() > 6)
                            <div class="mt-3 text-center">
                                <a href="{{ route('admin.subjects.show', $schedule->subject) }}" class="text-primary fw-semibold">
                                    Lihat semua materi ({{ $schedule->subject->materials->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted fst-italic">Belum ada materi untuk mata pelajaran ini.</p>
                    @endif
                </div>

                <!-- Ujian -->
                <div>
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-file-alt me-2"></i> Ujian
                    </h6>
                    @if(isset($schedule->subject->exams) && $schedule->subject->exams->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden border border-light-subtle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-white text-center"
                                    style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                                    <tr>
                                        <th class="py-3">No</th>
                                        <th class="py-3">Judul</th>
                                        <th class="py-3">Tanggal</th>
                                        <th class="py-3">Durasi</th>
                                        <th class="py-3">Guru</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-center">
                                    @foreach($schedule->subject->exams as $exam)
                                        <tr class="border-bottom">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-semibold text-dark">
                                                <i class="fas fa-file-signature me-1 text-primary"></i> {{ $exam->title }}
                                            </td>
                                            <td class="text-muted">
                                                @if($exam->exam_date)
                                                    <i class="far fa-calendar-alt me-1 text-info"></i>
                                                    {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted fst-italic">Belum dijadwalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border shadow-sm px-3 py-2 rounded-pill">
                                                    <i class="fas fa-stopwatch me-1 text-secondary"></i>
                                                    {{ $exam->duration ? $exam->duration . ' menit' : '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $exam->teacher?->user?->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                            <span class="text-muted fst-italic">Belum ada ujian untuk mata pelajaran ini.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection