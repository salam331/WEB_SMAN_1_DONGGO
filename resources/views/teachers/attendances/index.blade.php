@extends('layouts.app')

@section('title', 'Kelola Absensi - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        Data Absensi Siswa
                    </h5>
                    <a href="{{ route('teachers.attendances.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Input Absensi
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="dateFilter" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-outline-primary w-100" onclick="filterAttendances()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>

                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
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
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->subject_name }}</td>
                                        <td>{{ $attendance->class_name }}</td>
                                        <td><span class="badge bg-secondary">{{ $attendance->total_students }}</span></td>
                                        <td><span class="badge bg-success">{{ $attendance->present_count }}</span></td>
                                        <td><span class="badge bg-warning text-dark">{{ $attendance->late_count }}</span></td>
                                        <td><span class="badge bg-danger">{{ $attendance->absent_count }}</span></td>
                                        <td><span class="badge bg-info">{{ $attendance->excused_count }}</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teachers.attendances.show', [$attendance->schedule_id, $attendance->date]) }}" class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teachers.attendances.edit', [$attendance->schedule_id, $attendance->date]) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-danger btn-sm" onclick="deleteAttendance({{ $attendance->schedule_id }}, '{{ $attendance->date }}')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $attendances->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Belum Ada Data Absensi</h5>
                            <p class="text-muted">Data absensi siswa akan muncul di sini setelah Anda menginputnya.</p>
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

    if (params.toString()) {
        url += '?' + params.toString();
    }

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
