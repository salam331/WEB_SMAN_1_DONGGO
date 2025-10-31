@extends('layouts.app')

@section('title', 'Pengumuman - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bullhorn text-primary me-2"></i>
                        Pengumuman
                    </h5>
                    <a href="{{ route('teachers.announcements.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Buat Pengumuman
                    </a>
                </div>
                <div class="card-body">
                    @if($announcements->count() > 0)
                        <div class="row g-3">
                            @foreach($announcements as $announcement)
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">{{ $announcement->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $announcement->created_at->format('d F Y H:i') }}
                                                @if($announcement->is_published)
                                                    <span class="badge bg-success ms-2">Published</span>
                                                @else
                                                    <span class="badge bg-warning text-dark ms-2">Draft</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('teachers.announcements.edit', $announcement->id) }}">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a></li>
                                                @if(!$announcement->is_published)
                                                    <li><a class="dropdown-item" href="#" onclick="publishAnnouncement({{ $announcement->id }})">
                                                        <i class="fas fa-paper-plane me-2"></i>Publish
                                                    </a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $announcement->id }})">
                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ Str::limit($announcement->content, 200) }}</p>

                                        @if($announcement->target_audience)
                                            <div class="mb-2">
                                                <small class="text-muted">Target:</small>
                                                <span class="badge bg-info">{{ $announcement->target_audience }}</span>
                                            </div>
                                        @endif

                                        @if($announcement->attachment)
                                            <div class="mb-2">
                                                <small class="text-muted">Lampiran:</small>
                                                <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                                    <i class="fas fa-paperclip me-1"></i>Download
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bullhorn fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Belum Ada Pengumuman</h5>
                            <p class="text-muted">Belum ada pengumuman yang dibuat.</p>
                            <a href="{{ route('teachers.announcements.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Buat Pengumuman Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengumuman ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
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

<script>
function confirmDelete(announcementId) {
    document.getElementById('deleteForm').action = `/teachers/announcements/${announcementId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function publishAnnouncement(announcementId) {
    if (confirm('Apakah Anda yakin ingin mempublish pengumuman ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/teachers/announcements/${announcementId}/publish`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
