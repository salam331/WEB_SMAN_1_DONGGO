@extends('layouts.public')

@section('title', 'Kontak Kami')

@section('content')
    <div class="container-fluid">

        <!-- 🌈 HERO SECTION -->
        <section class="hero-section text-white py-5 mt-4 mb-4 rounded-5 shadow-lg position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-attachment: fixed;">
            <div class="container text-center position-relative" style="z-index: 2;">
                <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInDown">
                    Hubungi {{ $school->name ?? 'SMAN 1 Donggo' }}
                </h1>
                <p class="lead animate__animated animate__fadeInUp">
                    Kami siap menjawab pertanyaan dan membantu Anda mendapatkan informasi yang dibutuhkan
                </p>
            </div>
            <div class="hero-overlay"></div>
        </section>

        <!-- 📞 CONTACT SECTION -->
        <section class="fade-section mt-5">
            <div class="row">
                <!-- Informasi Kontak -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-primary text-white rounded-top-4">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kontak</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>Alamat</h6>
                                <p>{{ $school->address ?? 'Jl. Pendidikan No. 1, Donggo, Kabupaten Bima, Nusa Tenggara Barat' }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-phone me-2"></i>Telepon</h6>
                                <p class="mb-1">{{ $school->phone ?? '(0374) 123456' }}</p>
                                <small class="text-muted">Senin - Jumat, 07:00 - 16:00 WITA</small>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-envelope me-2"></i>Email</h6>
                                <p>{{ $school->email ?? 'info@sman1donggo.sch.id' }}</p>
                            </div>

                            @if($school->website)
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3"><i class="fas fa-globe me-2"></i>Website</h6>
                                    <a href="{{ $school->website }}" target="_blank"
                                        class="text-decoration-none">{{ $school->website }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Formulir Kontak -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-success text-white rounded-top-4">
                            <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Kirim Pesan</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="contactForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
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
                                    <label for="subject" class="form-label">Subjek <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Pilih subjek</option>
                                        <option value="informasi">Informasi Umum</option>
                                        <option value="pendaftaran">Pendaftaran Siswa</option>
                                        <option value="keluhan">Keluhan / Pengaduan</option>
                                        <option value="kerja-sama">Kerja Sama</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required
                                        placeholder="Tuliskan pesan Anda di sini..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 🗺️ LOKASI SEKOLAH -->
        <section id="location" class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2 text-danger"></i> Lokasi Sekolah
                    </h2>
                    <p class="text-muted">Temukan lokasi SMAN 1 Donggo melalui peta interaktif di bawah ini.</p>
                </div>

                <div class="card-body p-0">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.398222386579!2d118.60558147410514!3d-8.42696359160214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dca749685e7eabb%3A0x60ea19c9cc8d0917!2sSMA%20Negeri%201%20Donggo!5e0!3m2!1sid!2sid!4v1730170639162!5m2!1sid!2sid"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>


        <!-- ⚡ KONTAK CEPAT -->
        <section class="fade-section mt-5 mb-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <h4 class="fw-bold mb-4">Kontak Cepat</h4>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="tel:{{ $school->phone ?? '0374123456' }}" class="btn btn-outline-primary btn-lg w-100">
                                <i class="fas fa-phone fa-2x mb-2"></i><br><small>Telepon</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="mailto:{{ $school->email ?? 'info@sman1donggo.sch.id' }}"
                                class="btn btn-outline-success btn-lg w-100">
                                <i class="fas fa-envelope fa-2x mb-2"></i><br><small>Email</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="https://wa.me/{{ str_replace(['(', ')', ' ', '-'], '', $school->phone ?? '6281234567890') }}"
                                target="_blank" class="btn btn-outline-success btn-lg w-100">
                                <i class="fab fa-whatsapp fa-2x mb-2"></i><br><small>WhatsApp</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-info btn-lg w-100">
                                <i class="fas fa-sign-in-alt fa-2x mb-2"></i><br><small>Login Sistem</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ✅ SUCCESS MODAL -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-success fw-bold"><i class="fas fa-check-circle me-2"></i>Pesan Terkirim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p>Terima kasih atas pesan Anda. Kami akan segera merespons dalam 1–2 hari kerja.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            z-index: 1;
        }

        .fade-section {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.9s ease, transform 0.9s ease;
        }

        .fade-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .card {
            transition: all 0.35s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 1.5rem 2rem rgba(0, 0, 0, 0.12) !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-info:hover {
            color: #fff !important;
        }

        .hero-section {
            background-attachment: fixed !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('.fade-section').forEach(section => observer.observe(section));
        });

        document.getElementById('contactForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            if (!data.name || !data.email || !data.subject || !data.message) {
                alert('Mohon lengkapi semua field wajib.');
                return;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.email)) {
                alert('Format email tidak valid.');
                return;
            }
            new bootstrap.Modal('#successModal').show();
            this.reset();
        });
    </script>
@endpush