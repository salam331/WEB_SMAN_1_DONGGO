@extends('layouts.app')

@section('title', 'Edit Galeri')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Galeri</h4>
                        <a href="{{ route('admin.galleries.index') }}"
                            class="btn btn-light btn-sm fw-semibold shadow-sm text-primary rounded-pill">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Notifikasi Error --}}
                            {{-- @if($errors->any())
                                <div class="alert alert-danger border-0 shadow-sm rounded-3">
                                    <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan:</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}

                            {{-- Judul --}}
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Judul Galeri <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="title" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $gallery->title) }}" placeholder="Masukkan judul galeri..."
                                    required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Deskripsi</label>
                                <textarea id="description" name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Tuliskan deskripsi singkat galeri...">{{ old('description', $gallery->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gambar --}}
                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">Gambar Galeri</label>

                                @if($gallery->image)
                                    <div class="mb-2 text-center">
                                        <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}"
                                            class="img-fluid rounded shadow-sm border" style="max-height: 200px;">
                                    </div>
                                    <p class="text-muted text-center small mb-3">Biarkan kosong jika tidak ingin mengganti
                                        gambar.</p>
                                @endif

                                <div class="input-group">
                                    <input type="file" id="image" name="image"
                                        class="form-control @error('image') is-invalid @enderror" accept="image/*"
                                        onchange="previewImage(this)">
                                    <label class="input-group-text bg-primary text-white" for="image">
                                        <i class="fas fa-upload"></i>
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">Format diperbolehkan: JPG, PNG, GIF (maks.
                                    2MB)</small>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="mt-3 text-center">
                                    <img id="imagePreview" src="#" alt="Preview Gambar"
                                        class="img-fluid rounded-3 shadow-sm d-none" style="max-height: 200px;">
                                </div>
                            </div>

                            {{-- Kategori --}}
                            <div class="mb-3">
                                <label for="category" class="form-label fw-semibold">Kategori</label>
                                <input type="text" id="category" name="category"
                                    class="form-control @error('category') is-invalid @enderror"
                                    value="{{ old('category', $gallery->category) }}"
                                    placeholder="Contoh: Kegiatan Sekolah, Olahraga, dll">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Publikasi --}}
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" {{ old('is_public', $gallery->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_public">
                                    Publikasikan galeri ini
                                </label>
                                <div class="form-text">Jika dicentang, galeri akan muncul di halaman publik.</div>
                            </div>
                        </div>

                        <div
                            class="card-footer d-flex justify-content-end gap-2 align-items-center bg-light rounded-bottom">
                            <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview Gambar
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    preview.classList.add('fade-in');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Animasi halus
        document.addEventListener('DOMContentLoaded', () => {
            const css = document.createElement('style');
            css.textContent = `
                    .fade-in {
                        animation: fadeIn 0.6s ease-in-out forwards;
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; transform: scale(0.98); }
                        to { opacity: 1; transform: scale(1); }
                    }
                `;
            document.head.appendChild(css);
        });
    </script>
@endpush