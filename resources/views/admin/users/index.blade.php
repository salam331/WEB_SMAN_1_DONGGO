@extends('layouts.app')

@section('page_title', 'Manajemen Users')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-users me-2"></i> Daftar Pengguna
                </h5>
                <a href="{{ route('admin.users.create') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-user-plus me-1"></i> Buat User
                </a>
            </div>

            {{-- Form pencarian
            <form action="{{ route('admin.users') }}" method="get"
                class="mb-4 d-flex justify-content-end align-items-center flex-wrap gap-2">

                <div class="w-auto">
                    <select name="roles" class="form-select border-2 shadow-sm rounded-pill px-3 py-2">
                        <option value="">Semua Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ request('roles') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group w-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control border-0 shadow-sm rounded-start-pill px-3 py-2"
                        placeholder="Cari nama atau email pengguna...">
                    <button type="submit" class="btn btn-primary rounded-end-pill px-4 fw-semibold shadow-sm">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </form> --}}

            <div class="card-body bg-light bg-gradient p-4">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden"
                        style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: auto;">No</th>
                                <th class="width: auto">Nama</th>
                                <th class="width: auto">Email</th>
                                <th class="width: auto">Roles</th>
                                <th class="width: auto">Status</th>
                                <th style="width: 18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($users as $u)
                                <tr class="align-middle border-bottom">
                                    <td class="text-center fw-semibold text-secondary border-end">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td class="fw-semibold text-dark border-end">{{ $u->name }}</td>
                                    <td class="text-muted border-end">{{ $u->email }}</td>
                                    <td class="text-center border-end">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-user-tag me-1"></i>
                                            {{ $u->roles->pluck('name')->join(', ') ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center border-end">
                                        @if($u->is_active)
                                            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-check-circle me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-times-circle me-1"></i> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.users.edit', $u) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                title="Edit User">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus user ini?')">
                                                @csrf @method('delete')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                    title="Hapus User">
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
                                        Belum ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- 🎨 Style tambahan agar selaras dengan halaman Buat User -->
    <style>
        .table thead th {
            color: #726161 !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border: none;
        }

        .table tbody tr:hover {
            background-color: #f0fff7 !important;
            transition: all 0.2s ease-in-out;
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
    </style>
    <style>
        .table-bordered th,
        .table-bordered td {
            border-color: #dee2e6 !important;
        }

        .table thead th {
            border-color: rgba(255, 255, 255, 0.2) !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f7ff !important;
            transition: all 0.25s ease-in-out;
        }

        .badge {
            font-size: 0.85rem;
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
    </style>
@endsection