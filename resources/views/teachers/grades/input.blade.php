@extends('layouts.app')

@section('title', 'Input Nilai Ujian - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 px-4"
                        style="background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);">
                        <div>
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-edit me-2"></i> Input Nilai: {{ $exam->name }}
                            </h5>
                            <small class="text-light opacity-75">
                                {{ $exam->subject->name }} - {{ $exam->classRoom->name }}
                            </small>
                        </div>
                        <a href="{{ route('teachers.grades') }}" class="btn btn-light btn-sm shadow-sm rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="card-body bg-light">
                        <!-- Info Ujian -->
                        <div class="row g-4 mb-4">
                            @php
                                $cards = [
                                    ['icon' => 'users', 'color' => 'primary', 'value' => $exam->classRoom->students->count(), 'label' => 'Total Siswa'],
                                    ['icon' => 'calendar-alt', 'color' => 'success', 'value' => \Carbon\Carbon::parse($exam->start_date)->format('d/m/Y'), 'label' => 'Tanggal Mulai'],
                                    ['icon' => 'trophy', 'color' => 'warning', 'value' => $exam->total_score, 'label' => 'Nilai Maksimal'],
                                    ['icon' => 'clock', 'color' => 'info', 'value' => $exam->end_date >= now() ? 'Aktif' : 'Selesai', 'label' => 'Status']
                                ];
                            @endphp

                            @foreach($cards as $card)
                                <div class="col-md-3">
                                    <div class="card border-0 shadow-sm rounded-3 hover-lift transition">
                                        <div class="card-body text-center py-3">
                                            <i class="fas fa-{{ $card['icon'] }} fa-2x text-{{ $card['color'] }} mb-2"></i>
                                            <h6 class="fw-bold mb-1">
                                                @if($card['label'] === 'Status')
                                                    <span class="{{ $card['value'] === 'Aktif' ? 'text-success' : 'text-danger' }}">
                                                        {{ $card['value'] }}
                                                    </span>
                                                @else
                                                    {{ $card['value'] }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $card['label'] }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Form Input Nilai -->
                        <form method="POST" action="{{ route('teachers.grades.store', $exam->id) }}" id="gradesForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover align-middle shadow-sm">
                                    <thead class="table-primary">
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
                                            @php
                                                $existingResult = $existingResults->get($student->id);
                                            @endphp
                                            <tr class="row-hover transition">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($student->user->profile_photo)
                                                            <img src="{{ asset('storage/' . $student->user->profile_photo) }}"
                                                                alt="Foto" class="rounded-circle me-2 shadow-sm" width="35"
                                                                height="35">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                                style="width: 35px; height: 35px;">
                                                                <i class="fas fa-user fa-sm"></i>
                                                            </div>
                                                        @endif
                                                        <span class="fw-semibold">{{ $student->user->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $student->nis }}</td>
                                                <td>
                                                    <input type="hidden" name="grades[{{ $index }}][student_id]"
                                                        value="{{ $student->id }}">
                                                    <input type="number"
                                                        class="form-control form-control-sm grade-input border-0 bg-white shadow-sm"
                                                        name="grades[{{ $index }}][score]" min="0"
                                                        max="{{ $exam->total_score }}" placeholder="0-{{ $exam->total_score }}"
                                                        value="{{ $existingResult ? $existingResult->score : '' }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm border-0 shadow-sm grade-select"
                                                        name="grades[{{ $index }}][grade]" required>
                                                        <option value="">Pilih Grade</option>
                                                        <option value="A" {{ $existingResult && $existingResult->grade == 'A' ? 'selected' : '' }}>A (85-100)</option>
                                                        <option value="B" {{ $existingResult && $existingResult->grade == 'B' ? 'selected' : '' }}>B (75-84)</option>
                                                        <option value="C" {{ $existingResult && $existingResult->grade == 'C' ? 'selected' : '' }}>C (60-74)</option>
                                                        <option value="D" {{ $existingResult && $existingResult->grade == 'D' ? 'selected' : '' }}>D (45-59)</option>
                                                        <option value="E" {{ $existingResult && $existingResult->grade == 'E' ? 'selected' : '' }}>E (0-44)</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm border-0 shadow-sm"
                                                        name="grades[{{ $index }}][remark]" placeholder="Opsional"
                                                        value="{{ $existingResult ? $existingResult->remark : '' }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('teachers.grades') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fas fa-arrow-left me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-glow">
                                    <i class="fas fa-save me-1"></i>Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Efek hover dan animasi lembut */
        .hover-lift:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease-in-out;
        }

        .hover-glow:hover {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            transition: all 0.3s ease-in-out;
        }

        .transition {
            transition: all 0.3s ease-in-out;
        }

        .row-hover:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .grade-input:focus,
        .grade-select:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            transform: scale(1.03);
        }

        .animate__animated {
            animation-duration: 0.8s;
        }
    </style>

    <script>
        document.querySelectorAll('.grade-input').forEach(input => {
            input.addEventListener('input', function () {
                const score = parseInt(this.value) || 0;
                const maxScore = {{ $exam->total_score }};
                const percentage = (score / maxScore) * 100;

                let grade = '';
                if (percentage >= 85) grade = 'A';
                else if (percentage >= 75) grade = 'B';
                else if (percentage >= 60) grade = 'C';
                else if (percentage >= 45) grade = 'D';
                else grade = 'E';

                const row = this.closest('tr');
                const gradeSelect = row.querySelector('.grade-select');
                if (gradeSelect) {
                    gradeSelect.value = grade;
                    row.classList.add('table-success');
                    setTimeout(() => row.classList.remove('table-success'), 600);
                }
            });
        });

        // Validasi Form
        document.getElementById('gradesForm').addEventListener('submit', function (e) {
            const gradeInputs = document.querySelectorAll('.grade-input');
            for (const input of gradeInputs) {
                const score = parseInt(input.value) || 0;
                const maxScore = {{ $exam->total_score }};
                if (score > maxScore) {
                    alert(`Nilai tidak boleh lebih dari ${maxScore}`);
                    input.focus();
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>
@endsection