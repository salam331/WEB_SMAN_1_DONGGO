@extends('layouts.app')

@section('title', 'Input Nilai Ujian - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit text-primary me-2"></i>
                            Input Nilai: {{ $exam->name }}
                        </h5>
                        <small class="text-muted">
                            {{ $exam->subject->name }} - {{ $exam->classRoom->name }}
                        </small>
                    </div>
                    <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Info Ujian -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-1">{{ $exam->classRoom->students->count() }}</h6>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                    <h6 class="mb-1">{{ \Carbon\Carbon::parse($exam->start_date)->format('d/m/Y') }}</h6>
                                    <small class="text-muted">Tanggal Mulai</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                    <h6 class="mb-1">{{ $exam->total_score }}</h6>
                                    <small class="text-muted">Nilai Maksimal</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x text-info mb-2"></i>
                                    <h6 class="mb-1">
                                        @if($exam->end_date >= now())
                                            <span class="text-success">Aktif</span>
                                        @else
                                            <span class="text-danger">Selesai</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">Status</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Input Nilai -->
                    <form method="POST" action="{{ route('teacher.grades.store', $exam->id) }}" id="gradesForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Siswa</th>
                                        <th>NIS</th>
                                        <th>Nilai</th>
                                        <th>Grade</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exam->classRoom->students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($student->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $student->user->profile_photo) }}"
                                                         alt="Foto" class="rounded-circle me-2" width="32" height="32">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user fa-sm"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-semibold">{{ $student->user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $student->nis }}</td>
                                        <td>
                                            <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            <input type="number" class="form-control form-control-sm grade-input"
                                                   name="grades[{{ $index }}][score]" min="0" max="{{ $exam->total_score }}"
                                                   placeholder="0-{{ $exam->total_score }}" required>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm grade-select" name="grades[{{ $index }}][grade]" required>
                                                <option value="">Pilih Grade</option>
                                                <option value="A">A (85-100)</option>
                                                <option value="B">B (75-84)</option>
                                                <option value="C">C (60-74)</option>
                                                <option value="D">D (45-59)</option>
                                                <option value="E">E (0-44)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   name="grades[{{ $index }}][remark]" placeholder="Opsional">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill grade based on score
document.querySelectorAll('.grade-input').forEach(input => {
    input.addEventListener('input', function() {
        const score = parseInt(this.value) || 0;
        const maxScore = {{ $exam->total_score }};
        const percentage = (score / maxScore) * 100;

        let grade = '';
        if (percentage >= 85) grade = 'A';
        else if (percentage >= 75) grade = 'B';
        else if (percentage >= 60) grade = 'C';
        else if (percentage >= 45) grade = 'D';
        else grade = 'E';

        // Find the corresponding grade select in the same row
        const row = this.closest('tr');
        const gradeSelect = row.querySelector('.grade-select');
        if (gradeSelect) {
            gradeSelect.value = grade;
        }
    });
});

// Form validation
document.getElementById('gradesForm').addEventListener('submit', function(e) {
    const gradeInputs = document.querySelectorAll('.grade-input');
    let isValid = true;

    gradeInputs.forEach(input => {
        const score = parseInt(input.value) || 0;
        const maxScore = {{ $exam->total_score }};

        if (score > maxScore) {
            alert(`Nilai tidak boleh lebih dari ${maxScore}`);
            input.focus();
            isValid = false;
            e.preventDefault();
            return false;
        }
    });

    if (!isValid) {
        e.preventDefault();
    }
});
</script>
@endsection
