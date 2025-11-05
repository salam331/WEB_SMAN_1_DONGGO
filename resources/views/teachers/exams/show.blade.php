@extends('layouts.app')

@section('title', 'Detail Ujian')

@section('content')
    <div class="container-fluid py-12">
        <div class="card border-0 shadow-lg rounded-4">
            <div
                class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top-4">
                <h3 class="mb-0 fw-bold">
                    <i class="fas fa-file-alt me-2"></i> Detail Ujian
                </h3>
                <div>
                    <a href="{{ route('teachers.exams.index') }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('teachers.exams.edit', $exam) }}" class="btn btn-warning btn-sm me-2 text-white">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @if($examResults->count() > 0)
                        <a href="{{ route('teachers.exams.export_pdf', $exam) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body bg-light">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-header bg-primary text-white fw-semibold rounded-top-4">
                                <i class="fas fa-clipboard-list me-2"></i> Informasi Ujian
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless align-middle">
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
                                        <td>{{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('d F Y') }}</td>
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
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-header bg-success text-white fw-semibold rounded-top-4">
                                <i class="fas fa-chart-bar me-2"></i> Status & Evaluasi
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless align-middle">
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span
                                                class="badge bg-{{ $exam->status == 'published' ? 'success' : 'warning' }} px-3 py-2 rounded-pill">
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
                                        <th>Dibuat</th>
                                        <td>{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Diperbarui</th>
                                        <td>{{ $exam->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($exam->description || $exam->instructions)
                    <div class="card mt-4 border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            @if($exam->description)
                                <h5 class="fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Deskripsi</h5>
                                <p class="text-muted">{{ $exam->description }}</p>
                            @endif

                            @if($exam->instructions)
                                <h5 class="fw-bold text-primary mt-3"><i class="fas fa-list-ul me-2"></i>Instruksi</h5>
                                <p class="text-muted">{{ $exam->instructions }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card mt-4 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-info text-white fw-semibold rounded-top-4">
                        <i class="fas fa-user-graduate me-2"></i> Hasil Ujian
                    </div>
                    <div class="card-body">
                        @if($examResults->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th>Nama Siswa</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($examResults as $result)
                                            <tr>
                                                <td>{{ $result->student->user->name ?? 'N/A' }}</td>
                                                <td class="text-center fw-semibold">{{ $result->score }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-{{ $result->score >= ($exam->passing_grade ?? 0) ? 'success' : 'danger' }} px-3 py-2 rounded-pill">
                                                        {{ $result->score >= ($exam->passing_grade ?? 0) ? 'Lulus' : 'Tidak Lulus' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Belum ada hasil ujian.</p>
                        @endif
                    </div>
                </div>

                @if($exam->status == 'draft')
                    <div class="text-center mt-4">
                        <a href="{{ route('teachers.exams.input_grades', $exam) }}"
                            class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> Input Nilai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-2 fs-6">Apakah Anda yakin ingin menghapus ujian ini?</p>
                    <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Hapus</button>
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