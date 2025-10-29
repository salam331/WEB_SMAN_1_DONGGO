@extends('layouts.app')

@section('title', 'Kelola Profil Sekolah')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div
                        class="card-header bg-primary text-white py-3 rounded-top-4 d-flex align-items-center justify-content-between">
                        <h3 class="mb-0 fw-semibold">
                            <i class="fas fa-school me-2"></i> Kelola Profil Sekolah
                        </h3>
                        @if($school)
                            <a href="{{ route('admin.school-profiles.edit', $school->id) }}"
                                class="btn btn-light btn-sm fw-semibold shadow-sm text-primary rounded-pill">
                                <i class="fas fa-edit me-1 text-primary"></i> Edit Profil
                            </a>
                        @endif
                    </div>

                    <div class="card-body p-4 bg-light">
                        {{-- Alert Sukses --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Jika Profil Sekolah Ada --}}
                        @if($school)
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-8">
                                    <h4 class="fw-bold text-primary mb-1">{{ $school->name }}</h4>
                                    <p class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $school->address }}
                                    </p>
                                    <p class="mb-0"><strong>Kepala Sekolah:</strong> {{ $school->headmaster_name }}</p>
                                    <p class="mb-0"><strong>Akreditasi:</strong>
                                        <span class="badge bg-success">{{ $school->accreditation ?? 'Belum Diisi' }}</span>
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                                    @if($school->logo ?? false)
                                        <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo Sekolah"
                                            class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-4">
                                {{-- Informasi Dasar --}}
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-header bg-primary text-white rounded-top-4">
                                            <h5 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                            </h5>
                                        </div>
                                        <div class="card-body bg-white">
                                            <table class="table table-sm mb-0">
                                                <tr>
                                                    <td><strong>Nama Sekolah</strong></td>
                                                    <td>{{ $school->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alamat</strong></td>
                                                    <td>{{ $school->address }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Telepon</strong></td>
                                                    <td>{{ $school->phone ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email</strong></td>
                                                    <td>{{ $school->email ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kepala Sekolah</strong></td>
                                                    <td>{{ $school->headmaster_name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Akreditasi</strong></td>
                                                    <td>{{ $school->accreditation ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Informasi Halaman About --}}
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-header bg-primary text-white rounded-top-4">
                                            <h5 class="mb-0 fw-semibold"><i class="fas fa-file-alt me-2"></i>Konten Halaman
                                                About</h5>
                                        </div>
                                        <div class="card-body bg-white">
                                            <table class="table table-sm mb-0">
                                                <tr>
                                                    <td><strong>Hero Title</strong></td>
                                                    <td>{{ $school->hero_title ?? 'Tidak ada' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Hero Description</strong></td>
                                                    <td>{{ $school->hero_description ?? 'Tidak ada' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Deskripsi Sekolah</strong></td>
                                                    <td>{{ $school->school_description ? Str::limit($school->school_description, 80) : 'Tidak ada' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jumlah Fitur</strong></td>
                                                    <td>{{ $school->features ? count($school->features) : 0 }} item</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jumlah Statistik</strong></td>
                                                    <td>{{ $school->statistics ? count($school->statistics) : 0 }} item</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Jika Profil Belum Ada --}}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-circle fa-4x text-muted mb-3"></i>
                                <h5 class="text-secondary">Belum ada profil sekolah yang dibuat.</h5>
                                <p class="text-muted mb-3">Silakan buat profil sekolah terlebih dahulu untuk menampilkan data di
                                    halaman publik.</p>
                                <a href="{{ route('admin.school-profiles.create') }}" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-plus-circle me-1"></i> Buat Profil Sekolah
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection