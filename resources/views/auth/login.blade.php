@extends('layouts.public')

@section('title', 'Login - SMAN 1 Donggo')

@section('content')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        }

        .login-wrapper {
            min-height: calc(100vh - 160px);
            /* Sesuaikan tinggi navbar + footer */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            padding: 40px 60px;
            width: 900px;
            max-width: 95%;
            transition: all 0.5s ease;
        }

        .login-container:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
        }

        .logo-section {
            text-align: center;
            color: white;
        }

        .logo-section img {
            width: 150px;
            height: auto;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.05);
        }

        .logo-section h4 {
            font-weight: 600;
            letter-spacing: 1px;
        }

        .form-section {
            flex: 1;
            background: rgba(255, 255, 255, 0.2);
            padding: 35px 40px;
            border-radius: 20px;
            box-shadow: inset 0 0 15px rgba(255, 255, 255, 0.1);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 10px;
            padding: 12px 15px;
            transition: 0.3s;
        }

        .form-control:focus {
            background-color: white;
            box-shadow: 0 0 10px rgba(58, 123, 213, 0.4);
        }

        .btn-login {
            background-color: #1e3c72;
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            transition: 0.4s ease;
        }

        .btn-login:hover {
            background-color: #2a5298;
            transform: scale(1.03);
            box-shadow: 0 6px 18px rgba(30, 60, 114, 0.4);
        }

        .text-muted a {
            color: #cfd9df;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
            color: white;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                padding: 30px 20px;
            }

            .logo-section img {
                width: 120px;
            }
        }

        .animate-item {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Styling tambahan jika ingin tetap menarik */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 50px;
            padding: 40px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(18px);
            max-width: 900px;
            margin: 0 auto;
            margin-top: 60px;
        }

        .logo-section img {
            width: 150px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.05);
        }

        .form-section {
            flex: 1;
            padding: 30px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: inset 0 0 15px rgba(255, 255, 255, 0.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.animate-item');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('visible');
                }, index * 150); // Delay staggered, logo dulu baru form
            });
        });
    </script>

    <div class="login-wrapper">
        <div class="login-container bg-primary">
            <!-- Logo di kiri -->
            <div class="logo-section animate-item">
                <img src="{{ asset('image/logo.png') }}" alt="Logo SMAN 1 Donggo">
                <h4>SMAN 1 DONGGO</h4>
            </div>

            <!-- Form login -->
            <div class="form-section bg-blue animate-item">
                <h4 class="text-center mb-4 text-white fw-semibold">Login ke Akun Anda</h4>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-white">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email anda" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>
                    </div>
                    <button type="submit" class="btn-login">Masuk</button>
                    <p class="text-center text-muted mt-3">Lupa password? <a href="#">Hubungi Admin</a></p>
                </form>
            </div>
        </div>

    </div>
@endsection