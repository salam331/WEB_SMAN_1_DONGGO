@extends('layouts.app')

@section('page_title', 'Ringkasan Kehadiran')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-chart-bar me-2"></i> Ringkasan Kehadiran Siswa
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.attendances.index') }}" class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-list me-1"></i> Daftar Kehadiran
                </a>
                <a href="{{ route('admin.attendances.export') }}" class="btn btn-light btn-sm text-success fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
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
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Mapel</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
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

            <!-- Summary Table -->
            <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                <table class="table table-hover table-bordered align-middle mb-0" style="border-color: #dee2e6;">
                    <thead class="table-primary text-white" style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                        <tr class="text-center align-middle">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Siswa</th>
                            <th rowspan="2">Kelas</th>
                            <th rowspan="2">Mata Pelajaran</th>
                            <th colspan="5">Status Kehadiran</th>
                            <th rowspan="2">Persentase Hadir</th>
                        </tr>
                        <tr class="text-center align-middle">
                            <th>Total</th>
                            <th>Hadir</th>
                            <th>Tidak Hadir</th>
                            <th>Terlambat</th>
                            <th>Izin</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @php $no = 1; @endphp
                        @forelse($summary as $studentSummary)
                            @php $student = $studentSummary['student']; @endphp
                            @php $subjects = $studentSummary['subjects']; @endphp
                            @php $subjectCount = count($subjects); @endphp
                            @php $firstSubject = true; @endphp
                            @foreach($subjects as $subjectId => $subjectData)
                                <tr class="align-middle text-center border-bottom">
                                    @if($firstSubject)
                                        <td rowspan="{{ $subjectCount }}" class="fw-semibold text-secondary">{{ $no++ }}</td>
                                        <td rowspan="{{ $subjectCount }}" class="fw-semibold text-dark">{{ $student->user->name }}</td>
                                        <td rowspan="{{ $subjectCount }}">{{ $student->classRoom->name ?? '-' }}</td>
                                        @php $firstSubject = false; @endphp
                                    @endif
                                    <td class="fw-semibold">{{ $subjectData['subject']->name }}</td>
                                    <td>{{ $subjectData['total'] }}</td>
                                    <td><span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">{{ $subjectData['present'] }}</span></td>
                                    <td><span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow-sm">{{ $subjectData['absent'] }}</span></td>
                                    <td><span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">{{ $subjectData['late'] }}</span></td>
                                    <td><span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">{{ $subjectData['excused'] }}</span></td>
                                    <td>
                                        @php
                                            $percentage = $subjectData['total'] > 0 ? round(($subjectData['present'] / $subjectData['total']) * 100, 1) : 0;
                                            $percentageClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 60 ? 'bg-warning text-dark' : 'bg-danger');
                                        @endphp
                                        <span class="badge {{ $percentageClass }} px-3 py-2 rounded-pill shadow-sm">{{ $percentage }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2 text-primary"></i> Tidak ada data ringkasan kehadiran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
