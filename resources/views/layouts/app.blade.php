<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SMAN 1 DONGGO')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f8fafc;
            color: #1f2937;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Layout Wrapper */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            padding: 1.5rem;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1030;
        }

        .sidebar h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .sidebar nav ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            margin-bottom: 6px;
        }

        .sidebar a i {
            width: 22px;
            text-align: center;
            font-size: 16px;
            margin-right: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            transform: translateX(3px);
        }

        /* Main Content */
        main {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            background: #f9fafb;
            transition: all 0.3s ease;
            min-height: 100vh;
            overflow-y: auto;
        }

        /* Header */
        #mainHeader {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1050;
            transition: transform 0.4s ease, opacity 0.4s ease;
            will-change: transform, opacity;
        }

        #mainHeader.hide {
            transform: translateY(-100%);
            opacity: 0;
        }

        /* Notifikasi Toast - Container untuk toast notification */
        .toast-container {
            position: fixed;
            /* Posisi tetap di layar */
            top: 20px;
            /* Jarak dari atas layar */
            right: 20px;
            /* Jarak dari kanan layar, dekat area logout */
            z-index: 1060;
            /* Pastikan di atas elemen lain */
            pointer-events: none;
            /* Tidak mengganggu klik di bawahnya */
        }

        /* Styling untuk toast notification */
        .toast-notification {
            background: #28a745;
            /* Warna hijau untuk success */
            color: white;
            /* Teks putih */
            padding: 12px 16px;
            /* Padding dalam toast */
            border-radius: 8px;
            /* Sudut melengkung */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            /* Bayangan untuk efek timbul */
            margin-bottom: 10px;
            /* Jarak antar toast jika ada beberapa */
            font-size: 14px;
            /* Ukuran font */
            font-weight: 500;
            /* Ketebalan font */
            opacity: 0;
            /* Mulai transparan */
            transform: translateY(-20px);
            /* Mulai dari atas */
            transition: all 0.3s ease;
            /* Animasi smooth */
            pointer-events: auto;
            /* Izinkan klik pada toast */
            max-width: 300px;
            /* Lebar maksimal */
            word-wrap: break-word;
            /* Pecah kata panjang */
        }

        /* Toast untuk error - warna merah */
        .toast-notification.error {
            background: #dc3545;
            /* Warna merah untuk error */
        }

        /* Animasi muncul (fade-in dan slide-down) */
        .toast-notification.show {
            opacity: 1;
            /* Tampilkan penuh */
            transform: translateY(0);
            /* Geser ke posisi normal */
        }

        /* Animasi hilang (fade-out dan slide-up) */
        .toast-notification.hide {
            opacity: 0;
            /* Sembunyikan */
            transform: translateY(-20px);
            /* Geser ke atas */
        }

        /* Icon di dalam toast */
        .toast-notification i {
            margin-right: 8px;
            /* Jarak antara icon dan teks */
        }

        /* Tombol close jika diperlukan (opsional) */
        .toast-close {
            float: right;
            /* Posisi di kanan */
            font-size: 18px;
            /* Ukuran font */
            line-height: 1;
            /* Tinggi baris */
            color: rgba(255, 255, 255, 0.8);
            /* Warna putih transparan */
            cursor: pointer;
            /* Kursor pointer */
            margin-left: 10px;
            /* Jarak dari teks */
        }

        .toast-close:hover {
            color: white;
            /* Warna putih saat hover */
        }

        /* Logout Button */
        .btn-logout {
            background: #dc3545;
            color: white;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.875rem;
            transition: background 0.2s ease;
        }

        .btn-logout:hover {
            background: #bb2d3b;
        }

        /* Responsive Sidebar */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.show {
                left: 0;
            }

            main {
                margin-left: 0;
            }

            .toggle-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: #0d6efd;
                color: white;
                cursor: pointer;
                margin-right: 1rem;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1050;
            transition: transform 0.35s ease, opacity 0.35s ease;
            will-change: transform, opacity;
        }

        .sticky-header.hide {
            transform: translateY(-100%);
            opacity: 0;
        }

        @media (max-width: 992px) {
            .sticky-header {
                border-radius: 0;
                padding: 0.75rem 1rem;
            }
        }

        .sidebar-logo {
            width: 50px;
            /* Sesuaikan ukuran logo */
            height: 50px;
            object-fit: contain;
            /* Supaya proporsional */
        }
    </style>
</head>

