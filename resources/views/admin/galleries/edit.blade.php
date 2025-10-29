@extends('layouts.app')

@section('title', 'Edit Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Galeri</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="title">Judul Galeri <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar</label>
                            @if($gallery->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                <label class="custom-file-label" for="image">{{ $gallery->image ? 'Ganti gambar...' : 'Pilih gambar...' }}</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <img id="imagePreview" src="#" alt="Preview" class="img-fluid d-none" style="max-height: 200px;">
                        </div>

                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror"
                                   id="category" name="category" value="{{ old('category', $gallery->category) }}"
                                   placeholder="Contoh: Kegiatan Sekolah, Olahraga, dll">
                            @error('category')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $gallery->is_public) ? 'checked' : '' }}>
                                <label for="is_public" class="custom-control-label">
                                    Publikasikan galeri ini
                                </label>
                            </div>
                            <small class="form-text text-muted">Jika dicentang, galeri akan ditampilkan di halaman publik.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Update custom file label
$('.custom-file-input').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName || 'Pilih gambar...');
});
</script>
@endpush
