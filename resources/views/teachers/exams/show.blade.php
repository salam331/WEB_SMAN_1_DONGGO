@extends('layouts.app')

@section('title', 'Detail Ujian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Ujian</h3>
                    <div class="card-tools">
                        <a href="{{ route('teachers.exams.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('teachers.exams.edit', $exam) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($examResults->count() > 0)
                        <a href="{{ route('teachers.exams.export_pdf', $exam) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        @endif
                        {{-- <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $exam->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nama Ujian</th>
                                    <td>{{ $exam->name }}</td>
                                </tr>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td>{{ $exam->classRoom->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Ujian</th>
                                    <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Mulai</th>
                                    <td>{{ $exam->start_time }}</td>
                                </tr>
                                <tr>
                                    <th>Durasi</th>
                                    <td>{{ $exam->duration }} menit</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $exam->status == 'published' ? 'success' : 'warning' }}">
                                            {{ $exam->status == 'published' ? 'Dipublikasikan' : 'Draft' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Soal</th>
                                    <td>{{ $exam->total_questions ?? 'Belum ditentukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Nilai Kelulusan</th>
                                    <td>{{ $exam->passing_grade ?? 'Belum ditentukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Total Skor</th>
                                    <td>{{ $exam->total_score }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diupdate</th>
                                    <td>{{ $exam->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($exam->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Deskripsi</h5>
                            <p>{{ $exam->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exam->instructions)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Instruksi</h5>
                            <p>{{ $exam->instructions }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Hasil Ujian</h5>
                            @if($examResults->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Siswa</th>
                                                <th>Nilai</th>
                                                <th class="text-center">Status</th>
                                                {{-- <th>Waktu Submit</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($examResults as $result)
                                            <tr>
                                                <td>{{ $result->student->user->name ?? 'N/A' }}</td>
                                                <td>{{ $result->score }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $result->score >= ($exam->passing_grade ?? 0) ? 'success' : 'danger' }}">
                                                        {{ $result->score >= ($exam->passing_grade ?? 0) ? 'Lulus' : 'Tidak Lulus' }}
                                                    </span>
                                                </td>
                                                {{-- <td>{{ $result->submitted_at ? $result->submitted_at->format('d/m/Y H:i') : 'Belum submit' }}</td> --}}
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada hasil ujian.</p>
                            @endif
                        </div>
                    </div>

                    @if($exam->status == 'draft')
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('teachers.exams.input_grades', $exam) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Input Nilai
                            </a>
                        </div>
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
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">Apakah Anda yakin ingin menghapus ujian ini?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
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
function confirmDelete(examId) {
    document.getElementById('deleteForm').action = '{{ route("teachers.exams.index") }}/' + examId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
