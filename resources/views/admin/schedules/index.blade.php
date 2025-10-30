@extends('layouts.app')

@section('page_title', 'Manajemen Jadwal Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">

            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-calendar-alt me-2"></i> Daftar Jadwal Pelajaran
                </h5>
                <a href="{{ route('admin.schedules.create') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Jadwal
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">

                <!-- Filter & Search -->
                <form action="{{ route('admin.schedules.index') }}" method="get"
                    class="mb-4 d-flex flex-wrap justify-content-end align-items-center gap-3">
                    <div class="w-25">
                        <select name="day" class="form-select shadow-sm rounded-pill border-0">
                            <option value="">Semua Hari</option>
                            @foreach(['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'] as $key => $dayName)
                                <option value="{{ $key }}" {{ request('day') == $key ? 'selected' : '' }}>{{ $dayName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group w-50">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-0 rounded-start-pill"
                            placeholder="Cari mata pelajaran, guru, atau kelas...">
                        <button class="btn btn-primary rounded-end-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </form>

                <!-- Alerts -->
                @foreach (['success', 'error'] as $msg)
                    @if(session($msg))
                        <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }} alert-dismissible fade show rounded-pill shadow-sm"
                            role="alert">
                            <i class="fas fa-{{ $msg == 'success' ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
                            {{ session($msg) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                @endforeach

                <!-- Badge colors -->
                @php
                    $dayColors = [
                        'monday' => 'bg-primary text-white',
                        'tuesday' => 'bg-success text-white',
                        'wednesday' => 'bg-warning text-dark',
                        'thursday' => 'bg-purple text-white',
                        'friday' => 'bg-pink text-white',
                        'saturday' => 'bg-indigo text-white',
                        'sunday' => 'bg-secondary text-white',
                    ];
                @endphp

                <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                    <table class="table table-hover table-bordered align-middle mb-0" style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: 2%">No</th>
                                <th style="width: 7%">Hari</th>
                                <th style="width: 10%"><i class="far fa-clock me-1"></i> Waktu</th>
                                <th style="width: 17%">Mata Pelajaran</th>
                                <th style="width: 7%">Kelas</th>
                                <th style="width: auto">Guru</th>
                                <th style="width: 10%">Ruangan</th>
                                <th style="width: 17%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($schedules as $schedule)
                                <tr class="align-middle border-bottom text-center">
                                    <td class="fw-semibold text-secondary border-end">
                                        {{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}
                                    </td>

                                    <!-- Hari dengan badge -->
                                    <td>
                                        @php
                                            $dayColors = [
                                                'monday' => 'bg-primary text-white',
                                                'tuesday' => 'bg-success text-white',
                                                'wednesday' => 'bg-warning text-dark',
                                                'thursday' => 'bg-purple text-white',
                                                'friday' => 'bg-pink text-white',
                                                'saturday' => 'bg-indigo text-white',
                                                'sunday' => 'bg-secondary text-white',
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $dayColors[$schedule->day] ?? 'bg-light text-dark' }} px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-calendar-day me-1"></i> {{ ucfirst($schedule->day) }}
                                        </span>
                                    </td>

                                    <!-- Waktu -->
                                    <td class="fw-semibold text-secondary">
                                        <i class="far fa-clock me-1 text-primary"></i> <br>
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>

                                    <!-- Mata Pelajaran -->
                                    <td class="fw-semibold text-dark">{{ $schedule->subject->name ?? '-' }}</td>

                                    <!-- Kelas -->
                                    <td>
                                        <span class="badge bg-light text-dark border shadow-sm">
                                            <i class="fas fa-door-open me-1 text-secondary"></i>
                                            {{ $schedule->classRoom?->name ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Guru -->
                                    <td class="fw-semibold text-dark">
                                        <i class="fas fa-user-tie me-1 text-success"></i>
                                        {{ $schedule->teacher?->user?->name ?? '-' }}
                                    </td>

                                    <!-- Ruangan -->
                                    <td class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i> <br> {{ $schedule->room ?? '-' }}
                                    </td>

                                    <!-- Aksi -->
                                    <td>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="{{ route('admin.schedules.show', $schedule) }}"
                                                class="btn btn-sm btn-outline-info rounded-pill shadow-sm px-3 py-1 fw-semibold">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                            <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-3 py-1 fw-semibold">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Belum ada data jadwal.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- 🎨 Style Tambahan -->
    <style>
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: none;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f8ff !important;
            transition: all 0.25s ease-in-out;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #dc3545, #ff4d6d) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-outline-info:hover {
            background: linear-gradient(135deg, #0dcaf0, #0d6efd) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .input-group input:focus,
        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
@endsection