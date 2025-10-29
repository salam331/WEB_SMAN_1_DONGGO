@extends('layouts.app')

@section('title', 'Edit Pesan Kontak')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Kartu Utama -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fw-semibold">
                            <i class="fas fa-edit me-2"></i> Edit Pesan Kontak
                        </h3>
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-light btn-sm text-primary rounded-pill fw-semibold">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <!-- Form Edit Pesan -->
                    <form action="{{ route('admin.contact-messages.update', $contactMessage) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">Nama <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $contactMessage->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $contactMessage->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-bold">Telepon</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $contactMessage->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label fw-bold">Subjek <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('subject') is-invalid @enderror" id="subject"
                                            name="subject" required>
                                            <option value="">Pilih Subjek</option>
                                            <option value="Informasi Umum" {{ old('subject', $contactMessage->subject) == 'Informasi Umum' ? 'selected' : '' }}>Informasi
                                                Umum</option>
                                            <option value="Pendaftaran Siswa" {{ old('subject', $contactMessage->subject) == 'Pendaftaran Siswa' ? 'selected' : '' }}>
                                                Pendaftaran Siswa</option>
                                            <option value="Kerjasama" {{ old('subject', $contactMessage->subject) == 'Kerjasama' ? 'selected' : '' }}>Kerja Sama
                                            </option>
                                            <option value="Komplain" {{ old('subject', $contactMessage->subject) == 'Komplain' ? 'selected' : '' }}>Komplain</option>
                                            <option value="Lainnya" {{ old('subject', $contactMessage->subject) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-bold">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="pending" {{ old('status', $contactMessage->status) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="approved" {{ old('status', $contactMessage->status) == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="rejected" {{ old('status', $contactMessage->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pesan -->
                            <div class="mb-3">
                                <label for="message" class="form-label fw-bold">Pesan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message"
                                    name="message" rows="5"
                                    required>{{ old('message', $contactMessage->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catatan Admin -->
                            <div class="mb-3">
                                <label for="admin_notes" class="form-label fw-bold">Catatan Admin</label>
                                <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes"
                                    name="admin_notes" rows="3"
                                    placeholder="Tambahkan catatan admin jika diperlukan">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>
                                @error('admin_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="card-footer bg-light d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </div>
    </div>
@endsection