<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header d-flex align-items-center mb-3">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="sidebar-logo me-2">
                <h2 class="mb-0">SMAN 1 DONGGO</h2>
            </div>
            <nav>
                <ul>
                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <ul class="sidebar-menu">

                                {{-- ===== DASHBOARD ===== --}}
                                <li class="menu-header">Dashboard</li>
                                <li>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard Utama</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.logs.dashboard') }}"
                                        class="{{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                                        <i class="fas fa-history"></i> Dashboard Aktifitas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.public.dashboard') }}"
                                        class="{{ request()->routeIs('admin.public.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-globe"></i> Dashboard Publik
                                    </a>
                                </li>

                                {{-- ===== DATA PENGGUNA ===== --}}
                                <li class="menu-header">Manajemen Data</li>
                                <li>
                                    <a href="{{ route('admin.users') }}"
                                        class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                        <i class="fas fa-users"></i> Data Pengguna
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.teachers') }}"
                                        class="{{ request()->routeIs('admin.teachers*') ? 'active' : '' }}">
                                        <i class="fas fa-chalkboard-teacher"></i> Data Guru
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.students') }}"
                                        class="{{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                                        <i class="fas fa-user-graduate"></i> Data Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.parents.index') }}"
                                        class="{{ request()->routeIs('admin.parents*') ? 'active' : '' }}">
                                        <i class="fas fa-user-friends"></i> Data Orang Tua
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.classes') }}"
                                        class="{{ request()->routeIs('admin.classes*') ? 'active' : '' }}">
                                        <i class="fas fa-school"></i> Data Kelas
                                    </a>
                                </li>

                                {{-- ===== AKADEMIK ===== --}}
                                <li class="menu-header">Akademik</li>
                                <li>
                                    <a href="{{ route('admin.subjects.index') }}"
                                        class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                                        <i class="fas fa-book"></i> Mata Pelajaran
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.materials.index') }}"
                                        class="{{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
                                        <i class="fas fa-book-reader"></i> Materi Pembelajaran
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.schedules.index') }}"
                                        class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
                                    </a>
                                </li>

                                {{-- ===== KEHADIRAN ===== --}}
                                <li class="menu-header">Kehadiran</li>
                                <li>
                                    <a href="{{ route('admin.attendances.index') }}"
                                        class="{{ request()->routeIs('admin.attendances.index') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check"></i> Data Kehadiran
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.attendances.summary') }}"
                                        class="{{ request()->routeIs('admin.attendances.summary') ? 'active' : '' }}">
                                        <i class="fas fa-chart-bar"></i> Ringkasan Kehadiran
                                    </a>
                                </li>

                                {{-- ===== KEUANGAN ===== --}}
                                <li class="menu-header">Keuangan</li>
                                <li>
                                    <a href="{{ route('admin.invoices') }}"
                                        class="{{ request()->routeIs('admin.invoices*') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice-dollar"></i> Tagihan
                                    </a>
                                </li>

                                {{-- ===== INFORMASI & LOG ===== --}}
                                <li class="menu-header">Informasi</li>
                                <li>
                                    <a href="{{ route('admin.school-profiles.index') }}"
                                        class="{{ request()->routeIs('admin.school-profiles*') ? 'active' : '' }}">
                                        <i class="fas fa-school"></i> Profil Sekolah
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.announcements') }}"
                                        class="{{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                                        <i class="fas fa-bullhorn"></i> Pengumuman
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.galleries.index') }}"
                                        class="{{ request()->routeIs('admin.galleries*') ? 'active' : '' }}">
                                        <i class="fas fa-images"></i> Galeri
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.contact-messages.index') }}"
                                        class="{{ request()->routeIs('admin.contact-messages*') ? 'active' : '' }}">
                                        <i class="fas fa-envelope"></i> Pesan Kontak
                                    </a>
                                </li>

                            </ul>
                        @endif


                        @if(auth()->user()->hasRole('guru'))
                            <li>
                                <a href="{{ route('teachers.dashboard') }}"
                                    class="{{ request()->routeIs('teachers.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home"></i> Dashboard Guru
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.classes') }}"
                                    class="{{ request()->routeIs('teachers.classes*') ? 'active' : '' }}">
                                    <i class="fas fa-school"></i> Kelas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.attendances') }}"
                                    class="{{ request()->routeIs('teachers.attendances*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check"></i> Absensi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.schedules') }}"
                                    class="{{ request()->routeIs('teachers.schedules*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.materials.index') }}"
                                    class="{{ request()->routeIs('teachers.materials*') ? 'active' : '' }}">
                                    <i class="fas fa-book-reader"></i> Materi Pembelajaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.subjects.index') }}"
                                    class="{{ request()->routeIs('teachers.subjects*') ? 'active' : '' }}">
                                    <i class="fas fa-book"></i> Mata Pelajaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.announcements') }}"
                                    class="{{ request()->routeIs('teachers.announcements*') ? 'active' : '' }}">
                                    <i class="fas fa-bullhorn"></i> Pengumuman
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('teachers.messages') }}"
                                    class="{{ request()->routeIs('teachers.messages*') ? 'active' : '' }}">
                                    <i class="fas fa-envelope"></i> Pesan Kontak
                                </a>
                            </li> --}}
                            <li>
                                <a href="{{ route('teachers.notifications') }}"
                                    class="{{ request()->routeIs('teachers.notifications*') ? 'active' : '' }}">
                                    <i class="fas fa-bell"></i> Notifikasi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.grades') }}"
                                    class="{{ request()->routeIs('teachers.grades*') ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap"></i> Nilai
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('teachers.exams.index') }}"
                                    class="{{ request()->routeIs('teachers.exams*') ? 'active' : '' }}">
                                    <i class="fas fa-file-alt"></i> Ujian
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasRole('siswa'))
                            <li>
                                <a href="{{ route('student.dashboard') }}"
                                    class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard Siswa
                                </a>
                            </li>
                            {{-- ===== AKADEMIK ===== --}}
                            <li class="menu-header">Akademik</li>
                            <li>
                                <a href="{{ route('student.schedule') }}"
                                    class="{{ request()->routeIs('student.schedule') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('student.attendance') }}"
                                    class="{{ request()->routeIs('student.attendance') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check"></i> Riwayat Absensi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('student.grades') }}"
                                    class="{{ request()->routeIs('student.grades') ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap"></i> Nilai
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('student.materials') }}"
                                    class="{{ request()->routeIs('student.materials*') ? 'active' : '' }}">
                                    <i class="fas fa-book-reader"></i> Materi Pembelajaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('student.announcements') }}"
                                    class="{{ request()->routeIs('student.announcements') ? 'active' : '' }}">
                                    <i class="fas fa-bullhorn"></i> Pengumuman
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('student.invoices') }}"
                                    class="{{ request()->routeIs('student.invoices') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice-dollar"></i> Tagihan
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('student.library') }}"
                                    class="{{ request()->routeIs('student.library*') ? 'active' : '' }}">
                                    <i class="fas fa-book"></i> Perpustakaan
                                </a>
                            </li> --}}
                            {{-- <li>
                                <a href="{{ route('student.gallery') }}"
                                    class="{{ request()->routeIs('student.gallery') ? 'active' : '' }}">
                                    <i class="fas fa-images"></i> Galeri
                                </a>
                            </li> --}}
                            {{-- <li>
                                <a href="{{ route('student.messages') }}"
                                    class="{{ request()->routeIs('student.messages*') ? 'active' : '' }}">
                                    <i class="fas fa-envelope"></i> Pesan
                                </a>
                            </li> --}}
                            {{-- <li>
                                <a href="{{ route('student.notifications') }}"
                                    class="{{ request()->routeIs('student.notifications*') ? 'active' : '' }}">
                                    <i class="fas fa-bell"></i> Notifikasi
                                </a>
                            </li> --}}
                        @endif

                        @if(auth()->user()->hasRole('orang_tua'))
                            <li>
                                <a href="{{ route('parent.dashboard') }}"
                                    class="{{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home"></i> Dashboard Orang Tua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('parent.announcements') }}"
                                    class="{{ request()->routeIs('parent.announcements') ? 'active' : '' }}">
                                    <i class="fas fa-bullhorn"></i> Pengumuman
                                </a>
                            </li>
                            @if(auth()->user()->parent && auth()->user()->parent->students->count() > 0)
                                @foreach(auth()->user()->parent->students as $child)
                                    <li class="menu-header">Anak: {{ $child->user->name }}</li>
                                    <li>
                                        <a href="{{ route('parent.child.detail', $child->id) }}"
                                            class="{{ request()->routeIs('parent.child.detail') && request()->route('childId') == $child->id ? 'active' : '' }}">
                                            <i class="fas fa-user"></i> Detail {{ $child->user->name }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('parent.child.attendance', $child->id) }}"
                                            class="{{ request()->routeIs('parent.child.attendance') && request()->route('childId') == $child->id ? 'active' : '' }}">
                                            <i class="fas fa-calendar-check"></i> Kehadiran {{ $child->user->name }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('parent.child.grades', $child->id) }}"
                                            class="{{ request()->routeIs('parent.child.grades') && request()->route('childId') == $child->id ? 'active' : '' }}">
                                            <i class="fas fa-graduation-cap"></i> Nilai {{ $child->user->name }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('parent.child.invoices', $child->id) }}"
                                            class="{{ request()->routeIs('parent.child.invoices') && request()->route('childId') == $child->id ? 'active' : '' }}">
                                            <i class="fas fa-file-invoice-dollar"></i> Tagihan {{ $child->user->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        @endif
                    @endauth
                </ul>
            </nav>
        </aside>


        <!-- Main -->
        <main>
            <header id="mainHeader"
                class="d-flex flex-column flex-lg-row align-items-center justify-content-between bg-primary text-white px-4 py-3 shadow-sm rounded mb-4">
                <div class="d-flex align-items-center mb-2 mb-lg-0">
                    <div class="toggle-btn d-lg-none me-3" onclick="toggleSidebar()">
                        <i class="fas fa-bars fa-lg"></i>
                    </div>
                    <div>
                        <h2 class="h5 fw-bold mb-1">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Selamat Datang, {{ auth()->user()->name }}!
                        </h2>
                        <p class="mb-0 small opacity-75">Dashboard Khusus {{ auth()->user()->name }} - Sistem Manajemen
                            Sekolah</p>
                    </div>
                </div>

                <div class="d-flex align-items-center text-end">
                    <i class="fas fa-calendar-alt fa-2x me-3 opacity-75"></i>
                    <div class="me-4">
                        @php
                            $now = now()->setTimezone('Asia/Makassar');
                        @endphp

                        <div class="fw-semibold">{{ $now->translatedFormat('l, d F Y') }}</div>
                        <small class="opacity-75">{{ $now->format('H:i') }} WITA</small>
                    </div>

                    @auth
                        <div class="d-flex align-items-center">
                            <span class="text-sm me-3">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-light btn-sm text-danger fw-semibold rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Container untuk toast notification - posisi fixed di kanan atas -->
            <div class="toast-container" id="toastContainer">
                <!-- Toast akan ditambahkan secara dinamis oleh JavaScript -->
            </div>

            <div class="mt-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk menampilkan toast notification
        // Parameter: message (string) - pesan yang akan ditampilkan
        // Parameter: type (string) - jenis toast, bisa 'success' atau 'error'
        function showToast(message, type = 'success') {
            // Ambil container toast dari DOM
            const container = document.getElementById('toastContainer');

            // Buat elemen toast baru
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type === 'error' ? 'error' : ''}`;

            // Tentukan icon berdasarkan jenis toast
            const icon = type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle';

            // Isi HTML toast dengan icon dan pesan
            toast.innerHTML = `<i class="fas ${icon}"></i>${message}`;

            // Tambahkan toast ke container
            container.appendChild(toast);

            // Paksa reflow untuk memulai animasi
            toast.offsetHeight;

            // Tambahkan class 'show' untuk memulai animasi muncul
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Setelah 1 detik, mulai animasi hilang
            setTimeout(() => {
                toast.classList.add('hide');

                // Hapus elemen toast dari DOM setelah animasi selesai
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300); // Durasi animasi fade-out
            }, 1000); // Waktu tampil sebelum hilang
        }

        // Fungsi untuk toggle sidebar (tidak berubah)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        }

        // Header hide/show saat scroll (tidak berubah)
        let lastScrollTop = 0;
        const header = document.getElementById('mainHeader');
        const threshold = 80; // jarak minimal scroll agar efek aktif

        window.addEventListener('scroll', function () {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop && scrollTop > threshold) {
                header.classList.add('hide');
            } else if (scrollTop > lastScrollTop - 5) {
                header.classList.remove('hide');
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        const sidebar = document.getElementById('sidebar');

        // Restore posisi scroll saat load (tidak berubah)
        window.addEventListener('load', () => {
            const scrollPos = localStorage.getItem('sidebarScroll');
            if (scrollPos) sidebar.scrollTop = parseInt(scrollPos);

            // Cek apakah ada session success atau error untuk ditampilkan sebagai toast
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
        });

        // Simpan posisi scroll setiap saat sidebar digulir (tidak berubah)
        sidebar.addEventListener('scroll', () => {
            localStorage.setItem('sidebarScroll', sidebar.scrollTop);
        });
    </script>
    <script>
        // Header hide/show saat scroll (script kedua, tidak berubah)
        let lastScrollY = window.scrollY;
        const header = document.getElementById("mainHeader");

        window.addEventListener("scroll", () => {
            const currentScrollY = window.scrollY;

            // Jangan sembunyikan saat di paling atas
            if (currentScrollY <= 0) {
                header.classList.remove("hide");
                return;
            }

            // Jika scroll ke bawah → sembunyikan
            if (currentScrollY > lastScrollY && currentScrollY > 80) {
                header.classList.add("hide");
            }
            // Jika scroll ke atas → tampilkan kembali
            else if (currentScrollY < lastScrollY && currentScrollY > 100) {
                header.classList.remove("hide");
            }

            lastScrollY = currentScrollY;
        }, { passive: true });
    </script>

</body>

</html>