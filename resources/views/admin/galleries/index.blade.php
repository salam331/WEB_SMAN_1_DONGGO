@extends('layouts.app')

@section('title', 'Kelola Galeri')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-md-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white py-3 rounded-top-4 d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fw-semibold">
                            <i class="fas fa-images me-2"></i> Kelola Galeri
                        </h3>
                        <a href="{{ route('admin.galleries.create') }}" class="btn btn-light btn-sm fw-semibold shadow-sm text-primary rounded-pill">
                            <i class="fas fa-plus me-1 text-primary"></i> Tambah Galeri
                        </a>
                    </div>

                    <div class="card-body bg-light p-4 rounded-bottom-4">
                        {{-- Alert Sukses --}}
                        {{-- @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif --}}

                        {{-- Tabel Galeri --}}
                        <div class="table-responsive shadow-sm rounded-3">
                            <table class="table table-bordered align-middle mb-0 bg-white">
                                <thead class="table-primary text-center">
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
                                            <td class="text-center fw-semibold">
                                                {{ $loop->iteration + ($galleries->currentPage() - 1) * $galleries->perPage() }}
                                            </td>
                                            <td class="text-center">
                                                @if($gallery->image)
                                                    <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}"
                                                        class="rounded shadow-sm"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $gallery->title }}</td>
                                            <td class="text">{{ $gallery->category ?? '-' }}</td>
                                            <td class="text-center">
                                                @if($gallery->is_public)
                                                    <span class="badge bg-success px-3 py-2">Publik</span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2">Privat</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $gallery->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group gap-2" role="group">
                                                    <a href="{{ route('admin.galleries.show', $gallery) }}"
                                                        class="btn btn-info btn-sm shadow-sm" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.galleries.edit', $gallery) }}"
                                                        class="btn btn-warning btn-sm shadow-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.galleries.destroy', $gallery) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus galeri ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fas fa-images fa-3x mb-3"></i>
                                                <h6 class="fw-semibold">Belum ada galeri yang ditambahkan.</h6>
                                                <p>Tambahkan galeri baru untuk menampilkan dokumentasi kegiatan sekolah.</p>
                                                <a href="{{ route('admin.galleries.create') }}"
                                                    class="btn btn-primary btn-sm shadow-sm">
                                                    <i class="fas fa-plus me-1"></i> Tambah Galeri
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($galleries->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $galleries->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection