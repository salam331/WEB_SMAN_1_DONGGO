@extends('layouts.app')

@section('page_title', 'Manajemen Pengumuman')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-bullhorn me-2"></i> Daftar Pengumuman
                </h5>
                <a href="{{ route('admin.announcements.create') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Pengumuman
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Alerts -->
                {{-- @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-pill shadow-sm mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-pill shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif --}}

                <!-- Table Pengumuman -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden"
                        style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: auto">No</th>
                                <th style="width: auto">Judul</th>
                                <th style="width: auto">Konten</th>
                                <th style="width: auto">Status</th>
                                <th style="width: auto">Terbit</th>
                                <th style="width: auto">Target</th>
                                <th style="width: auto">Lampiran</th>
                                <th style="width: auto">Diposting Oleh</th>
                                <th style="width: auto">Tanggal</th>
                                <th style="width: auto">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($announcements as $announcement)
                                <tr class="align-middle border-bottom">
                                    <td class="text-center fw-semibold text-secondary border-end">
                                        {{ $loop->iteration + ($announcements->currentPage() - 1) * $announcements->perPage() }}
                                    </td>
                                    <!-- Judul dengan badge -->
                                    <td class="border-end">
                                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-bullhorn me-1"></i> {{ $announcement->title }}
                                        </span>
                                    </td>
                                    <!-- Konten dengan limit & badge -->
                                    <td class="border-end">
                                        <span class="badge bg-light text-secondary px-3 py-2 rounded-pill shadow-sm">
                                            {{ Str::limit($announcement->content, 50) }}
                                        </span>
                                    </td>
                                    <!-- Status -->
                                    <td class="text-center border-end">
                                        @if($announcement->is_public)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-globe me-1"></i> Publik
                                            </span>
                                        @else
                                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-lock me-1"></i> Privat
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Terbit -->
                                    <td class="text-center border-end">
                                        @if($announcement->is_published)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-eye me-1"></i> Ya
                                            </span>
                                        @else
                                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-eye-slash me-1"></i> Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Target Audience -->
                                    <td class="text-center border-end">
                                        @if($announcement->target_audience)
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-users me-1"></i> {{ ucfirst($announcement->target_audience) }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-users me-1"></i> -
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Lampiran -->
                                    <td class="text-center border-end">
                                        @if($announcement->attachment)
                                            <a href="{{ Storage::url($announcement->attachment) }}" target="_blank"
                                                class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-paperclip me-1"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge bg-light text-secondary px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-paperclip me-1"></i> -
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Diposting oleh -->
                                    <td class="text-center border-end">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-user me-1"></i> {{ $announcement->postedBy->name ?? 'Admin' }}
                                        </span>
                                    </td>
                                    <!-- Tanggal -->
                                    <td class="text-center border-end">
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $announcement->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <!-- Aksi -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-3 py-1 fw-semibold">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Belum ada pengumuman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $announcements->links() }}
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

        .card {
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        /* Responsive table for small screens */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table {
                min-width: 1200px; /* Ensure table doesn't shrink too much */
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