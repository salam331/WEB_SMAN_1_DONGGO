@extends('layouts.app')

@section('title', 'Detail Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Orang Tua</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.parents.edit', $parent) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Nama Lengkap:</th>
                                    <td>{{ $parent->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $parent->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon:</th>
                                    <td>{{ $parent->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Hubungan dengan Siswa:</th>
                                    <td>{{ $parent->relation_to_student }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun:</th>
                                    <td>
                                        @if($parent->user->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat:</th>
                                    <td>{{ $parent->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Update:</th>
                                    <td>{{ $parent->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Section -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa ({{ $parent->students->count() }} siswa)</h3>
                </div>

                <div class="card-body">
                    @if($parent->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Kelas</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parent->students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nisn }}</td>
                                        <td>{{ $student->classRoom->name ?? '-' }}</td>
                                        <td>{{ $student->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>
                                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Orang tua ini belum memiliki siswa yang terdaftar.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
