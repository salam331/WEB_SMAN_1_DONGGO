@extends('layouts.app')

@section('title', 'Nilai Anak - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-star me-2"></i> Nilai Anak
                        </h5>
                        <small class="fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        <div class="bg-white rounded-4 shadow-sm p-4 mb-4 border-top border-4 border-primary-subtle">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user-graduate me-2"></i> {{ $child->user->name }}
                            </h5>

                            <div class="table-responsive">
                                <table class="table custom-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-book me-2 text-primary"></i> Mata Pelajaran</th>
                                            <th><i class="fas fa-chalkboard me-2 text-success"></i> Kelas</th>
                                            <th><i class="fas fa-graduation-cap me-2 text-warning"></i> Nilai</th>
                                            <th><i class="fas fa-calendar-day me-2 text-info"></i> Tanggal Ujian</th>
                                            <th><i class="fas fa-user-tie me-2 text-purple"></i> Guru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($examResults as $result)
                                            <tr>
                                                <td>{{ $result->exam->subject->name ?? 'Tidak ada' }}</td>
                                                <td>{{ $result->exam->classRoom->name ?? 'Tidak ada' }}</td>
                                                <td>
                                                    <span class="badge rounded-pill px-3 py-2 fw-semibold
                                                        @if($result->score >= 90) bg-success-subtle text-success
                                                        @elseif($result->score >= 75) bg-warning-subtle text-warning
                                                        @else bg-danger-subtle text-danger @endif">
                                                        {{ $result->score }}
                                                    </span>
                                                </td>
                                                <td>{{ $result->exam->exam_date ? $result->exam->exam_date->format('d/m/Y') : 'Tidak ada' }}
                                                </td>
                                                <td>{{ $result->exam->teacher->user->name ?? 'Tidak ada' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fas fa-info-circle me-2"></i> Tidak ada data nilai.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $examResults->links() }}
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('parent.child.detail', $child->id) }}"
                                class="btn btn-outline-primary rounded-pill fw-semibold shadow-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Anak
                            </a>
                            <a href="{{ route('parent.dashboard') }}"
                                class="btn btn-outline-secondary rounded-pill fw-semibold shadow-sm">
                                <i class="fas fa-home me-1"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Table */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .custom-table th {
            background: linear-gradient(135deg, #f8f9fa, #eef2ff);
            color: #495057;
            font-weight: 600;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid #dee2e6;
        }

        .custom-table td {
            background: #fff;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f3f5;
        }

        .custom-table tr:last-child th,
        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover td {
            background: #f8f9ff;
            transition: 0.25s ease-in-out;
        }

        /* Badge Styles */
        .bg-success-subtle {
            background-color: #d1e7dd !important;
            color: #0f5132 !important;
        }

        .bg-warning-subtle {
            background-color: #fff3cd !important;
            color: #664d03 !important;
        }

        .bg-danger-subtle {
            background-color: #f8d7da !important;
            color: #842029 !important;
        }

        /* Button Hover */
        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        /* Card Hover Effect */
        .shadow-sm {
            transition: all 0.3s ease;
        }

        .shadow-sm:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection