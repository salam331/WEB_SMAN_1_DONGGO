@extends('layouts.app')

@section('title', 'Edit Absensi - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Edit Absensi Siswa
                    </h5>
                    <a href="{{ route('teachers.attendances.show', [$schedule->id, $date]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-calendar-day me-1"></i>Tanggal
                                    </h6>
                                    <h4 class="card-text mb-0">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-book me-1"></i>Mata Pelajaran
                                    </h6>
                                    <h4 class="card-text mb-0">{{ $schedule->subject->name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-users me-1"></i>Kelas
                                    </h6>
                                    <h4 class="card-text mb-0">{{ $schedule->classRoom->name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-2">
                                        <i class="fas fa-user-graduate me-1"></i>Total Siswa
                                    </h6>
                                    <h4 class="card-text mb-0">{{ $attendances->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('teachers.attendances.update', [$schedule->id, $date]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Siswa</th>
                                        <th>NIS</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($attendance->student->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $attendance->student->user->profile_photo) }}"
                                                         alt="Foto" class="rounded-circle me-3" width="40" height="40">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $attendance->student->user->name }}</div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="students[{{ $attendance->student->id }}][student_id]" value="{{ $attendance->student->id }}">
                                        </td>
                                        <td>{{ $attendance->student->nis }}</td>
                                        <td>
                                            <select class="form-select form-select-sm" name="students[{{ $attendance->student->id }}][status]" required>
                                                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Hadir</option>
                                                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Terlambat</option>
                                                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                                                <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>Izin</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="students[{{ $attendance->student->id }}][remark]" value="{{ $attendance->remark }}" placeholder="Opsional">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('teachers.attendances.show', [$schedule->id, $date]) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
