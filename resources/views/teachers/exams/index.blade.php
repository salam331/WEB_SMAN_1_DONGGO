@extends('layouts.app')

@section('title', 'Kelola Ujian - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Kelola Ujian
                        </h5>
                        <small class="text-muted">Daftar ujian yang telah dibuat</small>
                    </div>
                    <a href="{{ route('teachers.exams.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Buat Ujian Baru
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <select class="form-select" id="subjectFilter">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="classFilter">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Daftar Ujian -->
                    @if($exams->count() > 0)
                        <div class="row g-3">
                            @foreach($exams as $exam)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-1">{{ $exam->title }}</h6>
                                            <span class="badge bg-{{ $exam->status == 'published' ? 'success' : ($exam->status == 'completed' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst($exam->status) }}
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($exam->description, 100) }}</p>

                                        <div class="row g-2 mb-3">
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
                                                <span class="small">{{ $exam->exam_date->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Durasi</small>
                                                <span class="small">{{ $exam->duration }} menit</span>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            @if($exam->status == 'published')
                                                <a href="{{ route('teacher.exams.input_grades', $exam) }}" class="btn btn-success btn-sm">
                                                    <i class="fas fa-graduation-cap me-1"></i>Input Nilai
                                                </a>
                                            @endif
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('teacher.exams.show', $exam) }}">
                                                        <i class="fas fa-eye me-2"></i>Lihat Detail
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('teacher.exams.edit', $exam) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $exam->id }})">
                                                        <i class="fas fa-trash me-2"></i>Hapus
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <small class="text-muted">
                                            Dibuat: {{ $exam->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $exams->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada ujian</h5>
                            <p class="text-muted">Buat ujian pertama Anda untuk memulai</p>
                            <a href="{{ route('teachers.exams.create') }}" class="btn btn-primary">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus ujian ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const subjectFilter = document.getElementById('subjectFilter');
    const classFilter = document.getElementById('classFilter');
    const statusFilter = document.getElementById('statusFilter');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        params.set('subject_id', subjectFilter.value);
        params.set('class_id', classFilter.value);
        params.set('status', statusFilter.value);

        // Remove empty params
        for (let [key, value] of params) {
            if (!value) params.delete(key);
        }

        window.location.href = '{{ route("teachers.exams.index") }}?' + params.toString();
    }

    subjectFilter.addEventListener('change', applyFilters);
    classFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
});

function confirmDelete(examId) {
    document.getElementById('deleteForm').action = '{{ route("teachers.exams.index") }}/' + examId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
