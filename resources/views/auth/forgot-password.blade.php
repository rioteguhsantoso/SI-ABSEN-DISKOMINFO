<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SI-ABSEN DISKOMINFO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0c4a8a 0%, #1676c5 48%, #78c5f4 100%);
            color: #102d55;
            overflow-x: hidden;
        }

        .page-bg {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .2);
        }

        .bg-circle.one {
            width: 650px;
            height: 650px;
            top: -420px;
            left: -180px;
            box-shadow: 0 0 0 45px rgba(255, 255, 255, .035), 0 0 0 90px rgba(255, 255, 255, .025);
        }

        .bg-circle.two {
            width: 600px;
            height: 600px;
            bottom: -430px;
            right: -180px;
            box-shadow: 0 0 0 45px rgba(255, 255, 255, .04), 0 0 0 90px rgba(255, 255, 255, .025);
        }

        .bg-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            filter: blur(100px);
        }

        .glow-left {
            left: -200px;
            bottom: 5%;
        }

        .glow-right {
            right: -180px;
            top: 5%;
        }

        .dot-pattern {
            position: absolute;
            width: 110px;
            height: 90px;
            opacity: .3;
            background-image: radial-gradient(rgba(255, 255, 255, .9) 2px, transparent 2px);
            background-size: 20px 20px;
        }

        .forgot-card {
            background: rgba(255, 255, 255, .97);
            border: 1px solid rgba(255, 255, 255, .8);
            box-shadow: 0 30px 90px rgba(5, 35, 80, .25);
            backdrop-filter: blur(20px);
        }

        .logo-box {
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .form-input {
            width: 100%;
            padding: 13px 15px;
            background: #eff5ff;
            border: 1.5px solid #d9e6fa;
            border-radius: 13px;
            color: #102d55;
            font-size: 14px;
            outline: none;
            transition: .2s ease;
        }

        .form-input:focus {
            background: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .1);
        }

        .form-input::placeholder {
            color: #94a8c4;
        }

        .submit-button {
            background: linear-gradient(100deg, #164b9b, #0879e8);
            box-shadow: 0 9px 20px rgba(21, 91, 181, .22);
            transition: .2s ease;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 13px 25px rgba(21, 91, 181, .3);
        }

        @media (max-width: 480px) {
            body {
                padding: 14px;
            }

            .forgot-card {
                padding: 25px 21px;
                border-radius: 25px;
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Background Decoration -->
    <div class="page-bg">
        <div class="bg-circle one"></div>
        <div class="bg-circle two"></div>
        <div class="bg-glow glow-left"></div>
        <div class="bg-glow glow-right"></div>
        <div class="dot-pattern" style="top:35px;right:45px;"></div>
        <div class="dot-pattern" style="bottom:25px;left:35px;"></div>
    </div>

    <!-- Forgot Password Card -->
    <main class="forgot-card relative z-10 w-full max-w-[430px] rounded-[28px] px-7 py-9 sm:px-9 sm:py-10 my-4">

        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-7">

            <!-- Logo SI-ABSEN -->
            <div class="logo-box">
                <img src="{{ asset('images/logo-si-absen.png') }}" alt="Logo SI-ABSEN">
            </div>

            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-full text-[10px] sm:text-[11px] font-extrabold text-blue-700 tracking-wide uppercase mb-4">
                <span>🏛️</span>
                PEMERINTAH KOTA BANJARMASIN
            </div>

            <h1 class="text-[25px] sm:text-[28px] font-extrabold text-[#102d55] tracking-tight">
                Lupa Kata Sandi?
            </h1>

            <p class="text-slate-500 text-xs sm:text-sm mt-2 max-w-[320px] leading-relaxed">
                Jangan khawatir. Masukkan email yang terdaftar untuk menerima instruksi pengaturan kata sandi baru.
            </p>
        </div>

        <!-- Info Keamanan -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-4 mb-6 flex gap-3 items-center">
            <div class="w-10 h-10 bg-blue-100 rounded-xl text-blue-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xs sm:text-sm font-extrabold text-[#102d55]">Pemulihan Akun Aman</h2>
                <p class="text-[11px] text-slate-600 leading-relaxed mt-1">
                    Tautan pengaturan kata sandi akan dikirim ke alamat email yang terdaftar.
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('status'))
        <div class="message mb-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-medium leading-relaxed">
            {{ session('status') }}
        </div>
        @endif

        <!-- Error Message -->
        @if ($errors->any())
        <div class="message mb-4 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs font-medium leading-relaxed">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <!-- Form Reset Password -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs sm:text-sm font-extrabold text-[#17375f] mb-2">
                    Alamat Email
                </label>

                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        autofocus
                        autocomplete="email"
                        class="form-input pl-11">
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="submit-button w-full py-3.5 text-white font-extrabold rounded-xl transition flex items-center justify-center gap-2 text-sm mt-2">
                <span>Kirim Instruksi Pemulihan</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <!-- Back to Login -->
        <a href="{{ route('login') }}" class="mt-5 p-3.5 bg-blue-50 border border-blue-100 rounded-xl text-center text-xs sm:text-sm text-blue-700 font-bold hover:bg-blue-100 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Login
        </a>

        <!-- Footer -->
        <div class="mt-6 pt-4 border-t border-slate-100 text-center text-[10px] sm:text-xs text-slate-400 space-y-1.5">
            <div class="flex items-center justify-center gap-2 font-medium">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6m10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Sistem Terotentikasi • Server Resmi BJM</span>
            </div>
            <p>SI-ABSEN Versi 2.4.0 • Diskominfotik Kota Banjarmasin</p>
        </div>

    </main>
</body>

</html>