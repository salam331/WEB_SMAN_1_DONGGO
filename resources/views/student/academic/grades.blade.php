@extends('layouts.app')

@section('title', 'Nilai Ujian Saya - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card overflow-hidden">
                    <!-- Header -->
                    <div
                        class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <div>
                            <h4 class="card-title mb-1 fw-bold">
                                <i class="fas fa-graduation-cap me-2"></i> Nilai Ujian Saya
                            </h4>
                            <small class="text-white-50 fw-medium">Lihat hasil dan performa ujian Anda di sini</small>
                        </div>
                        @php
                            $now = now()->setTimezone('Asia/Makassar');
                        @endphp
                        <small class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 bg-light">
                        @if($examResults->count() > 0)
                            <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
                                <table class="table table-bordered align-middle table-hover mb-0">
                                    <thead class="bg-primary text-white text-center">
                                        <tr>
                                            <th style="width:5%">No</th>
                                            <th style="width:25%">Ujian</th>
                                            <th style="width:25%">Mata Pelajaran</th>
                                            <th style="width:15%">Kelas</th>
                                            <th style="width:15%">Nilai</th>
                                            <th style="width:15%">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @foreach($examResults as $i => $result)
                                            @php
                                                $score = $result->score;
                                                $scoreColor = $score >= 90 ? 'success' : ($score >= 75 ? 'info' : ($score >= 60 ? 'warning' : 'danger'));
                                                $scoreIcon = $score >= 90 ? 'fa-star' : ($score >= 75 ? 'fa-check-circle' : ($score >= 60 ? 'fa-exclamation-circle' : 'fa-times-circle'));
                                            @endphp
                                            <tr class="hover-row">
                                                <td class="text-center fw-semibold">{{ $examResults->firstItem() + $i }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle bg-light-primary me-3">
                                                            <i class="fas fa-clipboard-list text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="fw-bold text-dark d-block">{{ $result->exam->name ?? '-' }}</span>
                                                            <small class="text-muted">Tipe:
                                                                {{ ucfirst($result->exam->type ?? 'Ujian') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <i class="fas fa-book text-secondary me-2"></i>
                                                    {{ $result->exam->subject->name ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary-subtle text-dark px-3 py-2 rounded-pill shadow-sm">
                                                        {{ $result->exam->classRoom->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <span
                                                        class="badge bg-{{ $scoreColor }}-subtle text-{{ $scoreColor }} fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                        <i class="fas {{ $scoreIcon }} me-1"></i> {{ $score }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <i class="fas fa-calendar-day text-muted me-1"></i>
                                                    {{ $result->created_at->format('d/m/Y') }}
                                                </td>
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
                                <h5 class="text-muted fw-bold">Belum ada nilai ujian</h5>
                                <p class="text-muted mb-0">Nilai akan muncul di sini setelah guru menginput hasil ujian Anda.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style tambahan -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff, #4f7df3);
        }

        .hover-row:hover {
            background-color: #f3f8ff !important;
            transition: background-color 0.3s ease;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-light-primary {
            background-color: #e8f1ff;
        }

        .bg-success-subtle {
            background-color: #e8f6ef !important;
        }

        .bg-info-subtle {
            background-color: #e8f4fb !important;
        }

        .bg-warning-subtle {
            background-color: #fff8e6 !important;
        }

        .bg-danger-subtle {
            background-color: #fdeaea !important;
        }

        .bg-secondary-subtle {
            background-color: #f2f2f7 !important;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .animate-card {
            animation: fadeUp 0.6s ease-out;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection