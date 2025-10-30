@extends('layouts.app')

@section('title', 'Manajemen Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Orang Tua</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.parents.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Orang Tua
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau telepon..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.parents.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Hubungan dengan Siswa</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parents as $parent)
                                <tr>
                                    <td>{{ $loop->iteration + ($parents->currentPage() - 1) * $parents->perPage() }}</td>
                                    <td>{{ $parent->name }}</td>
                                    <td>{{ $parent->user->email }}</td>
                                    <td>{{ $parent->phone ?? '-' }}</td>
                                    <td>{{ $parent->relation_to_student }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $parent->students->count() }} siswa</span>
                                    </td>
                                    <td>
                                        @if($parent->user->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.parents.show', $parent) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('admin.parents.edit', $parent) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus orang tua ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data orang tua.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($parents->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $parents->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
