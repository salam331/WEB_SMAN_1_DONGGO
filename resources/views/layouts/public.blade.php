<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMAN 1 DONGGO')</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Animations (for hero fade effects) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Global Style Enhancement -->
    <style>
        /* ==============================
           🌈 GLOBAL BASE STYLING
        ============================== */
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        a {
            text-decoration: none;
            transition: all .3s ease;
        }

        a:hover {
            color: #0d6efd;
        }

        /* ==============================
           🌆 NAVBAR STYLING
        ============================== */
        .navbar {
            background: linear-gradient(90deg, #0052d4 0%, #4364f7 50%, #6fb1fc 100%);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #2600ce;
            transform: translateY(-2px);
        }

        /* ==============================
           📬 ALERT STYLING
        ============================== */
        .alert {
            border-radius: 1rem;
            font-weight: 500;
        }

        /* ==============================
           ⚫ FOOTER STYLING
        ============================== */
        footer {
            background: linear-gradient(180deg, #111827 0%, #1f2937 100%);
        }

        footer h5 {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        footer a {
            color: #d1d5db;
            text-decoration: none;
        }

        footer a:hover {
            color: #fff;
        }

        footer p,
        footer li {
            font-size: 0.9rem;
        }

        footer hr {
            opacity: 0.2;
        }

        footer .small {
            color: #9ca3af;
        }

        /* ==============================
           ✨ ANIMATIONS
        ============================== */
        .fade-in {
            animation: fadeIn 1.2s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- ==============================
         🌐 NAVIGATION BAR
    ============================== -->
    <nav class="navbar navbar-expand-lg navbar-dark py-3 sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('image/logo.png') }}" alt="Logo Sekolah" class="me-2"
                    style="width: 55px; height: 55px; object-fit: cover;">
                <span class="navbar-brand py-0 text-white">SMAN 1 DONGGO <br><p class="mb-0 d-none d-md-block d-xl-block text-white">Sekolah Hebat Berprestasi</p></span>
                
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse fade-in" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                            href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-2"></i>Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}"
                            href="{{ route('public.gallery') }}">
                            <i class="fas fa-images me-2"></i>Galeri</a></li>
                    <li class="nav-item"><a
                            class="nav-link {{ request()->routeIs('public.announcements') ? 'active' : '' }}"
                            href="{{ route('public.announcements') }}">
                            <i class="fas fa-bullhorn me-2"></i>Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                            href="{{ route('contact') }}">
                            <i class="fas fa-phone me-2"></i>Kontak</a></li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}"><i
                                    class="fas fa-user-circle me-1"></i>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-white">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-black fw-semibold px-3 py-1 ms-lg-2 rounded-pill shadow-sm"
                                href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ==============================
         🧭 MAIN CONTENT
    ============================== -->
    <main>
        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- ==============================
         ⚫ FOOTER SECTION
    ============================== -->
    <footer class="text-white pt-5 pb-3 mt-5">
        <div class="container-fluid px-4">
            <div class="row gy-4">
                <div class="col-md-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-2 fade-in-logo">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo SMAN 1 DONGGO"
                            class="img-fluid animate__animated animate__fadeIn"
                            style="width: 45px; height: 45px; animation-duration: 2s;"
                            class="img-fluid animate__animated animate__fadeIn">
                        <h5 class="mb-0 ms-2 fw-bold">
                            SMAN 1 DONGGO
                        </h5>
                    </div>
                    <p class="small mt-2">
                        Sekolah berkualitas dengan pendidikan yang unggul, modern, dan berintegritas tinggi
                        dalam membentuk generasi emas Indonesia.
                    </p>
                </div>


                <div class="col-md-4">
                    <h5>Menu Navigasi</h5>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('public.announcements') }}">Pengumuman</a></li>
                        <li><a href="{{ route('public.gallery') }}">Galeri</a></li>
                        <li><a href="{{ route('contact') }}">Kontak</a></li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h5>Hubungi Kami</h5>
                    <p class="small mb-1"><i class="fas fa-phone me-2 text-warning"></i>(+62) 85339458990</p>
                    <p class="small mb-1"><i class="fas fa-envelope me-2 text-warning"></i>info@sman1donggo.sch.id</p>
                    <p class="small"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Jl. Pesanggrahan No.19,
                        Doridungga, Kec. Donggo, Kabupaten Bima,<br>Nusa Tenggara Bar.</p>
                </div>
            </div>

            <hr class="mt-4 mb-3">

            <div class="text-center small">
                <p class="mb-0">&copy; {{ date('Y') }} <strong>SMAN 1 DONGGO</strong>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ==============================
         ⚙️ JAVASCRIPT LIBRARIES
    ============================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>