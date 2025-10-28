@extends('layouts.public')

@section('title', 'Tentang Kami')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="hero-section bg-primary text-white py-5">
                <div class="container">
                    <div class="text-center">
                        <h1 class="display-4 fw-bold mb-3">Tentang {{ $school->name ?? 'SMAN 1 Donggo' }}</h1>
                        <p class="lead">Membangun Generasi Unggul dengan Pendidikan Berkualitas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Info -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="mb-4">Profil Sekolah</h3>
                            <div class="mb-3">
                                <strong>Nama Sekolah:</strong><br>
                                {{ $school->name ?? 'SMAN 1 Donggo' }}
                            </div>
                            <div class="mb-3">
                                <strong>Alamat:</strong><br>
                                {{ $school->address ?? 'Jl. Pendidikan No. 1, Donggo, Bima' }}
                            </div>
                            <div class="mb-3">
                                <strong>Telepon:</strong><br>
                                {{ $school->phone ?? '(0374) 123456' }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                {{ $school->email ?? 'info@sman1donggo.sch.id' }}
                            </div>
                            <div class="mb-3">
                                <strong>Akreditasi:</strong><br>
                                {{ $school->accreditation ?? 'A' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('images/school-building.jpg') }}" alt="School Building" class="img-fluid rounded shadow" onerror="this.src='https://via.placeholder.com/400x300/007bff/ffffff?text=School+Building'">
                        </div>
                    </div>

                    @if($school && $school->description)
                    <div class="mt-4">
                        <h4>Deskripsi</h4>
                        <p class="text-muted">{{ $school->description }}</p>
                    </div>
                    @endif

                    @if($school && $school->vision)
                    <div class="mt-4">
                        <h4>Visi</h4>
                        <p class="text-muted">{{ $school->vision }}</p>
                    </div>
                    @endif

                    @if($school && $school->mission)
                    <div class="mt-4">
                        <h4>Misi</h4>
                        <p class="text-muted">{{ $school->mission }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Keunggulan Kami</h2>
                <p class="lead text-muted">Komitmen kami dalam memberikan pendidikan terbaik</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-4x text-primary mb-4"></i>
                    <h4 class="card-title">Kurikulum Modern</h4>
                    <p class="card-text">Menggunakan kurikulum terkini yang disesuaikan dengan perkembangan zaman dan kebutuhan industri.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-4x text-success mb-4"></i>
                    <h4 class="card-title">Guru Berkompeten</h4>
                    <p class="card-text">Tenaga pengajar yang profesional, berpengalaman, dan terus mengembangkan kompetensinya.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-flask fa-4x text-info mb-4"></i>
                    <h4 class="card-title">Fasilitas Lengkap</h4>
                    <p class="card-text">Sarana dan prasarana yang memadai untuk mendukung proses belajar mengajar yang optimal.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-trophy fa-4x text-warning mb-4"></i>
                    <h4 class="card-title">Prestasi Gemilang</h4>
                    <p class="card-text">Berbagai prestasi akademik dan non-akademik yang membanggakan di tingkat regional dan nasional.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-handshake fa-4x text-danger mb-4"></i>
                    <h4 class="card-title">Kerjasama</h4>
                    <p class="card-text">Bermitra dengan berbagai institusi pendidikan, perusahaan, dan komunitas untuk pengembangan siswa.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-heart fa-4x text-secondary mb-4"></i>
                    <h4 class="card-title">Nilai Karakter</h4>
                    <p class="card-text">Pendidikan karakter yang kuat untuk membentuk siswa yang berakhlak mulia dan bertanggung jawab.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body py-5">
                    <div class="text-center mb-4">
                        <h3>Statistik Sekolah</h3>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-3 mb-4">
                            <h2 class="display-4 fw-bold">{{ $school->total_students ?? '500' }}</h2>
                            <p class="mb-0">Siswa Aktif</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h2 class="display-4 fw-bold">{{ $school->total_teachers ?? '50' }}</h2>
                            <p class="mb-0">Guru & Staff</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h2 class="display-4 fw-bold">{{ $school->total_classes ?? '24' }}</h2>
                            <p class="mb-0">Kelas</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <h2 class="display-4 fw-bold">{{ $school->total_achievements ?? '100' }}</h2>
                            <p class="mb-0">Prestasi</p>
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
