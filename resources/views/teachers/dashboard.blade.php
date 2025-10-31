@extends('layouts.app')

@section('title', 'SMAN 1 DONGGO - Guru')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-primary text-white py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">
                                <i class="fas fa-tachometer-alt me-2"></i>Selamat Datang, {{ auth()->user()->name }}!
                            </h2>
                            <p class="mb-0 opacity-75">Dashboard Administrator - Sistem Manajemen Sekolah</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <i class="fas fa-calendar-alt fa-2x me-3 opacity-75"></i>
                                <div>
                                    <div class="h6 mb-0">{{ now()->format('l, d F Y') }}</div>
                                    <small class="opacity-75">{{ now()->format('H:i') }} WITA</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-school fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1 text-muted">Total Kelas</h6>
                            <h3 class="mb-0 text-warning">{{ number_format($stats['total_classes']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 p-3 rounded">
                                <i class="fas fa-history fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1 text-muted">Log Hari Ini</h6>
                            <h3 class="mb-0 text-danger">
                                @php
                                    $todayLogs = \App\Models\Log::whereDate('created_at', today())->count();
                                @endphp
                                {{ number_format($todayLogs) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activities -->
    <div class="row mb-4">
        <!-- Quick Actions -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary w-100 p-3 h-auto">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                <span>Tambah Pengguna</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-outline-success w-100 p-3 h-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i><br>
                                <span>Tambah Guru</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.classes.create') }}" class="btn btn-outline-info w-100 p-3 h-auto">
                                <i class="fas fa-school fa-2x mb-2"></i><br>
                                <span>Tambah Kelas</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.announcements.create') }}" class="btn btn-outline-warning w-100 p-3 h-auto">
                                <i class="fas fa-bullhorn fa-2x mb-2"></i><br>
                                <span>Buat Pengumuman</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-1 text-primary">{{ number_format($stats['total_subjects']) }}</div>
                                <small class="text-muted">Mata Pelajaran</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-1 text-info">{{ now()->format('W') }}</div>
                                <small class="text-muted">Minggu Ke</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-1 text-warning">{{ now()->format('m') }}</div>
                                <small class="text-muted">Bulan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Modules -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs text-secondary me-2"></i>Modul Manajemen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- User Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.users') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-users fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Manajemen Pengguna</h6>
                                    <p class="card-text text-muted small">Kelola akun pengguna, role, dan izin akses sistem</p>
                                    <span class="badge bg-primary">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.teachers') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Manajemen Guru</h6>
                                    <p class="card-text text-muted small">Data guru, jadwal mengajar, dan penugasan kelas</p>
                                    <span class="badge bg-success">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- Student Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.students') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user-graduate fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Manajemen Siswa</h6>
                                    <p class="card-text text-muted small">Data siswa, nilai, dan rapor akademik</p>
                                    <span class="badge bg-info">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- Class Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.classes') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-school fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Manajemen Kelas</h6>
                                    <p class="card-text text-muted small">Pengaturan kelas, wali kelas, dan struktur akademik</p>
                                    <span class="badge bg-warning">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.subjects.index') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-book fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Manajemen Mata Pelajaran</h6>
                                    <p class="card-text text-muted small">Kurikulum, silabus, dan standar kompetensi</p>
                                    <span class="badge bg-danger">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.schedules.index') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-calendar-alt fa-2x text-secondary"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Manajemen Jadwal</h6>
                                    <p class="card-text text-muted small">Jadwal pelajaran dan kegiatan sekolah</p>
                                    <span class="badge bg-secondary">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- Announcement Management -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.announcements') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-bullhorn fa-2x text-dark"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Pengumuman</h6>
                                    <p class="card-text text-muted small">Informasi dan berita sekolah</p>
                                    <span class="badge bg-dark">Kelola</span>
                                </div>
                            </div>
                        </div>

                        <!-- System Logs -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100 module-card" onclick="window.location.href='{{ route('admin.logs.dashboard') }}'">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-history fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-2">Log Sistem</h6>
                                    <p class="card-text text-muted small">Riwayat aktivitas dan audit trail</p>
                                    <span class="badge bg-primary">Lihat</span>
                                </div>
                            </div>
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
.module-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click animation to module cards
    const moduleCards = document.querySelectorAll('.module-card');
    moduleCards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});
</script>
@endpush
