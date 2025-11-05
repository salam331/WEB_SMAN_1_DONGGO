@extends('layouts.app')

@section('title', 'Tagihan Pembayaran - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Tagihan Pembayaran
                        </h5>
                        @php
                            $now = now()->setTimezone('Asia/Makassar');
                        @endphp
                        <small class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        @if($invoices->count() > 0)
                            <div class="row g-4 justify-content-center">
                                @foreach($invoices as $invoice)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 border-0 rounded-4 shadow-hover position-relative overflow-hidden">
                                            <!-- Decorative Gradient Header -->
                                            <div class="subject-header"
                                                style="height: 64px; background: linear-gradient(135deg, #{{ substr(md5($invoice->title), 0, 6) }}, #764ba2);">
                                            </div>

                                            <!-- Card Content -->
                                            <div class="card-body bg-white position-relative p-4"
                                                style="margin-top: -60px; border-radius: 20px;">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="icon-wrapper me-3">
                                                        <i class="fas fa-file-invoice text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-primary mb-1">{{ $invoice->title }}</h6>
                                                        <small class="text-muted">No. Rekening:
                                                            <span class="text-success fw-bold">BNI: 1650839083</span>
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Jumlah</small>
                                                        <span class="fw-bold text-success">Rp
                                                            {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Jatuh Tempo</small>
                                                        <span
                                                            class="fw-bold {{ $invoice->due_date < now() && $invoice->status != 'paid' ? 'text-danger' : 'text-dark' }}">
                                                            {{ $invoice->due_date->format('d/m/Y') }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <small class="text-muted d-block">Status</small>
                                                    @if($invoice->status == 'paid')
                                                        <span class="badge bg-success fs-6 px-3 py-2">
                                                            <i class="fas fa-check-circle me-1"></i> Lunas
                                                        </span>
                                                    @elseif($invoice->status == 'pending')
                                                        <span class="badge bg-warning fs-6 px-3 py-2">
                                                            <i class="fas fa-clock me-1"></i> Menunggu
                                                        </span>
                                                    @elseif($invoice->status == 'overdue')
                                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                                            <i class="fas fa-exclamation-triangle me-1"></i> Terlambat
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary fs-6 px-3 py-2">
                                                            <i class="fas fa-question-circle me-1"></i> {{ ucfirst($invoice->status) }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $invoice->createdBy->name ?? 'Admin' }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $invoice->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>

                                                @if($invoice->description)
                                                    <p class="text-muted small mb-0">
                                                        <i class="fas fa-info-circle me-1 text-secondary"></i>
                                                        {{ Str::limit($invoice->description, 100) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $invoices->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted fw-bold">Belum ada tagihan pembayaran</h5>
                                <p class="text-muted small">Tagihan akan muncul di sini setelah admin menambah data tagihan untuk Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth fade-in animation */
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

        /* Hover elevation effect */
        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Gradient header for each card */
        .subject-header {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            opacity: 0.9;
        }

        /* Icon bubble styling */
        .icon-wrapper {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(118, 75, 162, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* Animation for card */
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

        /* Consistent badge sizing */
        .badge {
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
@endsection
