@extends('layouts.app')

@section('page_title', 'Manajemen Tagihan')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-file-invoice-dollar me-2"></i> Daftar Tagihan Siswa
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Tagihan
                </a>
                <a href="{{ route('admin.invoices.export') }}" class="btn btn-light btn-sm text-success fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.invoices.report') }}" class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-chart-line me-1"></i> Laporan
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body bg-light bg-gradient p-4">

            <!-- Filter Form -->
            <form method="GET" class="mb-4 d-flex flex-wrap gap-3 align-items-end justify-content-end">
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Kelas</label>
                    <select name="class_id" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Kelas</option>
                        @foreach(\App\Models\ClassRoom::all() as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Status</label>
                    <select name="status" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                    </select>
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Bulan</label>
                    <select name="month" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="w-auto">
                    <label class="form-label fw-semibold text-secondary">Tahun</label>
                    <select name="year" class="form-select shadow-sm rounded-pill border-0">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                <table class="table table-hover table-bordered align-middle mb-0" style="border-color: #dee2e6;">
                    <thead class="table-primary text-white" style="background: linear-gradient(135deg, #0d6efd, #007bff); border-bottom: 2px solid #0d6efd;">
                        <tr class="text-center align-middle">
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jumlah</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($invoices as $invoice)
                            <tr class="align-middle text-center border-bottom">
                                <td class="fw-semibold text-secondary">{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                                <td class="fw-semibold text-dark">{{ $invoice->student->user->name }}</td>
                                <td>{{ $invoice->student->classRoom->name ?? '-' }}</td>
                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-success text-white',
                                            'unpaid' => 'bg-warning text-dark',
                                            'partial' => 'bg-info text-dark',
                                            'default' => 'bg-danger text-white'
                                        ];
                                    @endphp
                                    <span class="badge px-3 py-2 rounded-pill shadow-sm {{ $statusColors[$invoice->status] ?? $statusColors['default'] }}">
                                        @if($invoice->status == 'paid') Sudah Dibayar
                                        @elseif($invoice->status == 'unpaid') Belum Dibayar
                                        @elseif($invoice->status == 'partial') Sebagian
                                        @else {{ ucfirst($invoice->status) }} @endif
                                    </span>
                                </td>
                                <td class="text-center border-end">
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-user-tag me-1"></i>{{ $invoice->createdBy->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        @if($invoice->status != 'paid')
                                            <form action="{{ route('admin.invoices.markPaid', $invoice) }}" method="post" class="d-inline">
                                                @csrf @method('put')
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill shadow-sm" onclick="return confirm('Tandai tagihan ini sebagai dibayar?')">
                                                    <i class="fas fa-check me-1"></i> Dibayar
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="post" class="d-inline" onsubmit="return confirm('Hapus tagihan ini?')">
                                            @csrf @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm">
                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2 text-primary"></i> Tidak ada data tagihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $invoices->links() }}
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

        .btn-outline-success:hover {
            background: linear-gradient(135deg, #28a745, #198754) !important;
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

        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0062cc, #007bff) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
</div>
@endsection
