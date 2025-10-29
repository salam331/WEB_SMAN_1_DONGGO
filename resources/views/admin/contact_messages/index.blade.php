@extends('layouts.app')

@section('title', 'Kelola Pesan Kontak')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-envelope-open-text me-2"></i> Kelola Pesan Kontak
                        </h4>
                        {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm fw-semibold text-primary rounded-pill">
                            <i class="fas fa-arrow-left me-1 text-primary"></i> Kembali
                        </a> --}}
                    </div>

                    <div class="card-body bg-light">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle shadow-sm bg-white rounded-3">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Subjek</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($messages as $message)
                                        <tr class="text-center">
                                            <td class="fw-semibold text-start">{{ $message->name }}</td>
                                            <td>{{ $message->email }}</td>
                                            <td>{{ $message->subject }}</td>
                                            <td>
                                                @if($message->status == 'pending')
                                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
                                                        <i class="fas fa-hourglass-half me-1"></i> Menunggu
                                                    </span>
                                                @elseif($message->status == 'approved')
                                                    <span class="badge rounded-pill bg-success px-3 py-2">
                                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger px-3 py-2">
                                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $message->created_at->translatedFormat('d M Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.contact-messages.show', $message) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.contact-messages.edit', $message) }}"
                                                        class="btn btn-warning btn-sm text-white">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.contact-messages.destroy', $message) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin ingin menghapus pesan ini?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                Belum ada pesan kontak.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $messages->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Setujui -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-semibold"><i class="fas fa-check-circle me-2"></i> Setujui Pesan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <p class="fw-medium text-muted mb-0">Apakah Anda yakin ingin menyetujui pesan ini?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-semibold"><i class="fas fa-times-circle me-2"></i> Tolak Pesan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label fw-semibold">Catatan Admin</label>
                            <textarea class="form-control shadow-sm" id="admin_notes" name="admin_notes" rows="3"
                                placeholder="Tuliskan alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #6610f2);
        }

        table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f8ff;
            transition: all 0.2s ease-in-out;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Approve
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const form = document.getElementById('approveForm');
                    form.action = `/admin/contact-messages/${id}/approve`;
                    new bootstrap.Modal(document.getElementById('approveModal')).show();
                });
            });

            // Modal Reject
            document.querySelectorAll('.reject-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const form = document.getElementById('rejectForm');
                    form.action = `/admin/contact-messages/${id}/reject`;
                    new bootstrap.Modal(document.getElementById('rejectModal')).show();
                });
            });
        });
    </script>
@endpush