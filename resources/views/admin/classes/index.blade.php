@extends('layouts.app')

@section('page_title', 'Manajemen Kelas')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Daftar Kelas
                </h5>
                <a href="{{ route('admin.classes.create') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Kelas
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Filter & Search -->
                <form action="{{ route('admin.classes') }}" method="get"
                    class="mb-4 d-flex justify-content-end gap-2">
                    <div class="w-auto">
                        <select name="level" class="form-select border-2 border-2 shadow-sm rounded-pill">
                            <option value="">Semua Level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                    Kelas {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group w-50">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-0 shadow-sm rounded-start-pill" placeholder="Cari kelas atau wali kelas...">
                        <button class="btn btn-primary rounded-end-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Cari
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
                                <th style="width: auto">Nama Kelas</th>
                                <th style="width: auto">Level</th>
                                <th style="width: auto">Kapasitas</th>
                                <th style="width: auto">Jumlah Siswa</th>
                                <th style="width: auto">Wali Kelas</th>
                                <th style="width: 18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($classes as $c)
                                <tr class="align-middle border-bottom">
                                    <td class="text-center fw-semibold text-secondary border-end">
                                        {{ $loop->iteration + ($classes->currentPage() - 1) * $classes->perPage() }}
                                    </td>
                                    <td class="fw-semibold text-dark border-end">
                                        <i class="fas fa-chalkboard me-2 text-primary"></i> {{ $c->name }}
                                    </td>
                                    <td class="text-center border-end">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-layer-group me-1"></i> {{ $c->level }}
                                        </span>
                                    </td>
                                    <td class="text-center border-end">
                                        <span class="badge bg-light text-secondary px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-users me-1"></i> {{ $c->capacity }}
                                        </span>
                                    </td>
                                    <td class="text-center border-end">
                                        <span
                                            class="badge {{ $c->students_count >= $c->capacity ? 'bg-warning text-dark' : 'bg-success text-white' }} px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-user-graduate me-1"></i>
                                            {{ $c->students_count }} / {{ $c->capacity }}
                                        </span>
                                    </td>
                                    <td class="text-center border-end">
                                        @if($c->homeroomTeacher)
                                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-user-tie me-1"></i>
                                                {{ $c->homeroomTeacher->user->name }}
                                            </span>
                                            @if($c->homeroomTeacher->nip)
                                                <div class="small text-muted mt-1">
                                                    NIP: {{ $c->homeroomTeacher->nip }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.classes.edit', $c) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                title="Edit Kelas">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.classes.destroy', $c) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                    title="Hapus Kelas">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Belum ada data kelas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $classes->links() }}
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
    </style>
@endsection