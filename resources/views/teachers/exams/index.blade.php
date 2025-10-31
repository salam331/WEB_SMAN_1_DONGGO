@extends('layouts.app')

@section('title', 'Kelola Ujian - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 px-4"
                        style="background: linear-gradient(90deg, #007bff 0%, #00b4d8 100%);">
                        <div>
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-file-alt me-2"></i> Kelola Ujian
                            </h5>
                            <small class="text-light opacity-75">Daftar ujian yang telah dibuat</small>
                        </div>
                        <a href="{{ route('teachers.exams.create') }}"
                            class="btn btn-light shadow-sm rounded-pill px-3 hover-glow">
                            <i class="fas fa-plus me-1"></i>Buat Ujian Baru
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="card-body bg-light">
                        <!-- Filter -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <select class="form-select shadow-sm rounded-pill border-0" id="subjectFilter">
                                    <option value="">Semua Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select shadow-sm rounded-pill border-0" id="classFilter">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $class)
                                        @if(is_object($class))
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select shadow-sm rounded-pill border-0" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Daftar Ujian -->
                        @if($exams->count() > 0)
                            <div class="row g-4">
                                @foreach($exams as $index => $exam)
                                    <div
                                        class="col-md-6 col-lg-4 animate__animated animate__fadeInUp animate__delay-{{ ($index % 6) + 1 }}s">
                                        <div class="card border-0 shadow-sm rounded-3 h-100 hover-lift transition overflow-hidden">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <h6 class="card-title mb-1 fw-bold text-dark">{{ $exam->title }}</h6>
                                                    <span
                                                        class="badge bg-{{ $exam->status == 'published' ? 'success' : ($exam->status == 'completed' ? 'primary' : 'secondary') }}">
                                                        {{ ucfirst($exam->status) }}
                                                    </span>
                                                </div>
                                                <p class="card-text text-muted small mb-3">{{ Str::limit($exam->description, 100) }}
                                                </p>

                                                <div class="row g-2 mb-3 small">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Mata Pelajaran</small>
                                                        <span class="badge bg-light text-dark">{{ $exam->subject->name }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Kelas</small>
                                                        <span class="badge bg-light text-dark">{{ $exam->classRoom->name }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Tanggal</small>
                                                        <span>{{ $exam->exam_date->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Durasi</small>
                                                        <span>{{ $exam->duration }} menit</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2 mt-3">
                                                    @if($exam->status == 'published')
                                                        <a href="{{ route('teacher.exams.input_grades', $exam) }}"
                                                            class="btn btn-success btn-sm rounded-pill shadow-sm">
                                                            <i class="fas fa-graduation-cap me-1"></i>Input Nilai
                                                        </a>
                                                    @endif
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm rounded-3">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('teacher.exams.show', $exam) }}">
                                                                    <i class="fas fa-eye me-2 text-info"></i>Lihat Detail
                                                                </a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('teacher.exams.edit', $exam) }}">
                                                                    <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                                </a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item text-danger" href="#"
                                                                    onclick="confirmDelete({{ $exam->id }})">
                                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                                </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-light border-0">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1 text-secondary"></i>Dibuat:
                                                    {{ $exam->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-5">
                                {{ $exams->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5 animate__animated animate__fadeIn">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada ujian</h5>
                                <p class="text-muted">Buat ujian pertama Anda untuk memulai</p>
                                <a href="{{ route('teachers.exams.create') }}"
                                    class="btn btn-primary rounded-pill px-4 shadow-sm hover-glow">
                                    <i class="fas fa-plus me-1"></i>Buat Ujian Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">Apakah Anda yakin ingin menghapus ujian ini?</p>
                    <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3"
                        data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-3 shadow-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .hover-glow:hover {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            transition: all 0.3s ease;
        }

        .transition {
            transition: all 0.3s ease-in-out;
        }

        select.form-select {
            cursor: pointer;
        }

        .animate__animated {
            animation-duration: 0.8s;
            animation-fill-mode: both;
        }

        .dropdown-menu {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Filter functionality
                const subjectFilter = document.getElementById('subjectFilter');
                const classFilter = document.getElementById('classFilter');
                const statusFilter = document.getElementById('statusFilter');

                function applyFilters() {
                    const params = new URLSearchParams(window.location.search);
                    params.set('subject_id', subjectFilter.value);
                    params.set('class_id', classFilter.value);
                    params.set('status', statusFilter.value);
                    for (let [key, value] of params) if (!value) params.delete(key);
                    window.location.href = '{{ route("teachers.exams.index") }}?' + params.toString();
                }

                [subjectFilter, classFilter, statusFilter].forEach(f => f.addEventListener('change', applyFilters));
            });

            function confirmDelete(examId) {
                document.getElementById('deleteForm').action = '{{ route("teachers.exams.index") }}/' + examId;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }
        </script>
    @endpush
@endsection