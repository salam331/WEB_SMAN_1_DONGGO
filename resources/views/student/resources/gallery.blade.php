@extends('layouts.app')

@section('title', 'Galeri Sekolah - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-images me-2"></i> Galeri Sekolah
                    </h5>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    @if($galleries->count() > 0)
                        <div class="row g-4">
                            @foreach($galleries as $gallery)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-3 hover-lift">
                                        <div class="card-body p-0">
                                            @if($gallery->image)
                                                <div class="gallery-image-container">
                                                    <img src="{{ asset('storage/' . $gallery->image) }}"
                                                         alt="{{ $gallery->title }}"
                                                         class="gallery-image w-100 rounded-top-3"
                                                         style="height: 200px; object-fit: cover; cursor: pointer;"
                                                         onclick="openImageModal('{{ asset('storage/' . $gallery->image) }}', '{{ $gallery->title }}', '{{ $gallery->description ?? '' }}')">
                                                </div>
                                            @else
                                                <div class="gallery-image-container bg-light d-flex align-items-center justify-content-center rounded-top-3" style="height: 200px;">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                            @endif

                                            <div class="p-3">
                                                <h6 class="card-title fw-bold text-primary mb-2">{{ $gallery->title }}</h6>

                                                @if($gallery->description)
                                                    <p class="card-text text-muted mb-2" style="font-size: 0.9rem;">
                                                        {{ Str::limit($gallery->description, 80) }}
                                                    </p>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i> {{ $gallery->created_at->format('d/m/Y') }}
                                                    </small>
                                                    @if($gallery->image)
                                                        <button class="btn btn-sm btn-outline-primary" onclick="openImageModal('{{ asset('storage/' . $gallery->image) }}', '{{ $gallery->title }}', '{{ $gallery->description ?? '' }}')">
                                                            <i class="fas fa-eye me-1"></i> Lihat
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $galleries->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada galeri sekolah</h5>
                            <p class="text-muted">Galeri sekolah akan muncul di sini setelah admin mengupload foto kegiatan sekolah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Detail Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded mb-3" style="max-height: 70vh;">
                <h6 id="modalTitle" class="fw-bold text-primary"></h6>
                <p id="modalDescription" class="text-muted"></p>
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, title, description) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalDescription').textContent = description;

    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
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

.gallery-image {
    transition: transform 0.3s ease;
}

.gallery-image:hover {
    transform: scale(1.05);
}

.gallery-image-container {
    overflow: hidden;
    border-radius: 0.375rem 0.375rem 0 0;
}
</style>
@endsection
