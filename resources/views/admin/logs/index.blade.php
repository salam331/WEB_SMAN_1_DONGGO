@extends('layouts.app')

@section('page_title', 'Log Aktivitas')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-list-alt me-2"></i> Log Aktivitas
                </h5>
                <div class="d-flex flex-wrap gap-3 mt-2 mt-md-0">
                    <button onclick="exportLogs('pdf')" class="btn btn-warning rounded-pill shadow-sm">
                        <i class="fas fa-file-pdf me-2"></i> PDF
                    </button>
                    <button onclick="exportLogs('excel')" class="btn btn-success rounded-pill shadow-sm">
                        <i class="fas fa-file-excel me-2"></i> Excel
                    </button>
                    <button onclick="exportLogs('csv')" class="btn btn-danger rounded-pill shadow-sm">
                        <i class="fas fa-file-csv me-2"></i> CSV
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light text-primary rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>


            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">
                <!-- Filter Form -->
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="orang_tua" {{ request('role') == 'orang_tua' ? 'selected' : '' }}>Orang Tua
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Aksi</label>
                            <select name="action" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Semua Aksi</option>
                                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Model</label>
                            <select name="model" class="form-select border-0 shadow-sm rounded-3 p-2">
                                <option value="">Semua Model</option>
                                <option value="User" {{ request('model') == 'User' ? 'selected' : '' }}>User</option>
                                <option value="Teacher" {{ request('model') == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="Student" {{ request('model') == 'Student' ? 'selected' : '' }}>Student</option>
                                <option value="ClassRoom" {{ request('model') == 'ClassRoom' ? 'selected' : '' }}>Class
                                </option>
                                <option value="Subject" {{ request('model') == 'Subject' ? 'selected' : '' }}>Subject</option>
                                <option value="Announcement" {{ request('model') == 'Announcement' ? 'selected' : '' }}>
                                    Announcement</option>
                                <option value="Material" {{ request('model') == 'Material' ? 'selected' : '' }}>Material
                                </option>
                                <option value="Invoice" {{ request('model') == 'Invoice' ? 'selected' : '' }}>Invoice</option>
                                <option value="Attendance" {{ request('model') == 'Attendance' ? 'selected' : '' }}>Attendance
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Nama User</label>
                            <input type="text" name="user_name" value="{{ request('user_name') }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="Cari nama user...">
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Dari Tanggal</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sampai Tanggal</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                class="form-control border-0 shadow-sm rounded-3 p-2">
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Logs Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden"
                        style="border-color: #dee2e6;">
                        <thead class="table-primary text-white"
                            style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                            <tr class="text-center align-middle">
                                <th style="width: auto">No</th>
                                <th style="width: auto">Waktu</th>
                                <th style="width: auto">User</th>
                                <th style="width: auto">Role</th>
                                <th style="width: auto">Aksi</th>
                                <th style="width: auto">Model</th>
                                <th style="width: auto">IP Address</th>
                                <th style="width: 20%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($logs as $log)
                                @php
                                    // Tentukan role efektif
                                    $effectiveRole = $log->role ?? $log->user->role ?? 'User';
                                    // Tentukan warna badge berdasarkan role
                                    $roleColors = [
                                        'admin' => 'bg-danger text-white',
                                        'guru' => 'bg-primary text-white',
                                        'siswa' => 'bg-success text-white',
                                        'orang_tua' => 'bg-warning text-dark',
                                        'User' => 'bg-secondary text-white'
                                    ];
                                    $roleBadgeClass = $roleColors[$effectiveRole] ?? 'bg-secondary text-white';

                                    // Tentukan IP
                                    $ipAddress = $log->ip_address ?? $log->user->last_ip ?? 'Tidak Tersedia';
                                @endphp

                                <tr class="align-middle border-bottom text-center">
                                    <td class="fw-semibold text-secondary border-end">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold text-dark border-end">{{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>

                                    <td class="text-center border-end">
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-user-tag me-1"></i> {{ $log->user->name ?? 'User Tidak Dikenal' }}
                                        </span>
                                    </td>

                                    <td class="border-end">
                                        <span class="badge {{ $roleBadgeClass }} px-3 py-2 rounded-pill shadow-sm">
                                            {{ ucfirst($effectiveRole) }}
                                        </span>
                                    </td>

                                    <td class="border-end">
                                        @php
                                            $actionColors = [
                                                'create' => 'bg-success text-white',
                                                'update' => 'bg-primary text-white',
                                                'delete' => 'bg-danger text-white',
                                                'login' => 'bg-purple text-white',
                                                'logout' => 'bg-secondary text-white',
                                            ];
                                            $actionBadgeClass = $actionColors[$log->action] ?? 'bg-warning text-dark';
                                        @endphp
                                        <span class="badge {{ $actionBadgeClass }} px-3 py-2 rounded-pill shadow-sm">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>

                                    <td class="fw-semibold text-dark border-end">{{ $log->model }}</td>
                                    <td class="fw-semibold text-dark border-end">{{ $ipAddress }}</td>

                                    <td class="text-center border-end">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button onclick="showLogDetail({{ $log->id }})"
                                                class="btn btn-sm btn-outline-info rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                title="Detail Log">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </button>
                                            @if(auth()->user()->hasRole('admin'))
                                                <form method="POST" action="{{ route('admin.logs.destroy', $log) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus log ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3 py-1 fw-semibold"
                                                        title="Hapus Log">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Tidak ada log ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>


                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-end">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Log Detail Modal -->
    <div id="logDetailModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Log</h5>
                    <button type="button" class="btn-close" onclick="closeLogDetailModal()"></button>
                </div>
                <div class="modal-body" id="logDetailContent"></div>
            </div>
        </div>
    </div>

    <script>
        function showLogDetail(logId) {
            fetch(`/admin/logs/${logId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('logDetailContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('logDetailModal')).show();
                })
                .catch(error => {
                    console.error('Error loading log detail:', error);
                    alert('Gagal memuat detail log');
                });
        }

        function closeLogDetailModal() {
            const modalEl = document.getElementById('logDetailModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        }

        function exportLogs(format) {
            const params = new URLSearchParams(window.location.search);
            params.set('export', format);
            const url = `${window.location.pathname}?${params.toString()}`;
            window.open(url, '_blank');
        }
    </script>

    <style>
        .badge.bg-purple {
            background-color: #6f42c1;
            color: #fff;
        }
    </style>
@endsection