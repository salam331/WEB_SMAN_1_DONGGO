@extends('layouts.app')

@section('title', 'Detail Anak - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg rounded-4 animate-card">
                    <div
                        class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-child me-2"></i> Detail Anak
                        </h5>
                        <small class="fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="card-body p-4 bg-light-subtle">
                        <!-- Informasi Pribadi -->
                        <div class="bg-white rounded-4 shadow-sm p-4 mb-4 border-top border-4 border-primary-subtle">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user-graduate me-2"></i> Informasi Pribadi
                            </h5>
                            <div class="table-responsive">
                                <table class="table custom-table align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th><i class="fas fa-user me-2 text-primary"></i> Nama</th>
                                            <td>{{ $child->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-envelope me-2 text-primary"></i> Email</th>
                                            <td>{{ $child->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-id-card me-2 text-primary"></i> NIS</th>
                                            <td>{{ $child->nis }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-school me-2 text-primary"></i> Kelas</th>
                                            <td>{{ $child->classRoom->name ?? 'Tidak ada' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-birthday-cake me-2 text-primary"></i> Tanggal Lahir</th>
                                            <td>{{ $child->birth_date ? $child->birth_date->format('d/m/Y') : 'Tidak ada' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-map-marker-alt me-2 text-primary"></i> Alamat</th>
                                            <td>{{ $child->address ?? 'Tidak ada' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Wali Kelas -->
                        <div class="bg-white rounded-4 shadow-sm p-4 mb-4 border-top border-4 border-success-subtle">
                            <h5 class="fw-bold text-success mb-3">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Informasi Wali Kelas
                            </h5>
                            <div class="table-responsive">
                                <table class="table custom-table align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th><i class="fas fa-user-tie me-2 text-success"></i> Nama Wali Kelas</th>
                                            <td>{{ $child->classRoom->homeroomTeacher->user->name ?? 'Tidak ada' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-envelope me-2 text-success"></i> Email Wali Kelas</th>
                                            <td>{{ $child->classRoom->homeroomTeacher->user->email ?? 'Tidak ada' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Orang Tua -->
                        <div class="bg-white rounded-4 shadow-sm p-4 mb-4 border-top border-4 border-warning-subtle">
                            <h5 class="fw-bold text-warning mb-3">
                                <i class="fas fa-users me-2"></i> Informasi Orang Tua
                            </h5>
                            <div class="table-responsive">
                                <table class="table custom-table align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th><i class="fas fa-user me-2 text-warning"></i> Nama Orang Tua</th>
                                            <td>{{ $child->parent->name ?? 'Tidak ada' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-users me-2 text-warning"></i> Hubungan dengan Siswa</th>
                                            <td>{{ $child->parent->relation_to_student ?? 'Tidak ada' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-phone me-2 text-warning"></i> Nomor Telepon</th>
                                            <td>{{ $child->parent->phone ?? 'Tidak ada' }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-envelope me-2 text-warning"></i> Email</th>
                                            <td>{{ $child->parent->user->email ?? 'Tidak ada' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tombol Navigasi -->
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('parent.child.attendance', $child->id) }}"
                                class="btn btn-outline-success rounded-pill fw-semibold shadow-sm">
                                <i class="fas fa-calendar-check me-1"></i> Lihat Kehadiran
                            </a>
                            <a href="{{ route('parent.child.grades', $child->id) }}"
                                class="btn btn-outline-warning rounded-pill fw-semibold shadow-sm">
                                <i class="fas fa-star me-1"></i> Lihat Nilai
                            </a>
                            <a href="{{ route('parent.child.invoices', $child->id) }}"
                                class="btn btn-outline-purple rounded-pill fw-semibold shadow-sm"
                                style="border-color:#6f42c1; color:#6f42c1;">
                                <i class="fas fa-file-invoice-dollar me-1"></i> Lihat Tagihan
                            </a>
                            <a href="{{ route('parent.dashboard') }}"
                                class="btn btn-outline-secondary rounded-pill fw-semibold shadow-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Table Style */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .custom-table th {
            background: linear-gradient(135deg, #f8f9fa, #eef2ff);
            color: #495057;
            font-weight: 600;
            padding: 0.85rem 1rem;
            width: 35%;
            border-bottom: 1px solid #dee2e6;
        }

        .custom-table td {
            background: #fff;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f3f5;
        }

        .custom-table tr:last-child th,
        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover td {
            background: #f8f9ff;
            transition: 0.2s ease-in-out;
        }

        /* Button Hover Effects */
        .btn-outline-success:hover {
            background-color: #198754;
            color: #fff;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            color: #fff;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-outline-purple:hover {
            background-color: #6f42c1;
            color: #fff !important;
            box-shadow: 0 6px 20px rgba(111, 66, 193, 0.3);
        }

        /* Shadow hover animation */
        .shadow-sm {
            transition: all 0.3s ease;
        }

        .shadow-sm:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection