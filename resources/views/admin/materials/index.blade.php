@extends('layouts.app')

@section('page_title', 'Manajemen Materi Pembelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book-reader me-2"></i> Daftar Materi Pembelajaran
                </h5>
                <a href="{{ route('admin.materials.create') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Materi
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Filter & Search -->
                <form action="{{ route('admin.materials.index') }}" method="get"
                    class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="input-group w-50 shadow-sm">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-0 rounded-start-pill"
                            placeholder="Cari judul atau deskripsi materi...">
                        <button class="btn btn-primary rounded-end-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <select name="subject_id" class="form-select rounded-pill shadow-sm" style="width: auto;">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" 
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="class_id" class="form-select rounded-pill shadow-sm" style="width: auto;">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" 
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-pill shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-pill shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden"
                        style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: auto">No</th>
                                <th style="width: 30%">Judul</th>
                                <th style="width: 15%">Mata Pelajaran</th>
                                <th style="width: 12%">Kelas</th>
                                <th style="width: 15%">Guru</th>
                                <th style="width: 10%">File</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($materials as $material)
                                <tr class="align-middle border-bottom">
                                    <td class="text-center fw-semibold text-secondary border-end">
                                        {{ $loop->iteration + ($materials->currentPage() - 1) * $materials->perPage() }}
                                    </td>
                                    <td class="fw-semibold text-dark border-end">
                                        <i class="fas fa-file-alt me-2 text-primary"></i>
                                        {{ Str::limit($material->title, 25) }}
                                    </td>
                                    <td class="border-end">
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm">
                                            {{ $material->subject->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center border-end">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-door-open me-1"></i>
                                            {{ $material->classRoom->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="border-end">
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1 text-success"></i>
                                            {{ $material->teacher->user->name ?? '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center border-end">
                                        @if($material->file_path)
                                            <span class="badge bg-success text-white px-2 py-1 rounded-pill shadow-sm">
                                                <i class="fas fa-file me-1"></i> Ada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary text-white px-2 py-1 rounded-pill shadow-sm">
                                                <i class="fas fa-times me-1"></i> Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center border-end">
                                        @if($material->is_published)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-check me-1"></i> Dipublikasi
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-lock me-1"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.materials.show', $material) }}"
                                                class="btn btn-sm btn-outline-info rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>
                                            <a href="{{ route('admin.materials.edit', $material) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                title="Edit Materi">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                    title="Hapus Materi">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Belum ada data materi pembelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $materials->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- 🎨 Style Tambahan -->
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

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #dc3545, #ff4d6d) !important;
            color: #fff !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .btn-outline-info:hover {
            background: linear-gradient(135deg, #0dcaf0, #0d6efd) !important;
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

        .input-group input:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }
    </style>
@endsection
