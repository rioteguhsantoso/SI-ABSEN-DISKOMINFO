<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-ABSEN DISKOMINFO - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0c4a8a 0%, #1676c5 48%, #78c5f4 100%);
            color: #102d55;
            overflow-x: hidden;
        }

        /* Background Decoration */
        .login-bg {
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
            box-shadow:
                0 0 0 45px rgba(255, 255, 255, .035),
                0 0 0 90px rgba(255, 255, 255, .025);
        }

        .bg-circle.two {
            width: 600px;
            height: 600px;
            bottom: -430px;
            right: -180px;
            box-shadow:
                0 0 0 45px rgba(255, 255, 255, .04),
                0 0 0 90px rgba(255, 255, 255, .025);
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
            background-image: radial-gradient(rgba(255, 255, 255, .9) 2px,
                    transparent 2px);
            background-size: 20px 20px;
        }

        /* Profile Card */
        .login-card {
            background: rgba(255, 255, 255, .97);
            border: 1px solid rgba(255, 255, 255, .8);
            box-shadow: 0 30px 90px rgba(5, 35, 80, .25);
            backdrop-filter: blur(20px);
        }

        /* Logo */
        .logo-box {
            width: 94px;
            height: 94px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 28px;
            background: linear-gradient(145deg, #fff, #f1f7ff);
            box-shadow: 0 12px 28px rgba(20, 70, 130, .17);
            border: 1px solid #e8f1ff;
            overflow: hidden;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 5px;
        }

        /* Input */
        .form-input {
            width: 100%;
            background: #eff5ff;
            border: 1.5px solid #d9e6fa;
            border-radius: 14px;
            padding: 14px 16px;
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

        /* Button */
        .login-button {
            background: linear-gradient(100deg, #164b9b, #0879e8);
            box-shadow: 0 9px 20px rgba(21, 91, 181, .22);
            transition: .2s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 13px 25px rgba(21, 91, 181, .3);
        }

        /* DESKTOP - FIT TO SCREEN */
        @media (min-width: 768px) {
            body {
                min-height: 100vh;
                height: auto;
                padding: 12px !important;
                align-items: center;
                justify-content: center;
                overflow-y: auto;
            }

            .login-card {
                width: 100%;
                max-width: 450px !important;
                padding: 18px 30px !important;
                margin: 0 auto !important;
                border-radius: 26px;
            }

            /* Header */
            .login-card>div:first-child {
                margin-bottom: 14px !important;
            }

            .logo-box {
                width: 58px;
                height: 58px;
                border-radius: 17px;
                margin-bottom: 10px !important;
            }

            .login-card h1 {
                font-size: 24px;
                line-height: 1.2;
            }

            .login-card h1+p {
                font-size: 12px;
                margin-top: 5px;
                line-height: 1.5;
            }

            /* Badge */
            .login-card .inline-flex.items-center.gap-2 {
                padding: 5px 12px;
                margin-bottom: 10px;
            }

            /* Information Card */
            .login-card>div.bg-gradient-to-r {
                padding: 10px 12px;
                margin-bottom: 14px !important;
                border-radius: 15px;
            }

            .login-card>div.bg-gradient-to-r .w-11 {
                width: 38px;
                height: 38px;
            }

            /* Form */
            .login-card form.space-y-5> :not([hidden])~ :not([hidden]) {
                margin-top: 12px;
            }

            .form-input {
                padding: 10px 14px;
                font-size: 13px;
                border-radius: 12px;
            }

            /* Button */
            .login-card .login-button {
                padding-top: 11px;
                padding-bottom: 11px;
                border-radius: 12px;
            }

            /* Register Link */
            .login-card>div.mt-6 {
                margin-top: 14px !important;
                padding: 10px 12px;
                border-radius: 13px;
            }

            /* Footer */
            .login-card>div.mt-7 {
                margin-top: 14px !important;
                padding-top: 12px !important;
            }

            .login-card>div.mt-7.space-y-2> :not([hidden])~ :not([hidden]) {
                margin-top: 5px;
            }
        }

        /* MOBILE */
        @media (max-width: 767px) {
            body {
                padding: 14px;
            }

            .login-card {
                padding: 24px 20px;
                border-radius: 25px;
            }

            .logo-box {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:py-8">

    <!-- Background Decoration -->
    <div class="login-bg">
        <div class="bg-circle one"></div>
        <div class="bg-circle two"></div>
        <div class="bg-glow glow-left"></div>
        <div class="bg-glow glow-right"></div>
        <div class="dot-pattern" style="top:35px;right:45px;"></div>
        <div class="dot-pattern" style="bottom:25px;left:35px;"></div>
    </div>

    <!-- Card Wrapper -->
    <div class="login-card relative z-10 w-full max-w-[520px] rounded-[32px] px-6 py-8 sm:px-10 sm:py-10 my-3">

        <!-- Header Logo & Title -->
        <div class="flex flex-col items-center text-center mb-7">

            <!-- Logo SI-ABSEN -->
            <div class="logo-box mb-5">
                <img src="{{ asset('images/logo-si-absen.png') }}" alt="Logo SI-ABSEN">
            </div>

            <!-- Badge Pemkot -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-full text-[10px] sm:text-[11px] font-extrabold text-blue-700 tracking-wide uppercase mb-4">
                <span>🏛️</span>
                PEMERINTAH KOTA BANJARMASIN
            </div>

            <h1 class="text-[25px] sm:text-[30px] font-extrabold text-[#102d55] tracking-tight leading-tight">
                SI-ABSEN <span class="text-blue-600">DISKOMINFO</span>
            </h1>

            <p class="text-slate-500 text-sm sm:text-base mt-2 max-w-[330px] leading-relaxed">
                Masuk untuk mengakses presensi & absensi resmi Anda
            </p>
        </div>

        <!-- Alert Notification -->
        @if(session('error') || $errors->any())
        <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs font-medium">
            {{ session('error') ?? $errors->first() }}
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 text-xs font-medium">
            {{ session('success') }}
        </div>
        @endif

        <!-- Card Info Akses Terotentikasi -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-4 mb-7 flex gap-3 items-center">
            <div class="w-11 h-11 bg-blue-100 rounded-xl text-blue-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xs sm:text-sm font-extrabold text-[#102d55]">Akses Terotentikasi</h2>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed mt-1">
                    Sistem Absensi Kepegawaian Resmi Diskominfotik Kota Banjarmasin. Gunakan akun NIP aktif dan terverifikasi.
                </p>
            </div>
        </div>

        <!-- Form Login -->
        <form action="/login" method="POST" class="space-y-5">
            @csrf

            <!-- Field NIP -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs sm:text-sm font-extrabold text-[#17375f] flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6" />
                        </svg>
                        NIP (Nomor Induk Pegawai)
                    </label>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full">18 Digit</span>
                </div>
                <input type="text" name="nip" required placeholder="Contoh: 19930814 2020121002" class="form-input">
            </div>

            <!-- Field Kata Sandi -->
            <div>
                <label class="block text-xs sm:text-sm font-extrabold text-[#17375f] mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Kata Sandi
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi akun" class="form-input pr-12">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-700 p-1 transition">
                        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Checkbox & Lupa Sandi -->
            <div class="flex items-center justify-between text-xs sm:text-sm pt-0.5">
                <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer font-medium">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-700 focus:ring-blue-700">
                    Ingat saya
                </label>
                <a href="/forgot-password" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                    Lupa Kata Sandi?
                </a>
            </div>

            <!-- Button Masuk -->
            <button type="submit" class="login-button w-full py-4 text-white font-extrabold rounded-2xl transition flex items-center justify-center gap-3 text-sm sm:text-base mt-2">
                <span>Masuk ke Sistem</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <!-- Register Link Box -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl text-center text-xs sm:text-sm text-slate-600">
            Belum memiliki akun terdaftar?
            <a href="/register-account" class="text-blue-700 font-extrabold hover:underline ml-1">
                Daftar Sekarang
            </a>
        </div>

        <!-- Footer Info -->
        <div class="mt-7 pt-5 border-t border-slate-100 text-center text-[10px] sm:text-xs text-slate-400 space-y-2">
            <div class="flex items-center justify-center gap-2 font-medium">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Terenkripsi SSL 256-bit <span class="mx-1">•</span> Server Resmi BJM</span>
            </div>
            <p>SI-ABSEN Versi 2.4.0 <span class="mx-1">•</span> Diskominfotik Kota Banjarmasin</p>
        </div>

    </div>

    <!-- Toggle Password JS -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.959 8.959 0 013.682-.783c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-1.776 1.776L3 3l18 18"/>`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>

</html>