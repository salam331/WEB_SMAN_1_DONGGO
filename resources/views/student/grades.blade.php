@extends('layouts.app')

@section('title', 'Nilai Ujian Saya - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-graduation-cap me-2"></i> Nilai Ujian Saya
                    </h5>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    @if($examResults->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Ujian</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Nilai</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examResults as $i => $result)
                                        <tr>
                                            <td>{{ $examResults->firstItem() + $i }}</td>
                                            <td>{{ $result->exam->name ?? '-' }}</td>
                                            <td>{{ $result->exam->subject->name ?? '-' }}</td>
                                            <td>{{ $result->exam->classRoom->name ?? '-' }}</td>
                                            <td><span class="fw-bold text-primary">{{ $result->score }}</span></td>
                                            <td>{{ $result->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $examResults->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada nilai ujian</h5>
                            <p class="text-muted">Nilai ujian akan muncul di sini setelah guru menginput nilai.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
