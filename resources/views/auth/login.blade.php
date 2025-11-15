@extends('layouts.public')

@section('title', 'Login - SMAN 1 Donggo')

@section('content')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #1241ff 100%);
            min-height: 100vh;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            min-height: 500px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .logo-section {
            background: linear-gradient(135deg, #7ebf99 0%, #667eea 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            color: white;
            flex: 1;
        }

        .logo-section img {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            border-radius: 50%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.1);
        }

        .logo-section h4 {
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }

        .logo-section p {
            text-align: center;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .form-section {
            flex: 1;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-section h4 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background-color: white;
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .text-muted {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }

        .text-muted a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        /* Responsivitas untuk mobile */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 100%;
                margin: 20px;
                min-height: auto;
            }

            .logo-section {
                padding: 30px 20px;
                order: 1;
            }

            .logo-section img {
                width: 80px;
                height: 80px;
            }

            .logo-section h4 {
                font-size: 1.2rem;
            }

            .logo-section p {
                font-size: 0.8rem;
            }

            .form-section {
                padding: 30px 20px;
                order: 2;
            }

            .form-section h4 {
                margin-bottom: 20px;
                font-size: 1.3rem;
            }

            .form-control {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }

        /* Responsivitas untuk tablet */
        @media (max-width: 992px) and (min-width: 769px) {
            .login-container {
                max-width: 700px;
            }

            .logo-section {
                padding: 30px 20px;
            }

            .logo-section img {
                width: 100px;
                height: 100px;
            }

            .form-section {
                padding: 30px 25px;
            }
        }

        /* Animasi untuk elemen */
        .animate-fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .animate-fade-in.delay-1 {
            animation-delay: 0.2s;
        }

        .animate-fade-in.delay-2 {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        <div class="login-container">
            <!-- Bagian Logo -->
            <div class="logo-section animate-fade-in">
                <img src="{{ asset('image/logo.png') }}" alt="Logo SMAN 1 Donggo" class="animate-fade-in">
                <h4>SMAN 1 DONGGO</h4>
                <p>Sekolah Berkualitas Berprestasi</p>
            </div>

            <!-- Bagian Form Login -->
            <div class="form-section">
                <h4 class="animate-fade-in delay-1">Masuk ke Akun Anda</h4>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group animate-fade-in delay-1">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan alamat email" required>
                    </div>
                    <div class="form-group animate-fade-in delay-2">
                        <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
                    </div>
                    <button type="submit" class="btn-login animate-fade-in delay-2">Masuk</button>
                    <p class="text-muted animate-fade-in delay-2">Lupa kata sandi? <a href="#">Hubungi Administrator</a></p>
                </form>
            </div>
@endsection