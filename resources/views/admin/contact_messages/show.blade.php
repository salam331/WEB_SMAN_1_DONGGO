@extends('layouts.app')

@section('title', 'Detail Pesan Kontak')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Kartu Utama -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fw-semibold">
                            <i class="fas fa-envelope-open-text me-2"></i> Detail Pesan Kontak
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.contact-messages.edit', $contactMessage) }}"
                                class="btn btn-warning btn-sm me-2 rounded-pill">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-light btn-sm fw-semibold text-primary rounded-pill">
                                <i class="fas fa-arrow-left text-primary"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom Kiri: Detail Pesan -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama:</label>
                                    <p class="mb-0">{{ $contactMessage->name }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email:</label>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
                                    </p>
                                </div>

                                @if($contactMessage->phone)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Telepon:</label>
                                        <p class="mb-0">
                                            <a href="tel:{{ $contactMessage->phone }}">{{ $contactMessage->phone }}</a>
                                        </p>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Subjek:</label>
                                    <p class="mb-0">{{ $contactMessage->subject }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pesan:</label>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($contactMessage->message)) !!}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Kirim:</label>
                                    <p class="mb-0 text-muted">
                                        {{ $contactMessage->created_at->format('d M Y H:i:s') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Status & Aksi -->
                            <div class="col-md-4">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0 fw-semibold">
                                            <i class="fas fa-cogs me-2 text-secondary"></i>Status & Aksi
                                        </h5>
                                    </div>

                                    <div class="card-body">
                                        <!-- Status Pesan -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status:</label>
                                            <div>
                                                @if($contactMessage->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @elseif($contactMessage->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Detail Pemrosesan -->
                                        @if(in_array($contactMessage->status, ['approved', 'rejected']))
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Diproses Oleh:</label>
                                                <p class="mb-0">{{ $contactMessage->approver->name ?? 'N/A' }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tanggal Proses:</label>
                                                <p class="mb-0">
                                                    {{ $contactMessage->approved_at ? $contactMessage->approved_at->format('d M Y H:i:s') : 'N/A' }}
                                                </p>
                                            </div>

                                            @if($contactMessage->admin_notes)
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Catatan Admin:</label>
                                                    <div class="border rounded p-2 bg-light">
                                                        {!! nl2br(e($contactMessage->admin_notes)) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Tombol Aksi untuk Status Pending -->
                                        @if($contactMessage->status === 'pending')
                                            <div class="d-grid gap-2">
                                                <form action="{{ route('admin.contact-messages.approve', $contactMessage) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="fas fa-check me-1"></i> Setujui Pesan
                                                    </button>
                                                </form>

                                                <button class="btn btn-danger w-100" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal">
                                                    <i class="fas fa-times me-1"></i> Tolak Pesan
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End Kolom Kanan -->
                        </div>
                    </div>
                </div>
                <!-- End Kartu Utama -->
            </div>
        </div>
    </div>

    <!-- Modal Penolakan -->
    @if($contactMessage->status === 'pending')
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">
                            <i class="fas fa-times-circle me-2"></i> Tolak Pesan
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('admin.contact-messages.reject', $contactMessage) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="admin_notes" class="form-label fw-bold">
                                    Catatan Admin <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" required
                                    placeholder="Berikan alasan penolakan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i> Tolak Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection