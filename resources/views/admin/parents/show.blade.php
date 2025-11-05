@extends('layouts.app')

@section('page_title', 'Detail Orang Tua')

@section('content')
    <div class="container-fluid">
        <!-- Card Detail -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-user-friends me-2"></i> Detail Orang Tua
                </h5>
                <div>
                    <a href="{{ route('admin.parents.edit', $parent) }}"
                        class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.parents.index') }}"
                        class="btn btn-light btn-sm text-secondary fw-semibold rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body bg-light bg-gradient p-4">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless align-middle">
                            <tbody>
                                <tr>
                                    <th class="text-secondary" width="180">Nama Lengkap:</th>
                                    <td class="fw-semibold text-dark">
                                        <i class="fas fa-user me-2 text-primary"></i> {{ $parent->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Email:</th>
                                    <td class="text-muted">
                                        <i class="fas fa-envelope me-2 text-secondary"></i> {{ $parent->user->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Telepon:</th>
                                    <td class="text-muted">
                                        <i class="fas fa-phone-alt me-2 text-secondary"></i> {{ $parent->phone ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Hubungan dengan Siswa:</th>
                                    <td>
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            {{ $parent->relation_to_student }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Status Akun:</th>
                                    <td>
                                        @if($parent->user->is_active)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-check-circle me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-times-circle me-1"></i> Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Dibuat:</th>
                                    <td class="text-muted">
                                        <i class="fas fa-calendar-plus me-2 text-secondary"></i>
                                        {{ $parent->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-secondary">Terakhir Update:</th>
                                    <td class="text-muted">
                                        <i class="fas fa-sync-alt me-2 text-secondary"></i>
                                        {{ $parent->updated_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <div class="avatar-lg mb-3">
                                <i class="fas fa-user-circle fa-7x text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-1">{{ $parent->name }}</h6>
                            <p class="text-muted mb-2">{{ $parent->relation_to_student }}</p>
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                {{ $parent->students->count() }} siswa terhubung
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Daftar Siswa -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-user-graduate me-2"></i> Daftar Siswa ({{ $parent->students->count() }} siswa)
                </h5>
            </div>

            <div class="card-body bg-light bg-gradient p-4">
                @if($parent->students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden"
                            style="border-color: #dee2e6;">
                            <thead class="table-primary text-white"
                                style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                                <tr class="text-center align-middle">
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($parent->students as $student)
                                    <tr>
                                        <td class="text-center fw-semibold text-secondary">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <i class="fas fa-user me-2 text-primary"></i>
                                            {{ $student->user->name }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                                {{ $student->nis ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill shadow-sm">
                                                {{ $student->nisn ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                                {{ $student->classRoom->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($student->gender == 'male')
                                                <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm">
                                                    <i class="fas fa-mars me-1"></i> Laki-laki
                                                </span>
                                            @else
                                                <span class="badge bg-pink text-white px-3 py-2 rounded-pill shadow-sm"
                                                    style="background-color: #e83e8c !important;">
                                                    <i class="fas fa-venus me-1"></i> Perempuan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.students.show', $student) }}"
                                                class="btn btn-sm btn-outline-info rounded-pill shadow-sm px-3 py-1 fw-semibold">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info shadow-sm rounded-pill px-4 py-3 mb-0 text-center fw-semibold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Orang tua ini belum memiliki siswa yang terdaftar.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 🌈 Style Tambahan -->
    <style>
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: none;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f8ff !important;
            transition: all 0.25s ease-in-out;
        }

        .btn-outline-info:hover {
            background: linear-gradient(135deg, #17a2b8, #00bcd4) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .avatar-lg i {
            border-radius: 50%;
            background: radial-gradient(circle, #e9f2ff 30%, #cfe2ff 100%);
            padding: 25px;
        }

        .alert-info {
            background: #e9f7ff;
            border: 1px solid #b6e0fe;
            color: #0c5460;
        }
    </style>
@endsection