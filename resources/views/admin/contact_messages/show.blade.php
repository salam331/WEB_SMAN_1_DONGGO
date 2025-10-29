@extends('layouts.app')

@section('title', 'Detail Pesan Kontak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pesan Kontak</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contact-messages.edit', $contactMessage) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama:</label>
                                <p>{{ $contactMessage->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></p>
                            </div>

                            @if($contactMessage->phone)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Telepon:</label>
                                    <p><a href="tel:{{ $contactMessage->phone }}">{{ $contactMessage->phone }}</a></p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-bold">Subjek:</label>
                                <p>{{ $contactMessage->subject }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Pesan:</label>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($contactMessage->message)) !!}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Kirim:</label>
                                <p>{{ $contactMessage->created_at->format('d M Y H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Status & Aksi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status:</label>
                                        <div>
                                            @if($contactMessage->status == 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($contactMessage->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($contactMessage->status == 'approved' || $contactMessage->status == 'rejected')
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Diproses Oleh:</label>
                                            <p>{{ $contactMessage->approver->name ?? 'N/A' }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tanggal Proses:</label>
                                            <p>{{ $contactMessage->approved_at ? $contactMessage->approved_at->format('d M Y H:i:s') : 'N/A' }}</p>
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

                                    @if($contactMessage->status == 'pending')
                                        <div class="d-grid gap-2">
                                            <form action="{{ route('admin.contact-messages.approve', $contactMessage) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-check"></i> Setujui Pesan
                                                </button>
                                            </form>

                                            <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                <i class="fas fa-times"></i> Tolak Pesan
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($contactMessage->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pesan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.contact-messages.reject', $contactMessage) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Catatan Admin <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" required placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pesan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
