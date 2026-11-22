@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php
$sekarang = now('Asia/Makassar');
$jamSekarang = $sekarang->format('H:i:s');
$hariTanggal = $sekarang->translatedFormat('l, d F Y');
$hariSingkat = $sekarang->translatedFormat('l');
$isHariLibur = $sekarang->isWeekend();
$sudahMasuk = $absensiHariIni && $absensiHariIni->jam_masuk;
$sudahPulang = $absensiHariIni && $absensiHariIni->jam_pulang;
$jumlahTerlambat = $terlambat ?? 0;
$headerImage = file_exists(public_path('images/dashboard-header.jpg')) ? asset('images/dashboard-header.jpg') : null;

if ($isHariLibur) {

$statusHariIni = 'Hari Libur';
$statusWarna = 'text-indigo-600';
$statusBg = 'bg-indigo-50';
$statusIcon = 'text-indigo-500';
$statusKeterangan = $hariTanggal . ' · Tidak ada jadwal kerja';
$heroStatus = 'Nikmati waktu istirahat Anda ✨';

} elseif ($sedangIzin) {

$statusHariIni = 'Sedang Izin';
$statusWarna = 'text-violet-600';
$statusBg = 'bg-violet-50';
$statusIcon = 'text-violet-500';

$statusKeterangan = 'Izin disetujui · ' .
\Carbon\Carbon::parse($izinAktif->tanggal_mulai)->translatedFormat('d M Y') .
' - ' .
\Carbon\Carbon::parse($izinAktif->tanggal_selesai)->translatedFormat('d M Y');

$heroStatus = 'Izin Anda telah disetujui. Tidak perlu melakukan absensi.';

} elseif ($absensiHariIni) {
if ($absensiHariIni->status === 'terlambat') {
$statusHariIni = 'Terlambat';
$statusWarna = 'text-amber-600';
$statusBg = 'bg-amber-50';
$statusIcon = 'text-amber-500';
$statusKeterangan = $sudahMasuk
? 'Masuk pukul ' . \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') . ' WITA'
: $hariTanggal;
$heroStatus = $sudahPulang
? 'Absensi hari ini sudah lengkap ✓'
: 'Absensi masuk sudah tercatat.';
} elseif ($absensiHariIni->status === 'izin') {
$statusHariIni = 'Izin';
$statusWarna = 'text-indigo-600';
$statusBg = 'bg-indigo-50';
$statusIcon = 'text-indigo-500';
$statusKeterangan = 'Status izin tercatat pada sistem';
$heroStatus = 'Status izin Anda sudah tercatat.';
} else {
$statusHariIni = 'Hadir';
$statusWarna = 'text-emerald-600';
$statusBg = 'bg-emerald-50';
$statusIcon = 'text-emerald-500';
$statusKeterangan = $sudahMasuk
? 'Masuk pukul ' . \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') . ' WITA'
: $hariTanggal;
$heroStatus = $sudahPulang
? 'Absensi hari ini sudah lengkap ✓'
: 'Absensi masuk sudah tercatat.';
}
} else {
$statusHariIni = 'Belum Absen';
$statusWarna = 'text-slate-600';
$statusBg = 'bg-slate-100';
$statusIcon = 'text-slate-400';
$statusKeterangan = $hariTanggal;

if ($jamSekarang < '06:00:00' ) {
    $heroStatus='Semangat untuk hari ini! Jangan lupa absen ✨' ;
    } elseif ($jamSekarang < '16:00:00' ) {
    $heroStatus='Waktu absen masuk sedang aktif.' ;
    } else {
    $heroStatus='Waktu absen masuk hari ini telah berakhir.' ;
    }
    }
  $masukAktif = !$isHariLibur && !$sedangIzin && !$sudahMasuk
    && $jamSekarang >= '06:00:00'
    && $jamSekarang < '16:00:00';

        $pulangAktif=!$isHariLibur && !$sedangIzin && $sudahMasuk && !$sudahPulang
        && $jamSekarang>= '16:00:00'
        && $jamSekarang <= '17:00:00' ;
            $belumBukaMasuk=!$isHariLibur && !$sudahMasuk && $jamSekarang < '06:00:00' ;
            $berakhirMasuk=!$isHariLibur && !$sudahMasuk && $jamSekarang>= '16:00:00';
            $belumBukaPulang = !$isHariLibur && $sudahMasuk && !$sudahPulang && $jamSekarang < '16:00:00' ;
                $berakhirPulang=!$isHariLibur && $sudahMasuk && !$sudahPulang && $jamSekarang> '17:00:00';
                @endphp
                <style>
                    .dashboard-page {
                        background: #f4f8fd;
                    }

                    .dashboard-hero {
                        background: linear-gradient(180deg, rgba(20, 99, 181, .94) 0%, rgba(45, 125, 203, .86) 48%, rgba(38, 105, 177, .97) 100%);
                    }

                    .dashboard-hero.has-image {
                        background-image: linear-gradient(180deg, rgba(24, 108, 190, .82) 0%, rgba(43, 126, 201, .62) 46%, rgba(15, 71, 130, .90) 100%),
                        url('{{ $headerImage }}');
                        background-position: center;
                        background-size: cover;
                        background-repeat: no-repeat;
                    }

                    .dashboard-card {
                        transition: transform .2s ease, box-shadow .2s ease;
                    }

                    .dashboard-card:active {
                        transform: scale(.985);
                    }

                    .dashboard-soft-shadow {
                        box-shadow: 0 8px 28px rgba(30, 76, 130, .08);
                    }

                    .dashboard-progress {
                        background: linear-gradient(90deg, #2167d5, #62a8f8);
                    }

                    .dashboard-header-text {
                        text-shadow: 0 2px 10px rgba(5, 38, 78, .32);
                    }

                    .dashboard-header-subtext {
                        text-shadow: 0 1px 6px rgba(5, 38, 78, .28);
                    }

                    .holiday-box {
                        position: relative;
                        overflow: hidden;
                    }

                    .holiday-illustration {
                        position: relative;
                        width: 72px;
                        height: 64px;
                        flex-shrink: 0;
                    }

                    .holiday-glow {
                        position: absolute;
                        right: 1px;
                        top: 2px;
                        width: 58px;
                        height: 58px;
                        border-radius: 999px;
                        background: rgba(147, 197, 253, .28);
                        animation: holidayGlow 3s ease-in-out infinite;
                    }

                    .holiday-sun {
                        position: absolute;
                        right: 10px;
                        top: 8px;
                        width: 43px;
                        height: 43px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 18px;
                        background: rgba(255, 255, 255, .82);
                        box-shadow: 0 8px 18px rgba(59, 130, 246, .12);
                        animation: holidayFloat 3s ease-in-out infinite;
                    }

                    .holiday-sparkle {
                        position: absolute;
                        font-size: 13px;
                        line-height: 1;
                        animation: holidaySparkle 2.2s ease-in-out infinite;
                    }

                    .holiday-sparkle.one {
                        right: 0;
                        top: 1px;
                    }

                    .holiday-sparkle.two {
                        left: 7px;
                        top: 23px;
                        font-size: 9px;
                        animation-delay: .7s;
                    }

                    .holiday-sparkle.three {
                        right: 14px;
                        bottom: 0;
                        font-size: 8px;
                        animation-delay: 1.2s;
                    }

                    .holiday-building {
                        animation: buildingFloat 4s ease-in-out infinite;
                    }

                    .holiday-sun {
                        transform-origin: center;
                        animation: sunPulse 2.8s ease-in-out infinite;
                    }

                    @keyframes buildingFloat {

                        0%,
                        100% {
                            transform: translateY(0);
                        }

                        50% {
                            transform: translateY(-2px);
                        }
                    }

                    @keyframes sunPulse {

                        0%,
                        100% {
                            opacity: .7;
                        }

                        50% {
                            opacity: 1;
                        }
                    }

                    @keyframes holidayFloat {

                        0%,
                        100% {
                            transform: translateY(0) rotate(-2deg);
                        }

                        50% {
                            transform: translateY(-5px) rotate(2deg);
                        }
                    }

                    @keyframes holidayGlow {

                        0%,
                        100% {
                            transform: scale(.92);
                            opacity: .55;
                        }

                        50% {
                            transform: scale(1.08);
                            opacity: .9;
                        }
                    }

                    @keyframes holidaySparkle {

                        0%,
                        100% {
                            transform: scale(.75) rotate(0deg);
                            opacity: .35;
                        }

                        50% {
                            transform: scale(1.15) rotate(15deg);
                            opacity: 1;
                        }
                    }

                    @media (prefers-reduced-motion: reduce) {

                        .holiday-glow,
                        .holiday-sun,
                        .holiday-sparkle {
                            animation: none;
                        }
                    }

                    @media (min-width: 768px) {
                        .dashboard-card:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 14px 32px rgba(30, 76, 130, .10);
                        }
                    }
                </style>
                <div class="dashboard-page min-h-screen pb-5">
                    {{-- HERO --}}
                    <section class="dashboard-hero {{ $headerImage ? 'has-image' : '' }} relative overflow-hidden rounded-b-[34px] text-white">
                        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#1c69ad]/30 to-transparent"></div>
                        <div class="relative z-10 px-5 pb-10 pt-5">
                            {{-- BRAND --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/95 shadow-lg shadow-black/10">
                                        <img src="{{ asset('images/logo-header.jpg') }}"
                                            alt="Logo Diskominfo"
                                            class="h-8 w-8 object-contain">
                                    </div>
                                    <div class="dashboard-header-text">
                                        <p class="text-[15px] font-extrabold leading-none tracking-tight text-white">
                                            DISKOMINFO
                                        </p>
                                        <p class="dashboard-header-subtext mt-1 text-[8px] font-bold text-white/90">
                                            Kota Banjarmasin
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('notifications.index') }}"
                                        class="relative flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-white/15 backdrop-blur-sm">
                                        <svg class="h-5 w-5 text-white drop-shadow-sm"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                                        </svg>
                                        @if(($jumlahNotifikasiBelumDibaca ?? 0) > 0)
                                        <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[6px] font-extrabold text-white ring-2 ring-[#3784c7]">
                                            {{ $jumlahNotifikasiBelumDibaca > 9 ? '9+' : $jumlahNotifikasiBelumDibaca }}
                                        </span>
                                        @endif
                                    </a>
                                    <a href="{{ url('/profil') }}"
                                        class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full border border-white/60 bg-white/20 shadow-sm backdrop-blur-sm">
                                        @if($pegawai->foto)
                                        <img src="{{ asset('storage/' . $pegawai->foto) }}"
                                            alt="Foto {{ $pegawai->nama }}"
                                            class="h-full w-full object-cover">
                                        @else
                                        <span class="text-[11px] font-bold text-white drop-shadow-sm">
                                            {{ collect(explode(' ', trim($pegawai->nama)))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('') }}
                                        </span>
                                        @endif
                                    </a>
                                </div>
                            </div>
                            {{-- GREETING --}}
                            <div class="mt-7">
                                <h1 class="dashboard-header-text text-[22px] font-extrabold leading-tight tracking-tight text-white">
                                    Halo, {{ $pegawai->nama }} 👋
                                </h1>
                                <p class="dashboard-header-subtext mt-2 text-[10px] font-bold text-white/95">
                                    {{ $isHariLibur ? 'Nikmati waktu istirahatmu hari ini.' : 'Semangat terus untuk hari ini!' }}
                                </p>
                                <p class="dashboard-header-subtext mt-0.5 text-[9px] font-semibold text-white/80">
                                    {{ $isHariLibur ? 'Tidak ada jadwal absensi pada hari libur.' : 'Jangan lupa absen ya ✨' }}
                                </p>
                            </div>
                            {{-- DATE --}}
                            <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/20 px-3 py-2 shadow-sm backdrop-blur-md">
                                <svg class="h-3.5 w-3.5 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3v3m10-3v3M4 10h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1h14" />
                                </svg>
                                <span class="text-[8px] font-bold text-white drop-shadow-sm">{{ $hariTanggal }}</span>
                            </div>
                            {{-- HERO STATUS --}}
                            <div class="mt-4 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isHariLibur ? 'bg-amber-300' : 'bg-emerald-300' }} shadow-[0_0_8px_rgba(255,255,255,.5)]"></span>
                                <span class="dashboard-header-subtext text-[8px] font-bold text-white/90">{{ $heroStatus }}</span>
                            </div>
                        </div>
                    </section>
                    {{-- QUICK ATTENDANCE --}}
                    <section class="relative z-30 mt-4 px-4">
                        <div class="grid grid-cols-2 gap-3">
                            {{-- PAGI --}}
                            @if($isHariLibur)
                            <div class="dashboard-card rounded-[22px] border border-slate-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.14)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v2m6.36.64l-1.42 1.42M21 12h-2M17.36 18.36l-1.42-1.42M12 19v2m-5.36-2.64l1.42-1.42M5 12H3m4.64-4.64L6.22 5.94M8 12a4 4 0 108 0 4 4 0 00-8 0z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-slate-500">LIBUR</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-slate-600">Absen Pagi</p>
                                <p class="mt-0.5 text-[8px] font-bold text-slate-400">Tidak tersedia</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Senin–Jumat · 06:00–16:00</p>
                            </div>

                            @elseif($sedangIzin)
                            <div class="dashboard-card rounded-[22px] border border-violet-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(124,58,237,.10)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-100">
                                        <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h4m4 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l6 6v10a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-violet-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-violet-700">
                                        IZIN
                                    </span>
                                </div>

                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Pagi</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-violet-600">Tidak perlu absen</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Izin telah disetujui</p>
                            </div>

                            @elseif($sudahMasuk)
                            <div class="dashboard-card rounded-[22px] border border-emerald-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.13)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full {{ $absensiHariIni->status === 'terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }} px-2 py-1 text-[6px] font-extrabold tracking-wide">
                                        {{ $absensiHariIni->status === 'terlambat' ? 'TERLAMBAT' : 'SELESAI' }}
                                    </span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Pagi</p>
                                <p class="mt-0.5 text-[8px] font-extrabold {{ $absensiHariIni->status === 'terlambat' ? 'text-amber-600' : 'text-emerald-600' }}">
                                    {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }} WITA
                                </p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">
                                    {{ $absensiHariIni->status === 'terlambat' ? 'Absensi terlambat tetap dihitung hadir' : 'Absensi sudah tercatat' }}
                                </p>
                            </div>
                            @elseif($masukAktif)
                            <a href="{{ url('/kios-scan') }}" class="dashboard-card rounded-[22px] border border-blue-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(37,99,235,.16)] transition hover:-translate-y-0.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="1.8" d="M12 3v2m0 14v2M3 12h2m14 0h2M5.6 5.6l1.4 1.4m10 10l1.4 1.4M18.4 5.6L17 7M7 17l-1.4 1.4M12 7a5 5 0 100 10 5 5 0 000-10z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-blue-50 px-2 py-1 text-[6px] font-extrabold tracking-wide text-blue-700">AKTIF</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Pagi</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-blue-600">06:00 – 16:00</p>
                                <div class="mt-2.5 flex h-7 items-center justify-center gap-1.5 rounded-full bg-[#286bd0] text-[7px] font-extrabold text-white shadow-sm">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 8V5a1 1 0 011-1h3m8 0h3a1 1 0 011 1v3M4 16v3a1 1 0 001 1h3m8 0h3a1 1 0 001-1v-3M8 8h8v8H8V8z" />
                                    </svg>
                                    Absen Pagi
                                    <span>›</span>
                                </div>
                            </a>
                            @else
                            <div class="dashboard-card rounded-[22px] border border-slate-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.12)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-slate-600">{{ $belumBukaMasuk ? 'BELUM' : 'BERAKHIR' }}</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Pagi</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-slate-600">{{ $belumBukaMasuk ? 'Belum dibuka' : 'Waktu berakhir' }}</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">{{ $belumBukaMasuk ? 'Mulai 06:00 WITA' : 'Kembali hari kerja berikutnya' }}</p>
                            </div>
                            @endif
                            {{-- SORE --}}
                            @if($isHariLibur)
                            <div class="dashboard-card rounded-[22px] border border-slate-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.14)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v2m6.36.64l-1.42 1.42M21 12h-2M17.36 18.36l-1.42-1.42M12 19v2m-5.36-2.64l1.42-1.42M5 12H3m4.64-4.64L6.22 5.94M8 12a4 4 0 108 0 4 4 0 00-8 0z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-slate-500">LIBUR</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-slate-600">Absen Sore</p>
                                <p class="mt-0.5 text-[8px] font-bold text-slate-400">Tidak tersedia</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Senin–Jumat · 16:00–17:00</p>
                            </div>
                            @elseif($sedangIzin)
                            <div class="dashboard-card rounded-[22px] border border-violet-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(124,58,237,.10)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-100">
                                        <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h4m4 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l6 6v10a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-violet-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-violet-700">
                                        IZIN
                                    </span>
                                </div>

                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Sore</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-violet-600">Tidak perlu absen</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Izin telah disetujui</p>
                            </div>

                            @elseif($sudahPulang)
                            <div class="dashboard-card rounded-[22px] border border-emerald-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.13)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-emerald-700">SELESAI</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Sore</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-emerald-600">{{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }} WITA</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Absensi pulang tercatat</p>
                            </div>
                            @elseif($pulangAktif)
                            <a href="{{ url('/kios-scan') }}" class="dashboard-card rounded-[22px] border border-orange-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(249,115,22,.16)] transition hover:-translate-y-0.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-100">
                                        <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="1.8" d="M12 6v6l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-orange-50 px-2 py-1 text-[6px] font-extrabold tracking-wide text-orange-700">AKTIF</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Sore</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-orange-600">16:00 – 17:00</p>
                                <div class="mt-2.5 flex h-7 items-center justify-center gap-1.5 rounded-full bg-[#ff852d] text-[7px] font-extrabold text-white shadow-sm">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 8V5a1 1 0 011-1h3m8 0h3a1 1 0 011 1v3M4 16v3a1 1 0 001 1h3m8 0h3a1 1 0 001-1v-3M8 8h8v8H8V8z" />
                                    </svg>
                                    Absen Sore
                                    <span>›</span>
                                </div>
                            </a>
                            @elseif($belumBukaPulang)
                            <div class="dashboard-card rounded-[22px] border border-slate-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.12)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50">
                                        <svg class="h-5 w-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-orange-50 px-2 py-1 text-[6px] font-extrabold tracking-wide text-orange-600">MENUNGGU</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-[#173c70]">Absen Sore</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-orange-600">Belum dibuka</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Mulai 16:00 WITA</p>
                            </div>
                            @else
                            <div class="dashboard-card rounded-[22px] border border-slate-200 bg-white p-3.5 shadow-[0_12px_30px_rgba(15,47,89,.10)]">
                                <div class="flex items-center justify-between">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[6px] font-extrabold tracking-wide text-slate-600">BERAKHIR</span>
                                </div>
                                <p class="mt-3 text-[10px] font-extrabold text-slate-600">Absen Sore</p>
                                <p class="mt-0.5 text-[8px] font-extrabold text-slate-500">Waktu berakhir</p>
                                <p class="mt-1 text-[7px] font-medium text-slate-400">Kembali hari kerja berikutnya</p>
                            </div>
                            @endif
                        </div>
                    </section>
                    {{-- PRESENSI HARI INI --}}
                    <section class="px-4 pt-6">
                        <div class="mb-3 flex items-end justify-between">
                            <div>
                                <h2 class="text-[14px] font-extrabold text-[#173c70]">Presensi Hari Ini</h2>
                                <p class="mt-0.5 text-[8px] text-slate-400">
                                    {{ $isHariLibur ? 'Tidak ada presensi pada hari ini' : 'Pantau status kehadiran hari ini' }}
                                </p>
                            </div>
                            @if(!$isHariLibur)
                            <a href="{{ url('/riwayat') }}" class="text-[8px] font-bold text-blue-600">Lihat Semua ›</a>
                            @endif
                        </div>

                        @if($isHariLibur)
                        <div class="holiday-box dashboard-card rounded-[22px] border border-blue-100 bg-gradient-to-r from-[#edf6ff] to-[#e4f1ff] p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="relative flex h-16 w-16 shrink-0 items-center justify-center">
                                    <svg class="holiday-building" width="58" height="58" viewBox="0 0 64 64" fill="none">
                                        <path d="M8 56H56" stroke="#94BDE8" stroke-width="2" stroke-linecap="round" />
                                        <path d="M16 56V24L32 16L48 24V56" fill="#EAF4FF" stroke="#4B8BC9" stroke-width="2" />
                                        <path d="M22 30H27V35H22V30Z" fill="#8DBBE8" />
                                        <path d="M29 30H34V35H29V30Z" fill="#8DBBE8" />
                                        <path d="M37 30H42V35H37V30Z" fill="#8DBBE8" />
                                        <path d="M22 39H27V44H22V39Z" fill="#8DBBE8" />
                                        <path d="M29 39H34V44H29V39Z" fill="#8DBBE8" />
                                        <path d="M37 39H42V44H37V39Z" fill="#8DBBE8" />
                                        <path d="M28 56V48H36V56" fill="#4B8BC9" />
                                        <circle cx="47" cy="13" r="5" fill="#FFD76A" class="holiday-sun" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-extrabold text-[#173c70]">Nikmati Hari Libur ✨</p>
                                    <p class="mt-1 text-[7px] leading-relaxed text-slate-400">
                                        Tidak ada jadwal absensi pada hari ini. Manfaatkan waktu untuk hal-hal yang kamu suka.
                                    </p>
                                </div>
                            </div>
                        </div>

                        @elseif($sedangIzin)
                        <div class="rounded-[22px] border border-violet-100 bg-violet-50 p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white">
                                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M9 12h6m-6 4h4m4 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l6 6v10a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-extrabold text-violet-800">
                                        Izin Telah Disetujui
                                    </p>
                                    <p class="mt-1 text-[8px] leading-relaxed text-violet-600">
                                        Anda sedang dalam masa izin. Absensi pagi dan sore tidak perlu dilakukan.
                                    </p>
                                    <p class="mt-2 text-[8px] font-bold text-violet-700">
                                        {{ ucwords(str_replace('_', ' ', $izinAktif->jenis_izin)) }}
                                    </p>
                                    <p class="mt-1 text-[7px] text-violet-500">
                                        {{ \Carbon\Carbon::parse($izinAktif->tanggal_mulai)->translatedFormat('d F Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($izinAktif->tanggal_selesai)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @else
                        <div class="space-y-2.5">

                            {{-- ABSENSI MASUK --}}
                            <div class="flex items-center gap-3 rounded-[20px] border border-slate-100 bg-white px-3.5 py-3 shadow-sm">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-50">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M7 3v3m10-3v3M4 10h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1h14" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] font-extrabold text-[#173c70]">Absensi Masuk</p>
                                    <p class="mt-0.5 text-[7px] text-slate-400">
                                        @if($sudahMasuk)
                                        Masuk pukul {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }} WITA
                                        @elseif($belumBukaMasuk)
                                        Belum dibuka · mulai 06:00 WITA
                                        @elseif($berakhirMasuk)
                                        Waktu absensi telah berakhir
                                        @else
                                        Belum melakukan absensi
                                        @endif
                                    </p>
                                </div>

                                @if($sudahMasuk)
                                <span class="rounded-full {{ $absensiHariIni->status === 'terlambat' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} px-2.5 py-1 text-[7px] font-bold">
                                    {{ $absensiHariIni->status === 'terlambat' ? 'Terlambat' : 'Hadir' }}
                                </span>
                                @elseif($masukAktif)
                                <a href="{{ url('/kios-scan') }}" class="rounded-full bg-blue-50 px-2.5 py-1 text-[7px] font-bold text-blue-600">
                                    Absen
                                </a>
                                @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[7px] font-semibold text-slate-400">
                                    {{ $belumBukaMasuk ? 'Belum' : 'Berakhir' }}
                                </span>
                                @endif
                            </div>

                            {{-- ABSENSI PULANG --}}
                            <div class="flex items-center gap-3 rounded-[20px] border border-slate-100 bg-white px-3.5 py-3 shadow-sm">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $sudahPulang ? 'bg-emerald-50' : 'bg-orange-50' }}">
                                    <svg class="h-5 w-5 {{ $sudahPulang ? 'text-emerald-600' : 'text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-width="1.8"
                                            d="M12 6v6l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] font-extrabold text-[#173c70]">Absensi Pulang</p>
                                    <p class="mt-0.5 text-[7px] text-slate-400">
                                        @if($sudahPulang)
                                        Pulang pukul {{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }} WITA
                                        @elseif(!$sudahMasuk)
                                        Menunggu absensi masuk
                                        @elseif($belumBukaPulang)
                                        Belum dibuka · mulai 16:00 WITA
                                        @elseif($pulangAktif)
                                        Waktu pulang sedang aktif
                                        @else
                                        Waktu absensi telah berakhir
                                        @endif
                                    </p>
                                </div>

                                @if($sudahPulang)
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[7px] font-bold text-emerald-600">
                                    Selesai
                                </span>
                                @elseif($pulangAktif)
                                <a href="{{ url('/kios-scan') }}" class="rounded-full bg-orange-50 px-2.5 py-1 text-[7px] font-bold text-orange-600">
                                    Absen
                                </a>
                                @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[7px] font-semibold text-slate-400">
                                    {{ $belumBukaPulang ? 'Belum' : 'Berakhir' }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </section>
                    {{-- RINGKASAN KEHADIRAN --}}
                    <section class="px-4 pt-6">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-[14px] font-extrabold text-[#173c70]">Ringkasan Kehadiran</h2>
                                <p class="mt-0.5 text-[8px] text-slate-400">Rekap absensi bulan ini</p>
                            </div>
                            <a href="{{ url('/riwayat') }}" class="flex items-center gap-1 rounded-full bg-white px-3 py-2 text-[7px] font-bold text-blue-600 shadow-sm">
                                Lihat Detail
                                <span class="text-sm leading-none">›</span>
                            </a>
                        </div>
                        <div class="dashboard-soft-shadow rounded-[24px] border border-white bg-white p-3">
                            <div class="grid grid-cols-4 gap-1.5">
                                {{-- HADIR --}}
                                <div class="rounded-[16px] border border-emerald-200 bg-emerald-50 px-1 py-2.5 text-center shadow-sm">
                                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="mt-1.5 text-[7px] font-bold text-[#173c70]">Hadir</p>
                                    <p class="text-[19px] font-extrabold leading-none text-emerald-600">{{ $hadir }}</p>
                                    <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                                </div>
                                {{-- TERLAMBAT --}}
                                <div class="rounded-[16px] border border-orange-200 bg-orange-50 px-1 py-2.5 text-center shadow-sm">
                                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-orange-100">
                                        <svg class="h-3.5 w-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="2.3" d="M12 8v4l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="mt-1.5 text-[7px] font-bold text-[#173c70]">Terlambat</p>
                                    <p class="text-[19px] font-extrabold leading-none text-orange-600">{{ $terlambat }}</p>
                                    <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                                </div>
                                {{-- IZIN --}}
                                <div class="rounded-[16px] border border-violet-200 bg-violet-50 px-1 py-2.5 text-center shadow-sm">
                                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-violet-100">
                                        <svg class="h-3.5 w-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="2" d="M9 12h6m-6 4h4m4 8H8a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="mt-1.5 text-[7px] font-bold text-[#173c70]">Izin</p>
                                    <p class="text-[19px] font-extrabold leading-none text-violet-600">{{ $izin }}</p>
                                    <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                                </div>
                                {{-- TIDAK HADIR --}}
                                <div class="rounded-[16px] border border-red-200 bg-red-50 px-1 py-2.5 text-center shadow-sm">
                                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-red-100">
                                        <svg class="h-3.5 w-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <p class="mt-1.5 text-[7px] font-bold text-[#173c70]">Tidak Hadir</p>
                                    <p class="text-[19px] font-extrabold leading-none text-red-600">{{ $alpa }}</p>
                                    <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                                </div>
                            </div>
                            <div class="mt-3 rounded-[16px] bg-slate-50 px-3 py-2.5">
                                <div class="mb-1.5 flex items-center justify-between">
                                    <span class="text-[7px] font-semibold text-[#173c70]">Persentase Kehadiran</span>
                                    <span class="text-[9px] font-extrabold text-[#173c70]">{{ $persentaseKehadiran }}%</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="dashboard-progress h-full rounded-full" style="width: {{ min($persentaseKehadiran, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    {{-- RIWAYAT TERBARU --}}
                    <section class="px-4 pt-6">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-[14px] font-extrabold text-[#173c70]">Riwayat Terbaru</h2>
                                <p class="mt-0.5 text-[8px] text-slate-400">Aktivitas absensi Anda</p>
                            </div>
                            <a href="{{ url('/riwayat') }}" class="flex items-center gap-1 rounded-full bg-white px-3 py-2 text-[7px] font-bold text-blue-600 shadow-sm">
                                Lihat Semua
                                <span class="text-sm leading-none">›</span>
                            </a>
                        </div>
                        <div class="space-y-2.5">
                            @forelse($riwayatTerbaru->take(2) as $riwayat)
                            @php
                            $tanggalRiwayat = \Carbon\Carbon::parse($riwayat->tanggal ?? $riwayat->created_at);
                            if ($riwayat->tipe === 'izin') {
                            $statusRiwayat = $riwayat->status === 'disetujui'
                            ? 'Disetujui'
                            : ($riwayat->status === 'ditolak' ? 'Ditolak' : 'Menunggu');
                            } else {
                            $statusRiwayat = $riwayat->status === 'terlambat' ? 'Terlambat' : 'Hadir';
                            }
                            @endphp
                            <div class="dashboard-card flex items-center gap-3 rounded-[22px] border border-slate-100 bg-white px-3.5 py-3.5 shadow-sm">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $riwayat->tipe === 'izin' ? 'bg-violet-50' : ($statusRiwayat === 'Terlambat' ? 'bg-orange-50' : 'bg-blue-50') }}">
                                    @if($riwayat->tipe === 'izin')
                                    <svg class="h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L19 7.5V19a2 2 0 01-2 2z" />
                                    </svg>
                                    @elseif($statusRiwayat === 'Terlambat')
                                    <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-width="1.8" d="M12 6v6l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @else
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-width="1.8" d="M7 3v3m10-3v3M4 10h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1h14" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-extrabold text-[#173c70]">
                                        {{ $riwayat->tipe === 'izin' ? 'Pengajuan Izin' : 'Absensi' }}
                                    </p>
                                    <p class="mt-0.5 truncate text-[7px] text-slate-400">
                                        {{ $tanggalRiwayat->translatedFormat('d F Y') }}
                                        @if($riwayat->tipe === 'absensi' && $riwayat->jam_masuk)
                                        · Masuk {{ \Carbon\Carbon::parse($riwayat->jam_masuk)->format('H:i') }} WITA
                                        @elseif($riwayat->tipe === 'izin' && $riwayat->jenis_izin)
                                        · {{ ucwords(str_replace('_', ' ', $riwayat->jenis_izin)) }}
                                        @endif
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full {{ $statusRiwayat === 'Terlambat' ? 'bg-orange-50 text-orange-600' : ($statusRiwayat === 'Disetujui' ? 'bg-emerald-50 text-emerald-600' : ($statusRiwayat === 'Menunggu' ? 'bg-amber-50 text-amber-600' : ($statusRiwayat === 'Ditolak' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'))) }} px-2.5 py-1 text-[7px] font-bold">
                                    {{ $statusRiwayat }}
                                </span>
                            </div>
                            @empty
                            <div class="rounded-[22px] border border-dashed border-slate-200 bg-white px-4 py-7 text-center">
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3v3m10-3v3M4 10h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1h14" />
                                    </svg>
                                </div>
                                <p class="mt-2 text-[9px] font-bold text-slate-500">Belum ada riwayat</p>
                                <p class="mt-0.5 text-[7px] text-slate-400">Aktivitas absensi akan muncul di sini.</p>
                            </div>
                            @endforelse
                        </div>
                    </section>
                    {{-- AJUKAN IZIN --}}
                    <section class="px-4 pb-4 pt-5">
                        <a href="{{ url('/izin') }}" class="dashboard-card relative flex items-center gap-3 overflow-hidden rounded-[24px] border border-blue-100 bg-gradient-to-r from-[#f8fbff] via-white to-[#e8f3ff] px-4 py-4 shadow-sm">
                            <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-100">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14" />
                                </svg>
                            </div>
                            <div class="relative min-w-0 flex-1">
                                <p class="text-[10px] font-extrabold text-[#173c70]">Ajukan Izin</p>
                                <p class="mt-0.5 text-[8px] text-slate-400">Tidak dapat hadir? Ajukan izin melalui sistem.</p>
                            </div>
                            <div class="relative flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white shadow-sm">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    </section>
                </div>
                @endsection