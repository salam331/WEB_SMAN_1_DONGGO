@extends('layouts.app')

@section('title', 'Kelola Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelola Galeri</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Galeri
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Gambar</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($galleries as $gallery)
                                    <tr>
                                        <td>{{ $loop->iteration + ($galleries->currentPage() - 1) * $galleries->perPage() }}</td>
                                        <td>
                                            @if($gallery->image)
                                                <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-image fa-2x"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $gallery->title }}</td>
                                        <td>{{ $gallery->category ?? '-' }}</td>
                                        <td>
                                            @if($gallery->is_public)
                                                <span class="badge badge-success">Publik</span>
                                            @else
                                                <span class="badge badge-secondary">Privat</span>
                                            @endif
                                        </td>
                                        <td>{{ $gallery->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.galleries.show', $gallery) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus galeri ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fas fa-images fa-3x mb-3"></i>
                                            <p>Belum ada galeri yang ditambahkan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($galleries->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $galleries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
