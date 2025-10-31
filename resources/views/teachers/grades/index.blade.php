@extends('layouts.app')

@section('title', 'Kelola Nilai - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                        Data Nilai Siswa
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <select class="form-select" id="classFilter">
                                <option value="">Semua Kelas</option>
                                @foreach(auth()->user()->teacher->subjectTeachers->unique('class_id') as $subjectTeacher)
                                    <option value="{{ $subjectTeacher->class_id }}" {{ request('class_id') == $subjectTeacher->class_id ? 'selected' : '' }}>
                                        {{ $subjectTeacher->classRoom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mata Pelajaran</label>
                            <select class="form-select" id="subjectFilter">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach(auth()->user()->teacher->subjectTeachers->unique('subject_id') as $subjectTeacher)
                                    <option value="{{ $subjectTeacher->subject_id }}" {{ request('subject_id') == $subjectTeacher->subject_id ? 'selected' : '' }}>
                                        {{ $subjectTeacher->subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-outline-primary w-100" onclick="filterGrades()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>

                    @if($examResults->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Ujian</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Nilai</th>
                                        <th>Grade</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examResults as $result)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($result->student->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $result->student->user->profile_photo) }}"
                                                         alt="Foto" class="rounded-circle me-2" width="32" height="32">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user fa-sm"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $result->student->user->name }}</div>
                                                    <small class="text-muted">{{ $result->student->nis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $result->exam->classRoom->name }}</td>
                                        <td>{{ $result->exam->name }}</td>
                                        <td>{{ $result->exam->subject->name }}</td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $result->score }}/{{ $result->exam->total_score }}</span>
                                        </td>
                                        <td>
                                            @switch($result->grade)
                                                @case('A')
                                                    <span class="badge bg-success">A</span>
                                                    @break
                                                @case('B')
                                                    <span class="badge bg-primary">B</span>
                                                    @break
                                                @case('C')
                                                    <span class="badge bg-warning text-dark">C</span>
                                                    @break
                                                @case('D')
                                                    <span class="badge bg-danger">D</span>
                                                    @break
                                                @case('E')
                                                    <span class="badge bg-dark">E</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $result->grade }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $result->remark ?? '-' }}</td>
                                        <td>{{ $result->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $examResults->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Belum Ada Data Nilai</h5>
                            <p class="text-muted">Data nilai siswa akan muncul di sini setelah Anda menginput nilai ujian.</p>
                            <a href="{{ route('teachers.exams.store') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Lihat Ujian
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterGrades() {
    const classId = document.getElementById('classFilter').value;
    const subjectId = document.getElementById('subjectFilter').value;

    let url = '{{ route("teachers.grades") }}';
    const params = new URLSearchParams();

    if (classId) params.append('class_id', classId);
    if (subjectId) params.append('subject_id', subjectId);

    if (params.toString()) {
        url += '?' + params.toString();
    }

    window.location.href = url;
}
</script>
@endsection
