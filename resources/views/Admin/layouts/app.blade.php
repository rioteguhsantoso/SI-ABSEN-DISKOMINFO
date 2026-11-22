
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin SI-ABSEN DISKOMINFO')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f7fb;
            scroll-behavior: smooth;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,.08);
            color: white;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: linear-gradient(100deg, #1675c7, #155eaa);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .15);
        }

        .sidebar-link.active svg {
            color: #ffffff;
        }

        .sidebar-icon {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
        }

        .header-shadow {
            box-shadow: 0 2px 12px rgba(15, 23, 42, .035);
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen text-slate-800">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col bg-[#102a45] text-white lg:flex">

            {{-- BRAND --}}
            <div class="border-b border-white/10 px-6 py-6">
                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/15">
                        <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[3px] text-slate-400">
                            SISTEM INFORMASI
                        </p>
                        <h1 class="mt-0.5 text-base font-extrabold tracking-wide text-white">
                            SI-ABSEN
                        </h1>
                        <p class="text-[10px] font-semibold tracking-widest text-emerald-400">
                            DISKOMINFO
                        </p>
                    </div>

                </div>
            </div>

            {{-- NAVIGATION --}}
            <div class="flex-1 overflow-y-auto px-4 py-6">

                <p class="mb-4 px-3 text-[10px] font-extrabold uppercase tracking-[2px] text-slate-500">
                    Menu Utama
                </p>

                <nav class="space-y-2">

                    {{-- DASHBOARD --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : 'text-slate-400' }}">

                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-4H3v4z"/>
                        </svg>

                        Dashboard
                    </a>

                    {{-- DATA PEGAWAI --}}
                    <a href="{{ route('admin.pegawai.index') }}"
                        class="sidebar-link {{ request()->is('admin/pegawai*') ? 'active' : 'text-slate-400' }}">

                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-10a4 4 0 110 8 4 4 0 010-8z"/>
                        </svg>

                        Data Pegawai
                    </a>

                    {{-- DATA ABSENSI --}}
                    <a href="{{ route('admin.absensi.index') }}"
                        class="sidebar-link {{ request()->is('admin/absensi*') ? 'active' : 'text-slate-400' }}">

                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h6"/>
                        </svg>

                        Data Absensi
                    </a>

                    {{-- PENGAJUAN IZIN --}}
                    <a href="{{ route('admin.izin.index') }}"
                        class="sidebar-link {{ request()->is('admin/izin*') ? 'active' : 'text-slate-400' }}">

                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>

                        <span class="flex-1">Pengajuan Izin</span>

                        @if(isset($jumlahIzinMenunggu) && $jumlahIzinMenunggu > 0)
                            <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-extrabold text-white">
                                {{ $jumlahIzinMenunggu > 99 ? '99+' : $jumlahIzinMenunggu }}
                            </span>
                        @endif
                    </a>

                    {{-- LAPORAN DAN REKAP --}}
                    <a href="{{ route('admin.laporan.index') }}"
                        class="sidebar-link {{ request()->is('admin/laporan*') ? 'active' : 'text-slate-400' }}">

                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 17v-2m3 2v-4m3 4V9m2 12H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                        </svg>

                        Laporan & Rekap

                        <span class="ml-auto rounded-md bg-emerald-400/15 px-2 py-0.5 text-[9px] font-bold text-emerald-300">
                            NEW
                        </span>
                    </a>

                </nav>

                {{-- SIDEBAR INFORMATION --}}
                <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300">
                            Sistem Aktif
                        </p>
                    </div>

                    <p class="mt-2 text-[10px] leading-5 text-slate-400">
                        SI-ABSEN DISKOMINFO
                        <br>
                        Sistem Informasi Absensi Pegawai.
                    </p>
                </div>

            </div>

            {{-- ADMIN PROFILE --}}
            <div class="border-t border-white/10 p-4">

                <div class="mb-4 flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-500/20 text-blue-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-xs font-bold text-white">
                            {{ Auth::guard('admin')->user()?->email ?? 'Administrator' }}
                        </p>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Administrator
                        </p>
                    </div>

                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-400 transition hover:bg-red-500/10 hover:text-red-300">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
                        </svg>

                        Keluar
                    </button>
                </form>

            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex min-w-0 flex-1 flex-col lg:ml-64">

            {{-- HEADER --}}
            <header class="header-shadow sticky top-0 z-30 flex h-[72px] shrink-0 items-center justify-between border-b border-slate-200/80 bg-white/95 px-5 backdrop-blur-md lg:px-8">

                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[2px] text-blue-600">
                        ADMINISTRATOR
                    </p>

                    <h2 class="mt-1 text-lg font-extrabold tracking-tight text-slate-800">
                        @yield('page_title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-3">

                    <div class="hidden text-right sm:block">
                        <p class="max-w-[220px] truncate text-xs font-bold text-slate-700">
                            {{ Auth::guard('admin')->user()?->email ?? 'Administrator' }}
                        </p>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">
                            Administrator
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-blue-100 bg-[#102a45] shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>

                </div>

            </header>

            {{-- PAGE CONTENT --}}
            <main class="w-full flex-1 p-5 lg:p-8">

                @yield('content')

            </main>

            {{-- FOOTER --}}
            <footer class="mt-auto border-t border-slate-200 bg-white px-5 py-5 lg:px-8">

                <div class="flex flex-col items-center justify-between gap-2 text-center sm:flex-row sm:text-left">

                    <div>
                        <p class="text-xs font-extrabold tracking-wide text-slate-700">
                            SI-ABSEN
                            <span class="mx-1 text-slate-300">|</span>
                            DISKOMINFO
                        </p>

                        <p class="mt-1 text-[10px] text-slate-400">
                            Sistem Informasi Absensi Pegawai Berbasis Web
                        </p>
                    </div>

                    <p class="text-[10px] font-medium text-slate-400">
                        &copy; {{ date('Y') }} SI-ABSEN DISKOMINFO. Hak cipta dilindungi.
                    </p>

                </div>

            </footer>

        </div>

    </div>

    @stack('scripts')

</body>
</html>
