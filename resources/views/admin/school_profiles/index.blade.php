@extends('layouts.app')

@section('title', 'Kelola Profil Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelola Profil Sekolah</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($school)
                        <div class="row">
                            <div class="col-md-8">
                                <h4>{{ $school->name }}</h4>
                                <p class="text-muted">{{ $school->address }}</p>
                                <p><strong>Kepala Sekolah:</strong> {{ $school->headmaster_name }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.school-profiles.edit', $school->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Profil
                                </a>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informasi Dasar</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nama Sekolah:</strong></td>
                                        <td>{{ $school->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat:</strong></td>
                                        <td>{{ $school->address }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telepon:</strong></td>
                                        <td>{{ $school->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $school->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kepala Sekolah:</strong></td>
                                        <td>{{ $school->headmaster_name }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Konten Halaman About</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Hero Title:</strong></td>
                                        <td>{{ $school->hero_title ?? 'Tidak ada' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hero Description:</strong></td>
                                        <td>{{ $school->hero_description ?? 'Tidak ada' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Deskripsi Sekolah:</strong></td>
                                        <td>{{ Str::limit($school->school_description, 50) ?? 'Tidak ada' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fitur:</strong></td>
                                        <td>{{ $school->features ? count($school->features) : 0 }} item</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statistik:</strong></td>
                                        <td>{{ $school->statistics ? count($school->statistics) : 0 }} item</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <p>Belum ada profil sekolah yang dibuat.</p>
                            <a href="#" class="btn btn-primary">Buat Profil Sekolah</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
