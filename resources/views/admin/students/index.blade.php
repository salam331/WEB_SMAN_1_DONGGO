@extends('layouts.app')

@section('page_title', 'Manajemen Siswa')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-user-graduate me-2"></i> Daftar Siswa
                </h5>
                <a href="{{ route('admin.students.create') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Siswa
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Form Pencarian -->
                <form method="GET" class="mb-4 d-flex flex-wrap gap-3 align-items-end justify-content-end">
                    <div class="w-auto">
                        <label class="form-label fw-semibold text-secondary">Kelas</label>
                        <select name="class_id" class="form-select shadow-sm rounded-pill border-0">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group w-50">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-0 shadow-sm rounded-start-pill"
                            placeholder="Cari nama atau NIS siswa...">
                        <button class="btn btn-primary rounded-end-pill px-4 fw-semibold shadow-sm">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </form>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden"
                        style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: auto">No</th>
                                <th style="width: auto">Nama</th>
                                <th style="width: auto">Email</th>
                                <th style="width: auto">NIS</th>
                                <th style="width: auto">Kelas</th>
                                <th style="width: 18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($students as $student)
                                <tr class="align-middle border-bottom">
                                    <td class="text-center fw-semibold text-secondary border-end">
                                        {{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}
                                    </td>
                                    <td class="fw-semibold text-dark border-end">
                                        <i class="fas fa-user me-2 text-primary"></i>
                                        {{ $student->user->name ?? '-' }}
                                    </td>
                                    <td class="text-muted border-end">
                                        <i class="fas fa-envelope me-2 text-secondary"></i>
                                        {{ $student->user->email ?? '-' }}
                                    </td>
                                    <td class="border-end text-center">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-id-card me-1"></i> {{ $student->nis ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center border-end">
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-school me-1"></i> {{ $student->classRoom->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.students.edit', $student) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                title="Edit Siswa">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Hapus data siswa ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                    title="Hapus Siswa">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>
                                        Belum ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $students->links() }}
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

        .table-bordered th,
        .table-bordered td {
            border-color: #dee2e6 !important;
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

        /* Responsive table for small screens */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table {
                min-width: 800px;
            }

            .table th, .table td {
                white-space: nowrap;
                padding: 0.5rem;
            }

            .table td .badge {
                display: block;
                margin-bottom: 0.25rem;
            }

            .table td .d-flex {
                flex-direction: column;
                gap: 0.25rem;
            }

            .table td .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
    </style>
@endsection