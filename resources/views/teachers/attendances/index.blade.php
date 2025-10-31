@extends('layouts.app')

@section('title', 'Kelola Absensi - SMAN 1 DONGGO')

@section('content')
    <style>
        /* ===== Tampilan Umum ===== */
        .card {
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            border-bottom: none;
            background: linear-gradient(90deg, #007bff 0%, #0056d2 100%);
            color: white;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .card-title i {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.8;
            }
        }

        /* ===== Tabel ===== */
        .table {
            border-radius: 1rem;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
            color: #343a40;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: scale(1.01);
        }

        /* ===== Tombol ===== */
        .btn {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        .btn-outline-info:hover {
            background-color: #0dcaf0;
            color: #fff;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #fff;
        }

        /* ===== Filter ===== */
        .form-control,
        .btn-outline-primary {
            border-radius: 0.6rem;
        }

        /* ===== Efek Kosong ===== */
        .empty-state i {
            animation: fadeInDown 1.2s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-calendar-check me-2"></i> Data Absensi Siswa
                        </h5>
                        <a href="{{ route('teachers.attendances.create') }}" class="btn btn-light text-primary fw-semibold">
                            <i class="fas fa-plus me-1"></i> Input Absensi
                        </a>
                    </div>

                    <div class="card-body bg-white">
                        <!-- Filter -->
                        <div class="row g-3 mb-4 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold text-secondary">Tanggal</label>
                                <input type="date" class="form-control shadow-sm" id="dateFilter"
                                    value="{{ request('date') }}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-primary w-100 fw-semibold" onclick="filterAttendances()">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>

                        @if($attendances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Total Siswa</th>
                                            <th>Hadir</th>
                                            <th>Terlambat</th>
                                            <th>Tidak Hadir</th>
                                            <th>Izin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendances as $attendance)
                                            <tr>
                                                <td class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                                <td>{{ $attendance->subject_name }}</td>
                                                <td>{{ $attendance->class_name }}</td>
                                                <td><span class="badge bg-secondary">{{ $attendance->total_students }}</span></td>
                                                <td><span class="badge bg-success">{{ $attendance->present_count }}</span></td>
                                                <td><span class="badge bg-warning text-dark">{{ $attendance->late_count }}</span>
                                                </td>
                                                <td><span class="badge bg-danger">{{ $attendance->absent_count }}</span></td>
                                                <td><span class="badge bg-info">{{ $attendance->excused_count }}</span></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('teachers.attendances.show', [$attendance->schedule_id, $attendance->date]) }}"
                                                            class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('teachers.attendances.edit', [$attendance->schedule_id, $attendance->date]) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteAttendance({{ $attendance->schedule_id }}, '{{ $attendance->date }}')"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $attendances->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5 empty-state">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h5 class="fw-semibold text-muted">Belum Ada Data Absensi</h5>
                                <p class="text-muted">Data absensi siswa akan muncul di sini setelah Anda menginputnya.</p>
                                <a href="{{ route('teachers.attendances.create') }}" class="btn btn-primary mt-2 shadow-sm">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterAttendances() {
            const date = document.getElementById('dateFilter').value;
            let url = '{{ route("teachers.attendances") }}';
            const params = new URLSearchParams();

            if (date) params.append('date', date);
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
        }

        function deleteAttendance(scheduleId, date) {
            if (confirm('Apakah Anda yakin ingin menghapus data absensi ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/teacher/attendances/${scheduleId}/${date}`;

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';

                form.appendChild(methodField);
                form.appendChild(csrfField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection