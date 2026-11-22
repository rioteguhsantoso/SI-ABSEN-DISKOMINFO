<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-ABSEN DISKOMINFO - Daftar Akun</title>
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

        .register-bg {
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

        .register-card {
            background: rgba(255, 255, 255, .97);
            border: 1px solid rgba(255, 255, 255, .8);
            box-shadow: 0 30px 90px rgba(5, 35, 80, .25);
            backdrop-filter: blur(20px);
        }

        .logo-box {
            width: 66px;
            height: 66px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .form-input {
            width: 100%;
            background: #eff5ff;
            border: 1.5px solid #d9e6fa;
            border-radius: 12px;
            padding: 11px 14px;
            color: #102d55;
            font-size: 13px;
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

        .register-button {
            background: linear-gradient(100deg, #164b9b, #0879e8);
            box-shadow: 0 9px 20px rgba(21, 91, 181, .22);
            transition: .2s ease;
        }

        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 13px 25px rgba(21, 91, 181, .3);
        }

        /* DESKTOP - FIT TO SCREEN 100% */
        @media (min-width: 768px) {
            body {
                min-height: 100vh;
                height: auto;
                padding: 12px !important;
                align-items: center;
                justify-content: center;
                overflow-y: auto;
            }

            .register-card {
                width: 100%;
                max-width: 450px !important;
                padding: 16px 30px !important;
                margin: auto !important;
                border-radius: 26px;
            }

            /* Header */
            .register-card>div:first-child {
                margin-bottom: 12px !important;
            }

            .logo-box {
                width: 52px;
                height: 52px;
                margin-bottom: 8px;
            }

            .register-card h1 {
                font-size: 23px;
                line-height: 1.2;
            }

            /* Badge */
            .register-card .inline-flex.items-center.gap-2 {
                padding: 5px 12px;
                margin-bottom: 8px;
            }

            /* Informasi verifikasi */
            .register-card>div.bg-gradient-to-r {
                padding: 9px 11px;
                margin-bottom: 12px !important;
                border-radius: 14px;
            }

            /* Jarak antar input */
            .register-card form.space-y-3\.5> :not([hidden])~ :not([hidden]) {
                margin-top: 10px;
            }

            /* Input */
            .form-input {
                padding: 9px 13px;
                font-size: 12px;
                border-radius: 11px;
            }

            /* Informasi pendaftaran wajah */
            .register-card form>div.bg-gradient-to-r {
                padding: 9px 11px;
            }

            /* Tombol */
            .register-button {
                padding-top: 11px;
                padding-bottom: 11px;
                border-radius: 12px;
            }

            /* Link kembali ke login */
            .register-card>div.mt-5 {
                margin-top: 12px !important;
                padding: 10px;
            }

            /* Footer */
            .register-card>div.mt-5.pt-4 {
                margin-top: 12px !important;
                padding-top: 10px !important;
            }
        }

        /* MOBILE */
        @media (max-width: 767px) {
            body {
                padding: 14px;
            }

            .register-card {
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
    <div class="register-bg">
        <div class="bg-circle one"></div>
        <div class="bg-circle two"></div>
        <div class="bg-glow glow-left"></div>
        <div class="bg-glow glow-right"></div>
        <div class="dot-pattern" style="top:35px;right:45px;"></div>
        <div class="dot-pattern" style="bottom:25px;left:35px;"></div>
    </div>

    <!-- Register Card -->
    <div class="register-card relative z-10 w-full max-w-[480px] rounded-[28px] px-6 py-7 sm:px-8 sm:py-8 my-3">

        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-5">

            <!-- Logo SI-ABSEN -->
            <div class="logo-box">
                <img src="{{ asset('images/logo-si-absen.png') }}" alt="Logo SI-ABSEN">
            </div>

            <!-- Badge Pemkot -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-full text-[10px] sm:text-[11px] font-extrabold text-blue-700 tracking-wide uppercase mb-3">
                <span>🏛️</span>
                PEMERINTAH KOTA BANJARMASIN
            </div>

            <h1 class="text-2xl sm:text-[27px] font-extrabold text-[#102d55] tracking-tight">
                Daftar Akun
            </h1>

            <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-[320px] leading-relaxed">
                Daftarkan akun pegawai untuk menggunakan SI-ABSEN DISKOMINFO
            </p>
        </div>

        <!-- Info Verifikasi Pegawai -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-3.5 mb-5 flex gap-3 items-center">
            <div class="w-10 h-10 bg-blue-100 rounded-xl text-blue-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xs sm:text-sm font-extrabold text-[#102d55]">Verifikasi Pegawai</h2>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed mt-1">
                    Gunakan NIP yang telah terdaftar sebagai pegawai Diskominfotik Kota Banjarmasin.
                </p>
            </div>
        </div>

        <!-- Alert Error -->
        @if(session('error') || $errors->any())
        <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs font-medium">
            {{ session('error') ?? $errors->first() }}
        </div>
        @endif

        <!-- Alert Success -->
        @if(session('success'))
        <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 text-xs font-medium">
            {{ session('success') }}
        </div>
        @endif

        <!-- Form Pendaftaran -->
        <form action="/register-account" method="POST" class="space-y-3.5">
            @csrf

            <!-- NIP -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="text-xs sm:text-sm font-extrabold text-[#17375f]">
                        NIP (Nomor Induk Pegawai)
                    </label>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full">
                        18 Digit
                    </span>
                </div>
                <input type="text" name="nip" value="{{ old('nip') }}" required maxlength="18" inputmode="numeric" placeholder="Contoh: 199308142020121002" class="form-input">
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs sm:text-sm font-extrabold text-[#17375f] mb-1.5">
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap" class="form-input">
                <p class="text-[10px] text-slate-400 mt-1.5">
                    Gunakan nama sesuai data kepegawaian.
                </p>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs sm:text-sm font-extrabold text-[#17375f] mb-1.5">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com" class="form-input">
            </div>

            <!-- Kata Sandi -->
            <div>
                <label class="block text-xs sm:text-sm font-extrabold text-[#17375f] mb-1.5">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="form-input pr-12">
                    <button type="button" onclick="togglePassword('password', 'eye-password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-700 p-1 transition">
                        <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Kata Sandi -->
            <div>
                <label class="block text-xs sm:text-sm font-extrabold text-[#17375f] mb-1.5">
                    Konfirmasi Kata Sandi
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Ulangi kata sandi" class="form-input pr-12">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-700 p-1 transition">
                        <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Info Pendaftaran Wajah -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-3.5 flex gap-3 items-center">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0 text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 4h.01M9 8h.01M15 8h.01M9 12h.01M15 12h.01M9 16h.01M15 16h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-extrabold text-[#102d55]">Pendaftaran Wajah</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed mt-1">
                        Setelah data akun diverifikasi, Anda akan diarahkan untuk mendaftarkan wajah sebagai identitas biometrik untuk proses absensi.
                    </p>
                </div>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit" class="register-button w-full py-3.5 text-white font-extrabold rounded-xl transition flex items-center justify-center gap-2 text-sm mt-2">
                <span>Lanjutkan Pendaftaran</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <!-- Link Login -->
        <div class="mt-5 p-3.5 bg-blue-50 border border-blue-100 rounded-xl text-center text-xs sm:text-sm text-slate-600">
            Sudah memiliki akun?
            <a href="/login" class="text-blue-700 font-extrabold hover:underline ml-1">
                Kembali ke Login
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-5 pt-4 border-t border-slate-100 text-center text-[10px] sm:text-xs text-slate-400 space-y-1.5">
            <div class="flex items-center justify-center gap-2 font-medium">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6m10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Sistem Terotentikasi • Server Resmi BJM</span>
            </div>
            <p>SI-ABSEN Versi 2.4.0 • Diskominfotik Kota Banjarmasin</p>
        </div>

    </div>

    <!-- Toggle Password JS -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.959 8.959 0 013.682-.783c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-1.776 1.776L3 3l18 18"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>

</html>