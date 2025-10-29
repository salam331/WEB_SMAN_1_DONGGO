@extends('layouts.app')

@section('page_title', 'Manajemen Kehadiran')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-user-check me-2"></i> Daftar Kehadiran Siswa
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.attendances.create') }}" class="btn btn-success btn-sm fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Kehadiran
                </a>
                <a href="{{ route('admin.attendances.summary') }}" class="btn btn-light btn-sm text-info fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-chart-bar me-1"></i> Ringkasan
                </a>
                <a href="{{ route('admin.attendances.export') }}" class="btn btn-light btn-sm text-success fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.attendances.report') }}" class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-chart-line me-1"></i> Laporan
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body bg-light bg-gradient p-4">

            <!-- Filter Form -->
            <form method="GET" class="mb-4 d-flex flex-wrap gap-3 align-items-end">
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Kelas</label>
                    <select name="class_id" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Kelas</option>
                        @foreach(\App\Models\ClassRoom::all() as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Status</label>
                    <select name="status" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Izin</option>
                    </select>
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control shadow-sm rounded-pill border-0">
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control shadow-sm rounded-pill border-0">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                <table class="table table-hover table-bordered align-middle mb-0" style="border-color: #dee2e6;">
                    <thead class="table-primary text-white" style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                        <tr class="text-center align-middle">
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($attendances as $attendance)
                            <tr class="align-middle text-center border-bottom">
                                <td class="fw-semibold text-secondary">{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                                <td class="fw-semibold text-dark">{{ $attendance->student->user->name }}</td>
                                <td>{{ $attendance->student->classRoom->name ?? '-' }}</td>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'present' => 'bg-success text-white',
                                            'absent' => 'bg-danger text-white',
                                            'late' => 'bg-warning text-dark',
                                            'excused' => 'bg-info text-dark',
                                            'default' => 'bg-secondary text-white'
                                        ];
                                    @endphp
                                    <span class="badge px-3 py-2 rounded-pill shadow-sm {{ $statusColors[$attendance->status] ?? $statusColors['default'] }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td>{{ $attendance->remark ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-user-tag me-1"></i>{{ $attendance->recordedBy->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.attendances.show', $attendance) }}" class="btn btn-sm btn-info rounded-pill" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn btn-sm btn-warning rounded-pill" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.attendances.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kehadiran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2 text-primary"></i> Tidak ada data kehadiran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $attendances->links() }}
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

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .form-select:focus, .form-control:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
</div>
@endsection
