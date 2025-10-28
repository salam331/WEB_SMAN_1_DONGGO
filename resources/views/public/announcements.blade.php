@extends('layouts.public')

@section('title', 'Pengumuman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="hero-section bg-primary text-white py-4">
                <div class="container">
                    <div class="text-center">
                        <h1 class="display-5 fw-bold mb-2">Pengumuman Sekolah</h1>
                        <p class="mb-0">Informasi penting dan berita terbaru dari {{ $school->name ?? 'SMAN 1 Donggo' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            @if($announcements->count() > 0)
                <div class="row">
                    @foreach($announcements as $announcement)
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    @if($announcement->pinned)
                                    <div class="me-3">
                                        <i class="fas fa-thumbtack text-warning fa-lg"></i>
                                    </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h4 class="card-title mb-2">{{ $announcement->title }}</h4>
                                        <div class="d-flex align-items-center text-muted small mb-3">
                                            <i class="fas fa-calendar me-2"></i>
                                            <span>{{ $announcement->created_at->format('l, d F Y H:i') }}</span>
                                            @if($announcement->postedBy)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-user me-1"></i>
                                            <span>{{ $announcement->postedBy->name }}</span>
                                            @endif
                                            @if($announcement->pinned)
                                            <span class="badge bg-warning text-dark ms-2">Disematkan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card-text mb-3">
                                    {!! nl2br(e(Str::limit($announcement->content, 300))) !!}
                                    @if(strlen($announcement->content) > 300)
                                    <span class="text-muted">...</span>
                                    @endif
                                </div>

                                @if($announcement->attachment)
                                <div class="mb-3">
                                    <i class="fas fa-paperclip me-2"></i>
                                    <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-download me-1"></i>Lampiran
                                    </a>
                                </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Dibaca {{ $announcement->views ?? 0 }} kali
                                    </small>
                                    <a href="#" class="btn btn-outline-primary btn-sm" onclick="showAnnouncement({{ $announcement->id }})">
                                        <i class="fas fa-eye me-1"></i>Baca Lengkap
                                    </a>
                                </div>
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
                    <h4 class="text-muted">Belum ada pengumuman</h4>
                    <p class="text-muted">Pengumuman terbaru akan segera dipublikasikan di sini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="announcementModalLabel">Detail Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="announcementContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showAnnouncement(id) {
    // This would typically make an AJAX call to get full announcement content
    // For now, we'll just show an alert
    alert('Fitur detail pengumuman akan segera diimplementasikan.');
}
</script>
@endpush

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0 0 50px 50px;
}
.card {
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endpush
