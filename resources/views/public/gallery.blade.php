@extends('layouts.public')

@section('title', 'Galeri')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="hero-section bg-primary text-white py-4">
                <div class="container">
                    <div class="text-center">
                        <h1 class="display-5 fw-bold mb-2">Galeri Sekolah</h1>
                        <p class="mb-0">Kumpulan momen dan kegiatan di {{ $school->name ?? 'SMAN 1 Donggo' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="row mt-5">
        <div class="col-12">
            @if($galleries->count() > 0)
                <div class="row" id="gallery-grid">
                    @foreach($galleries as $gallery)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100 gallery-item" data-bs-toggle="modal" data-bs-target="#galleryModal" data-image="{{ Storage::url($gallery->image) }}" data-title="{{ $gallery->title }}" data-description="{{ $gallery->description }}" data-date="{{ $gallery->created_at->format('d F Y') }}">
                            <div class="card-img-container">
                                <img src="{{ Storage::url($gallery->image) }}" class="card-img-top gallery-image" alt="{{ $gallery->title }}" loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="gallery-overlay-content">
                                        <i class="fas fa-search-plus fa-2x mb-2"></i>
                                        <h6 class="mb-1">{{ $gallery->title }}</h6>
                                        <small>{{ $gallery->created_at->format('d F Y') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{ $gallery->title }}</h6>
                                @if($gallery->description)
                                <p class="card-text text-muted small">{{ Str::limit($gallery->description, 100) }}</p>
                                @endif
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $gallery->created_at->format('d F Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $galleries->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Galeri masih kosong</h4>
                    <p class="text-muted">Foto dan dokumentasi kegiatan akan segera ditampilkan di sini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel">Detail Galeri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded mb-3" style="max-height: 70vh; width: auto;">
                <h5 id="modalTitle"></h5>
                <p id="modalDescription" class="text-muted"></p>
                <small id="modalDate" class="text-muted"></small>
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
document.addEventListener('DOMContentLoaded', function() {
    const galleryModal = document.getElementById('galleryModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalDate = document.getElementById('modalDate');

    galleryModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const image = button.getAttribute('data-image');
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');
        const date = button.getAttribute('data-date');

        modalImage.src = image;
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        modalDate.textContent = date;
    });
});
</script>
@endpush

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0 0 50px 50px;
}

.gallery-item {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
}

.card-img-container {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: white;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay-content {
    text-align: center;
}

@media (max-width: 768px) {
    .card-img-container {
        height: 200px;
    }
}
</style>
@endpush
