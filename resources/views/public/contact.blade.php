@extends('layouts.public')

@section('title', 'Kontak Kami')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="hero-section bg-primary text-white py-4">
                <div class="container">
                    <div class="text-center">
                        <h1 class="display-5 fw-bold mb-2">Kontak Kami</h1>
                        <p class="mb-0">Hubungi {{ $school->name ?? 'SMAN 1 Donggo' }} untuk informasi lebih lanjut</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Content -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informasi Kontak
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                </h6>
                                <p class="mb-0">{{ $school->address ?? 'Jl. Pendidikan No. 1, Donggo, Kabupaten Bima, Nusa Tenggara Barat' }}</p>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-phone me-2"></i>Telepon
                                </h6>
                                <p class="mb-1">{{ $school->phone ?? '(0374) 123456' }}</p>
                                <small class="text-muted">Jam operasional: Senin - Jumat, 07:00 - 16:00 WITA</small>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </h6>
                                <p class="mb-0">{{ $school->email ?? 'info@sman1donggo.sch.id' }}</p>
                            </div>

                            @if($school->website)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-globe me-2"></i>Website
                                </h6>
                                <p class="mb-0">
                                    <a href="{{ $school->website }}" target="_blank" class="text-decoration-none">{{ $school->website }}</a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-envelope me-2"></i>Kirim Pesan
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="contactForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Pilih subjek</option>
                                        <option value="informasi">Informasi Umum</option>
                                        <option value="pendaftaran">Pendaftaran Siswa</option>
                                        <option value="keluhan">Keluhan/Pengaduan</option>
                                        <option value="kerja-sama">Kerja Sama</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Tuliskan pesan Anda di sini..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marked-alt me-2"></i>Lokasi Sekolah
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.123456789012!2d118.7167!3d-8.4567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOMKwMjcnMjQuMTIiUyAxMTjCsDQzJzAwLjEyIkU!5e0!3m2!1sen!2sid!4v1634567890123!5m2!1sen!2sid"
                                    width="600"
                                    height="450"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Contact Options -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="mb-4">Kontak Cepat</h5>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="tel:{{ $school->phone ?? '0374123456' }}" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="fas fa-phone fa-2x mb-2"></i><br>
                                        <small>Telepon</small>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="mailto:{{ $school->email ?? 'info@sman1donggo.sch.id' }}" class="btn btn-outline-success btn-lg w-100">
                                        <i class="fas fa-envelope fa-2x mb-2"></i><br>
                                        <small>Email</small>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="https://wa.me/{{ str_replace(['(', ')', ' ', '-'], '', $school->phone ?? '6281234567890') }}" target="_blank" class="btn btn-outline-success btn-lg w-100">
                                        <i class="fab fa-whatsapp fa-2x mb-2"></i><br>
                                        <small>WhatsApp</small>
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('login') }}" class="btn btn-outline-info btn-lg w-100">
                                        <i class="fas fa-sign-in-alt fa-2x mb-2"></i><br>
                                        <small>Login Sistem</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="successModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Pesan Terkirim
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Terima kasih atas pesan Anda. Kami akan segera merespons dalam waktu 1-2 hari kerja.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Basic form validation
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Simple validation
    if (!data.name || !data.email || !data.subject || !data.message) {
        alert('Mohon lengkapi semua field yang wajib diisi.');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        alert('Format email tidak valid.');
        return;
    }

    // Show success modal (in real implementation, this would be an AJAX call)
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();

    // Reset form
    this.reset();
});
</script>
@endpush

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0 0 50px 50px;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-info:hover {
    color: white !important;
}
</style>
@endpush
