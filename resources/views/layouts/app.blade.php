<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SI-ABSEN DISKOMINFO')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        html {
            background: #e8eef6;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #e8eef6;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .app-shell {
            width: 100%;
            max-width: 430px;
            min-height: 100vh;
            background: #f4f8fd;
            overflow-x: hidden;
            position: relative;
        }

        .safe-bottom {
            padding-bottom: 108px;
        }

        .bottom-nav {
            padding-bottom: max(10px, env(safe-area-inset-bottom));
        }

        .nav-shadow {
            box-shadow:
                0 -8px 30px rgba(25, 67, 115, .08),
                0 4px 18px rgba(25, 67, 115, .05);
        }

        .scan-button {
            box-shadow: 0 10px 25px rgba(31, 99, 205, .22);
        }

        .scan-button-disabled {
            box-shadow: 0 8px 20px rgba(100, 116, 139, .14);
        }

        .nav-item {
            -webkit-tap-highlight-color: transparent;
        }

        .nav-item:active {
            transform: scale(.95);
        }

        .soft-card {
            box-shadow: 0 5px 22px rgba(31, 67, 113, .055);
        }

        @media (min-width: 431px) {
            body {
                padding: 16px 0;
            }

            .app-shell {
                min-height: calc(100vh - 32px);
                border-radius: 30px;
                box-shadow: 0 0 45px rgba(15, 47, 89, .12);
            }

            .bottom-nav {
                max-width: 430px;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="flex justify-center">
    @php
    $sekarang = now('Asia/Makassar');
    $hariLibur = $sekarang->isWeekend();
    $scanAktif = !$hariLibur;
    $routeAktif = request()->path();
    @endphp
    <div class="app-shell">
        <main class="safe-bottom relative">
            @yield('content')
        </main>
        {{-- BOTTOM NAVIGATION --}}
        <nav class="bottom-nav fixed bottom-0 left-1/2 z-50 w-full max-w-[430px] -translate-x-1/2 px-3 pb-2">
            <div class="nav-shadow rounded-[27px] border border-white/90 bg-white/95 px-2 pt-2.5 backdrop-blur-2xl">
                <div class="grid grid-cols-5 items-end">
                    {{-- BERANDA --}}
                    <a href="{{ url('/dashboard') }}"
                        class="nav-item flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl py-1.5 transition {{ request()->is('dashboard') ? 'text-[#1769e0]' : 'text-[#91a3bd]' }}">
                        <div class="flex h-7 w-10 items-center justify-center rounded-xl {{ request()->is('dashboard') ? 'bg-blue-50' : '' }}">
                            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="{{ request()->is('dashboard') ? '2.4' : '1.8' }}"
                                    d="M3 11.5L12 4l9 7.5M5.5 10v9a1 1 0 001 1H9v-5.5h6V20h2.5a1 1 0 001-1v-9" />
                            </svg>
                        </div>
                        <span class="text-[10px] {{ request()->is('dashboard') ? 'font-extrabold' : 'font-medium' }}">
                            Beranda
                        </span>
                    </a>
                    {{-- RIWAYAT --}}
                    <a href="{{ url('/riwayat') }}"
                        class="nav-item flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl py-1.5 transition {{ request()->is('riwayat') ? 'text-[#1769e0]' : 'text-[#91a3bd]' }}">
                        <div class="flex h-7 w-10 items-center justify-center rounded-xl {{ request()->is('riwayat') ? 'bg-blue-50' : '' }}">
                            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke="currentColor"
                                    stroke-width="{{ request()->is('riwayat') ? '2.4' : '1.8' }}"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 7v5l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] {{ request()->is('riwayat') ? 'font-extrabold' : 'font-medium' }}">
                            Riwayat
                        </span>
                    </a>
                    {{-- SCAN --}}
                    <div class="relative flex justify-center">
                        @if($scanAktif)
                        <a href="{{ url('/kios-scan') }}"
                            class="scan-button nav-item -mt-8 flex h-[61px] w-[61px] items-center justify-center rounded-[21px] bg-[#1769e0] text-white transition active:scale-95">
                            <div class="flex h-10 w-10 items-center justify-center rounded-[14px] bg-white/10">
                                <svg class="h-[23px] w-[23px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M4 8V5a1 1 0 011-1h3m8 0h3a1 1 0 011 1v3M4 16v3a1 1 0 001 1h3m8 0h3a1 1 0 001-1v-3" />
                                    <rect x="8" y="8" width="8" height="8" rx="1.5"
                                        stroke="currentColor"
                                        stroke-width="1.8" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M10.5 10.5h3v3h-3z" />
                                </svg>
                            </div>
                        </a>
                        @else
                        <div class="scan-button-disabled nav-item -mt-8 flex h-[61px] w-[61px] cursor-not-allowed items-center justify-center rounded-[21px] bg-slate-300 text-white">
                            <div class="flex h-10 w-10 items-center justify-center rounded-[14px] bg-white/20">
                                <svg class="h-[23px] w-[23px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M4 8V5a1 1 0 011-1h3m8 0h3a1 1 0 011 1v3M4 16v3a1 1 0 001 1h3m8 0h3a1 1 0 001-1v-3" />
                                    <rect x="8" y="8" width="8" height="8" rx="1.5"
                                        stroke="currentColor"
                                        stroke-width="1.8" />
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M10.5 10.5h3v3h-3z" />
                                </svg>
                            </div>
                        </div>
                        @endif
                    </div>
                    {{-- IZIN --}}
                    <a href="{{ url('/izin') }}"
                        class="nav-item flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl py-1.5 transition {{ request()->is('izin') ? 'text-[#1769e0]' : 'text-[#91a3bd]' }}">
                        <div class="flex h-7 w-10 items-center justify-center rounded-xl {{ request()->is('izin') ? 'bg-blue-50' : '' }}">
                            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke="currentColor"
                                    stroke-width="{{ request()->is('izin') ? '2.4' : '1.8' }}"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                <path stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    d="M14 3v6h5M9 13h6M9 17h4" />
                            </svg>
                        </div>
                        <span class="text-[10px] {{ request()->is('izin') ? 'font-extrabold' : 'font-medium' }}">
                            Izin
                        </span>
                    </a>
                    {{-- PROFIL --}}
                    <a href="{{ url('/profil') }}"
                        class="nav-item flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl py-1.5 transition {{ request()->is('profil') ? 'text-[#1769e0]' : 'text-[#91a3bd]' }}">
                        <div class="flex h-7 w-10 items-center justify-center rounded-xl {{ request()->is('profil') ? 'bg-blue-50' : '' }}">
                            <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="3.5"
                                    stroke="currentColor"
                                    stroke-width="{{ request()->is('profil') ? '2.4' : '1.8' }}" />
                                <path stroke="currentColor"
                                    stroke-width="{{ request()->is('profil') ? '2.4' : '1.8' }}"
                                    stroke-linecap="round"
                                    d="M5 20c.8-3.5 3.2-5 7-5s6.2 1.5 7 5" />
                            </svg>
                        </div>
                        <span class="text-[10px] {{ request()->is('profil') ? 'font-extrabold' : 'font-medium' }}">
                            Profil
                        </span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    @stack('scripts')
</body>

</html>