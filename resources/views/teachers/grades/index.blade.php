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
                                        <th>Ujian</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Tanggal Input</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @php
                                        // Group examResults by exam_id, ambil ujian terbaru
                                        $exams = $examResults->groupBy('exam_id')->map(function($items) { return $items->first(); });
                                    @endphp
                                    @forelse($exams as $exam)
                                    <tr class="hover-row">
                                        <td>{{ $exam->exam->name }}</td>
                                        <td>{{ $exam->exam->classRoom->name }}</td>
                                        <td>{{ $exam->exam->subject->name }}</td>
                                        <td>
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            {{ $exam->exam->created_at ? $exam->exam->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('teachers.grades.show', $exam->exam->id) }}" class="btn btn-sm btn-info shadow-sm" title="Lihat Nilai Siswa">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada data ujian.</td>
                                    </tr>
                                    @endforelse
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
