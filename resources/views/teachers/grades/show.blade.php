@extends('layouts.app')

@section('title', 'Detail Nilai - ' . $exam->name . ' - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
                    <div>
                        <h5 class="card-title mb-1 fw-bold d-flex align-items-center">
                            <i class="fas fa-graduation-cap me-2 fs-5"></i> Detail Nilai Siswa
                        </h5>
                        <p class="mb-0 small opacity-75">
                            <i class="fas fa-book me-1"></i>{{ $exam->name }} - {{ $exam->subject->name }} - {{ $exam->classRoom->name }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('teachers.grades') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        @if($exam->examResults->count() > 0)
                            <a href="{{ route('teachers.grades.input', $exam->id) }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="fas fa-edit me-1"></i> Edit Nilai
                            </a>
                        @else
                            <a href="{{ route('teachers.grades.input', $exam->id) }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="fas fa-edit me-1"></i> Input Nilai
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body bg-light-subtle p-4">
                    @if($exam->examResults->count() > 0)
                        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Nilai</th>
                                        <th>Grade</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @php
                                        $students = $exam->classRoom->students->sortBy('user.name');
                                    @endphp
                                    @forelse($students as $index => $student)
                                        @php
                                            $examResult = $exam->examResults->where('student_id', $student->id)->first();
                                        @endphp
                                        <tr class="hover-row">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->nis }}</td>
                                            <td>{{ $student->user->name }}</td>
                                            <td>
                                                @if($examResult)
                                                    <span class="badge bg-success fs-6 px-3 py-2">{{ $examResult->score }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum Diinput</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($examResult)
                                                    <span class="badge bg-info fs-6 px-3 py-2">{{ $examResult->grade }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($examResult && $examResult->remark)
                                                    {{ $examResult->remark }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">Tidak ada siswa di kelas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Statistik -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $exam->examResults->count() }}</h4>
                                        <small>Siswa Dinilai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $exam->examResults->avg('score') ? number_format($exam->examResults->avg('score'), 1) : 0 }}</h4>
                                        <small>Rata-rata Nilai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $exam->examResults->max('score') ?? 0 }}</h4>
                                        <small>Nilai Tertinggi</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $exam->examResults->min('score') ?? 0 }}</h4>
                                        <small>Nilai Terendah</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-4 bounce"></i>
                            <h5 class="text-muted fw-bold">Belum Ada Data Nilai</h5>
                            <p class="text-muted">Nilai siswa untuk ujian ini belum diinput.</p>
                            <a href="{{ route('teachers.grades.input', $exam->id) }}" class="btn btn-gradient-primary shadow-sm">
                                <i class="fas fa-plus me-1"></i> Input Nilai Sekarang
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
@endsection
