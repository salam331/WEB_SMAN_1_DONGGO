@extends('layouts.app')

@section('page_title', 'Tambah Tagihan')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">

            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Tambah Tagihan Baru
                </h5>
                <a href="{{ route('admin.invoices') }}"
                    class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Body -->
            <div class="card-body bg-light bg-gradient p-4">

                <!-- Error Alerts -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-pill shadow-sm mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Terdapat kesalahan dalam input:
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('admin.invoices.store') }}" method="post" class="p-2">
                    @csrf

                    <div class="mb-4">
                        <label for="student_id" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-user-graduate me-1 text-primary"></i> Siswa <span class="text-danger">*</span>
                        </label>
                        <select name="student_id" id="student_id" class="form-select border-0 shadow-sm rounded-3 p-2"
                            required>
                            <option value="">Pilih Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} - {{ $student->classRoom->name ?? 'Tidak ada kelas' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-money-bill-wave me-1 text-primary"></i> Jumlah (Rp) <span
                                class="text-danger">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0"
                            class="form-control border-0 shadow-sm rounded-3 p-2" placeholder="Masukkan jumlah tagihan"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="due_date" class="form-label fw-semibold text-secondary">
                            <i class="fas fa-calendar-alt me-1 text-primary"></i> Jatuh Tempo <span
                                class="text-danger">*</span>
                        </label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                            class="form-control border-0 shadow-sm rounded-3 p-2" required>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.invoices') }}"
                            class="btn btn-outline-secondary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4 py-2 fw-semibold">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 🎨 Style Tambahan -->
        <style>
            .form-label {
                font-size: 0.95rem;
            }

            .form-control,
            .form-select {
                transition: all 0.2s ease-in-out;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #0d6efd !important;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
            }

            .btn-outline-secondary:hover {
                background: linear-gradient(135deg, #6c757d, #adb5bd);
                color: #fff;
                transform: translateY(-2px);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #0062cc, #007bff);
                transform: translateY(-2px);
            }

            .card {
                transition: box-shadow 0.3s ease, transform 0.2s ease;
            }

            .card:hover {
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
                transform: translateY(-2px);
            }
        </style>
    </div>
@endsection