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

        /* Alerts */
        .alert {
            border-radius: 10px;
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
                                    <a href="{{ route('admin.announcements') }}"
                                        class="{{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                                        <i class="fas fa-bullhorn"></i> Pengumuman
                                    </a>
                                </li>

                            </ul>
                        @endif


                        @if(auth()->user()->hasRole('guru'))
                            <li>
                                <a href="{{ route('teacher.dashboard') }}"
                                    class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home"></i> Dashboard Guru
                                </a>
                            </li>
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
                        <p class="mb-0 small opacity-75">Dashboard Administrator - Sistem Manajemen Sekolah</p>
                    </div>
                </div>

                <div class="d-flex align-items-center text-end">
                    <i class="fas fa-calendar-alt fa-2x me-3 opacity-75"></i>
                    <div class="me-4">
                        <div class="fw-semibold">{{ now()->format('l, d F Y') }}</div>
                        <small class="opacity-75">{{ now()->format('H:i') }} WITA</small>
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

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
            @endif

            <div class="mt-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        }

        // Header hide/show saat scroll
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

        // Restore posisi scroll saat load
        window.addEventListener('load', () => {
            const scrollPos = localStorage.getItem('sidebarScroll');
            if (scrollPos) sidebar.scrollTop = parseInt(scrollPos);
        });

        // Simpan posisi scroll setiap saat sidebar digulir
        sidebar.addEventListener('scroll', () => {
            localStorage.setItem('sidebarScroll', sidebar.scrollTop);
        });
    </script>
    <script>
        // Header hide/show saat scroll
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