@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="hero-section bg-primary text-white py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="display-4 fw-bold mb-3">Selamat Datang di SMAN 1 Donggo</h1>
                            <p class="lead mb-4">Sekolah berkualitas dengan pendidikan yang unggul dan berintegritas.</p>
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg me-3">Masuk Sistem</a>
                            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">Tentang Kami</a>
                            <a href="{{ route('public.gallery') }}" class="btn btn-outline-light btn-lg">Galeri Sekolah</a>
                            <a href="{{ route('public.announcements') }}" class="btn btn-outline-light btn-lg">Pengumuman Sekolah</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">Kontak Kami</a>
                        </div>
                        <div class="col-lg-6">
                            <img src="{{ asset('images/school-hero.jpg') }}" alt="School Building" class="img-fluid rounded shadow" onerror="this.src='https://via.placeholder.com/600x400/007bff/ffffff?text=School+Building'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-5">
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h4 class="card-title">Siswa</h4>
                    <p class="card-text">Jumlah siswa aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                    <h4 class="card-title">Guru</h4>
                    <p class="card-text">Tenaga pengajar profesional</p>
                    <h2 class="text-success">{{ $school->total_teachers ?? '50+' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                    <h4 class="card-title">Program</h4>
                    <p class="card-text">Program studi unggulan</p>
                    <h2 class="text-info">6</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                    <h4 class="card-title">Prestasi</h4>
                    <p class="card-text">Penghargaan dan prestasi</p>
                    <h2 class="text-warning">100+</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    @if($announcements->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h3 class="mb-0">
                        <i class="fas fa-bullhorn text-primary me-2"></i>
                        Pengumuman Terbaru
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($announcements as $announcement)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        @if($announcement->pinned)
                                        <i class="fas fa-thumbtack text-warning me-2 mt-1"></i>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-2">{{ $announcement->title }}</h5>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $announcement->created_at->format('d M Y') }}
                                                @if($announcement->postedBy)
                                                • {{ $announcement->postedBy->name }}
                                                @endif
                                            </p>
                                            <p class="card-text">{{ Str::limit($announcement->content, 150) }}</p>
                                            <a href="{{ route('public.announcements') }}" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Features Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Fitur Sistem</h2>
                <p class="lead text-muted">Platform terintegrasi untuk manajemen sekolah modern</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-book-open fa-4x text-primary mb-4"></i>
                    <h4 class="card-title">Materi Pembelajaran</h4>
                    <p class="card-text">Akses materi pembelajaran digital yang terorganisir dengan baik untuk mendukung proses belajar mengajar.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-clipboard-check fa-4x text-success mb-4"></i>
                    <h4 class="card-title">Penilaian & Ujian</h4>
                    <p class="card-text">Sistem penilaian yang transparan dan terintegrasi untuk monitoring perkembangan akademik siswa.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-4x text-info mb-4"></i>
                    <h4 class="card-title">Laporan & Analitik</h4>
                    <p class="card-text">Laporan komprehensif dan analitik data untuk pengambilan keputusan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3">Hubungi Kami</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                            <p>Jl. Pendidikan No. 1, Donggo, Bima</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-phone fa-2x mb-2"></i>
                            <p>(0374) 123456</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-envelope fa-2x mb-2"></i>
                            <p>info@sman1donggo.sch.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
    transform: translateY(-5px);
}
</style>
@endpush
