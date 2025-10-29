@extends('layouts.app')

@section('title', 'Detail Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Galeri</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($gallery->image)
                                <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" class="img-fluid rounded">
                            @else
                                <div class="text-center p-5 bg-light rounded">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada gambar</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Judul:</th>
                                    <td>{{ $gallery->title }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi:</th>
                                    <td>{{ $gallery->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori:</th>
                                    <td>{{ $gallery->category ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($gallery->is_public)
                                            <span class="badge badge-success">Publik</span>
                                        @else
                                            <span class="badge badge-secondary">Privat</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat:</th>
                                    <td>{{ $gallery->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate:</th>
                                    <td>{{ $gallery->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus galeri ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus Galeri
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
