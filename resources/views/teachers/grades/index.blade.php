@extends('layouts.app')

@section('title', 'Kelola Nilai - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                        <i class="fas fa-graduation-cap me-2 fs-5"></i> Kelola Nilai Siswa
                    </h5>
                    <span class="badge bg-light text-primary shadow-sm px-3 py-2">
                        <i class="fas fa-database me-1"></i> Total: {{ $examResults->count() }} Data
                    </span>
                </div>

                <div class="card-body bg-light-subtle p-4">
                    <!-- Filter -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Kelas</label>
                            <select class="form-select shadow-sm" id="classFilter">
                                <option value="">Semua Kelas</option>
                                @foreach(auth()->user()->teacher->subjectTeachers->unique('class_id') as $subjectTeacher)
                                    <option value="{{ $subjectTeacher->class_id }}" {{ request('class_id') == $subjectTeacher->class_id ? 'selected' : '' }}>
                                        {{ $subjectTeacher->classRoom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Mata Pelajaran</label>
                            <select class="form-select shadow-sm" id="subjectFilter">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach(auth()->user()->teacher->subjectTeachers->unique('subject_id') as $subjectTeacher)
                                    <option value="{{ $subjectTeacher->subject_id }}" {{ request('subject_id') == $subjectTeacher->subject_id ? 'selected' : '' }}>
                                        {{ $subjectTeacher->subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary shadow-sm w-100 animate-btn" onclick="filterGrades()">
                                <i class="fas fa-filter me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>

                    @if($examResults->count() > 0)
                        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="bg-primary text-white">
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
                                <tbody class="bg-white">
                                    @foreach($examResults as $result)
                                    <tr class="hover-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($result->student->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $result->student->user->profile_photo) }}"
                                                         alt="Foto" class="rounded-circle me-3 shadow-sm" width="40" height="40">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user fa-sm"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $result->student->user->name }}</div>
                                                    <small class="text-muted">{{ $result->student->nis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $result->exam->classRoom->name }}</td>
                                        <td>{{ $result->exam->name }}</td>
                                        <td>{{ $result->exam->subject->name }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark fs-6 shadow-sm">
                                                {{ $result->score }}/{{ $result->exam->total_score }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($result->grade)
                                                @case('A') <span class="badge bg-success shadow-sm fs-6">A</span> @break
                                                @case('B') <span class="badge bg-primary shadow-sm fs-6">B</span> @break
                                                @case('C') <span class="badge bg-warning text-dark shadow-sm fs-6">C</span> @break
                                                @case('D') <span class="badge bg-danger shadow-sm fs-6">D</span> @break
                                                @case('E') <span class="badge bg-dark shadow-sm fs-6">E</span> @break
                                                @default <span class="badge bg-secondary shadow-sm fs-6">{{ $result->grade }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $result->remark ?? '-' }}</td>
                                        <td>
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            {{ $result->created_at->format('d/m/Y H:i') }}
                                        </td>
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
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-4 bounce"></i>
                            <h5 class="text-muted fw-bold">Belum Ada Data Nilai</h5>
                            <p class="text-muted">Data nilai siswa akan muncul di sini setelah Anda menginput nilai ujian.</p>
                            <a href="{{ route('teachers.exams.store') }}" class="btn btn-gradient-primary shadow-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Nilai
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Efek Animasi -->
<style>
    .fade-in { animation: fadeIn 0.8s ease-in-out; }
    .hover-row:hover { background-color: #f8f9fa !important; transform: scale(1.01); transition: all 0.2s ease-in-out; }
    .animate-btn { transition: all 0.3s ease-in-out; }
    .animate-btn:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
    .bounce { animation: bounce 1.5s infinite alternate; }

    .bg-gradient-primary {
        background: linear-gradient(90deg, #0062cc 0%, #4e9eff 100%);
    }
    .btn-gradient-primary {
        background: linear-gradient(90deg, #007bff, #4ea9ff);
        border: none;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(90deg, #0056b3, #2185d0);
        transform: translateY(-2px);
    }

    @keyframes fadeIn { from {opacity: 0; transform: translateY(20px);} to {opacity: 1; transform: translateY(0);} }
    @keyframes bounce { from { transform: translateY(0); } to { transform: translateY(-6px); } }
</style>

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
