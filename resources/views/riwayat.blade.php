@extends('layouts.app')
@section('title', 'Riwayat - SI-ABSEN DISKOMINFO')
@section('page_title', 'Riwayat')
@section('content')
@php
$izinDisetujui = $izin->where('status', 'disetujui');
$tanggalIzinDisetujui = collect();
foreach ($izinDisetujui as $izinItem) {
if (!$izinItem->tanggal_mulai) {
continue;
}
$mulaiIzin = \Carbon\Carbon::parse($izinItem->tanggal_mulai, 'Asia/Makassar')->startOfDay();
$selesaiIzin = $izinItem->tanggal_selesai
? \Carbon\Carbon::parse($izinItem->tanggal_selesai, 'Asia/Makassar')->startOfDay()
: $mulaiIzin->copy();
while ($mulaiIzin->lte($selesaiIzin)) {
if ($mulaiIzin->betweenIncluded(
$tanggalBulan->copy()->startOfMonth(),
$tanggalBulan->copy()->endOfMonth()
)) {
$tanggalIzinDisetujui->push($mulaiIzin->toDateString());
}
$mulaiIzin->addDay();
}
}
$tanggalIzinDisetujui = $tanggalIzinDisetujui->unique()->values();
@endphp
<div class="min-h-screen bg-[#f5f8fd] pb-8">
    {{-- HEADER --}}
    @php
    $headerImage = file_exists(public_path('images/dashboard-header.jpg'))
    ? asset('images/dashboard-header.jpg')
    : null;
    $fotoProfil = $pegawai->foto
    ? asset('storage/' . $pegawai->foto)
    : asset('images/default-profile.png');
    @endphp
    <style>
        .riwayat-hero {
            background: linear-gradient(180deg, rgba(20, 99, 181, .94) 0%, rgba(45, 125, 203, .86) 48%, rgba(38, 105, 177, .97) 100%);
        }

        .riwayat-hero.has-image {
            background-image: linear-gradient(180deg, rgba(24, 108, 190, .82) 0%, rgba(43, 126, 201, .62) 46%, rgba(15, 71, 130, .90) 100%),
            url('{{ $headerImage }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    <section class="riwayat-hero {{ $headerImage ? 'has-image' : '' }} relative overflow-hidden rounded-b-[34px] text-white">
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
                    <div>
                        <p class="text-[15px] font-extrabold leading-none tracking-tight text-white">
                            DISKOMINFO
                        </p>
                        <p class="mt-1 text-[8px] font-bold text-white/90">
                            Kota Banjarmasin
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- NOTIFIKASI --}}
                    <a href="{{ route('notifications.index') }}"
                        aria-label="Notifikasi"
                        class="relative flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-white/15 backdrop-blur-sm transition active:scale-95">
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
                    {{-- FOTO PROFIL --}}
                    <a href="{{ url('/profil') }}"
                        aria-label="Profil"
                        class="block h-9 w-9 overflow-hidden rounded-full border-2 border-white/70 bg-white/20 shadow-sm backdrop-blur-sm transition active:scale-95">
                        <img src="{{ $fotoProfil }}"
                            alt="Foto Profil"
                            class="h-full w-full object-cover">
                    </a>
                </div>
            </div>
            {{-- JUDUL HALAMAN --}}
            <div class="mt-7 flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 shadow-sm backdrop-blur-sm">
                    <svg class="h-5 w-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1Zm4 8h2m2 0h2m-6 4h2m2 0h2" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-[20px] font-extrabold leading-tight tracking-tight">
                        Riwayat Absensi
                    </h1>
                    <p class="mt-1 text-[8px] font-medium text-white/75">
                        Pantau catatan kehadiran dan pengajuan izin Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>
    {{-- CONTENT --}}
    <div class="-mt-2 space-y-4 px-4 pt-5">
        {{-- RINGKASAN --}}
        <section>
            <div class="mb-2.5">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-blue-50">
                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[14px] font-extrabold leading-tight text-[#102f59]">Ringkasan Kehadiran</h2>
                        <p class="mt-0.5 text-[8px] text-slate-400">Rekap status absensi pada periode yang dipilih</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-1.5">
                {{-- HADIR --}}
                <div class="rounded-[16px] border border-emerald-200 bg-emerald-50 px-1 py-2.5 text-center shadow-sm">
                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100">
                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h4m4 8H8a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="mt-1.5 text-[7px] font-bold text-[#173c70]">Izin</p>
                    <p class="text-[19px] font-extrabold leading-none text-violet-600">{{ $izinCount }}</p>
                    <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                </div>
                {{-- TIDAK HADIR --}}
                <div class="rounded-[16px] border border-red-200 bg-red-50 px-1 py-2.5 text-center shadow-sm">
                    <div class="mx-auto flex h-7 w-7 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-3.5 w-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <p class="mt-1.5 text-[7px] font-bold text-[#173c70]">Tidak Hadir</p>
                    <p class="text-[19px] font-extrabold leading-none text-red-600">{{ $alpa }}</p>
                    <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                </div>
            </div>
        </section>
        {{-- KALENDER --}}
        <section class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[8px] font-semibold uppercase tracking-wider text-slate-400">Rekap Bulanan</p>
                        <h3 class="mt-0.5 text-[17px] font-extrabold leading-tight text-[#102f59]">
                            {{ $tanggalBulan->translatedFormat('F Y') }}
                        </h3>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('riwayat', ['bulan' => $bulanSebelumnya]) }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 transition active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <a href="{{ route('riwayat', ['bulan' => $bulanBerikutnya]) }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 transition active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="px-3 py-3.5">
                <div class="mb-1 grid grid-cols-7">
                    @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $hari)
                    <div class="py-1 text-center text-[8px] font-bold uppercase text-slate-400">{{ $hari }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-y-1.5">
                    @foreach($kalender as $item)
                    @php
                    $tanggal = $item['tanggal'];
                    $absen = $item['absensi'];
                    $isToday = $tanggal->isSameDay($sekarang);
                    $izinHariIni = $izinDisetujui->filter(function ($izinItem) use ($tanggal) {
                    if ($izinItem->tanggal_mulai) {
                    $mulai = \Carbon\Carbon::parse($izinItem->tanggal_mulai, 'Asia/Makassar')->startOfDay();
                    $selesai = $izinItem->tanggal_selesai
                    ? \Carbon\Carbon::parse($izinItem->tanggal_selesai, 'Asia/Makassar')->startOfDay()
                    : $mulai->copy();
                    return $tanggal->betweenIncluded($mulai, $selesai);
                    }
                    return $tanggal->isSameDay(\Carbon\Carbon::parse($izinItem->created_at, 'Asia/Makassar'));
                    })->first();
                    $status = $absen?->status;
                    if ($izinHariIni) {
                    $status = 'izin';
                    }
                    $isHariKerja = $tanggal->isWeekday();
                    $isSebelumMulaiAktif = $tanggal->lt($tanggalMulaiAktif);
                    $isHariTerlewat = $tanggal->lt($sekarang->copy()->startOfDay());
                    $isHariIniSelesai = $tanggal->isSameDay($sekarang)
                    && $sekarang->format('H:i:s') >= '17:00:00';

                    $isTidakHadir = $item['bulanAktif']
                    && !$isSebelumMulaiAktif
                    && $isHariKerja
                    && ($isHariTerlewat || $isHariIniSelesai)
                    && !$status;

                    if ($isTidakHadir) {
                    $status = 'alpa';
                    }
                    $detail = [
                    'tanggal' => $tanggal->translatedFormat('d F Y'),
                    'status' => $status,
                    'jam_masuk' => $absen?->jam_masuk,
                    'jam_pulang' => $absen?->jam_pulang,
                    'jenis_izin' => $izinHariIni?->jenis_izin,
                    'status_izin' => $izinHariIni?->status,
                    'keterangan' => $izinHariIni?->keterangan,
                    ];
                    $detailEncoded = base64_encode(json_encode($detail));
                    @endphp
                    <div class="flex justify-center">
                        @if($item['bulanAktif'])
                        <button type="button" onclick="bukaDetailTanggal(this)" data-detail="{{ $detailEncoded }}" class="relative flex h-9 w-9 flex-col items-center justify-center rounded-xl transition active:scale-95
                            {{ $isToday ? 'ring-1 ring-[#1769e0] ring-offset-1' : '' }}
                            {{ $status === 'hadir' ? 'bg-emerald-50' : '' }}
                            {{ $status === 'terlambat' ? 'bg-orange-50' : '' }}
                            {{ $status === 'izin' ? 'bg-violet-50' : '' }}
                            {{ $status === 'alpa' ? 'bg-red-50' : '' }}
                            {{ !$status && !$isToday ? 'bg-slate-50' : '' }}">
                            <span class="text-[10px] font-extrabold
                                {{ $status === 'hadir' ? 'text-emerald-700' : '' }}
                                {{ $status === 'terlambat' ? 'text-orange-700' : '' }}
                                {{ $status === 'izin' ? 'text-violet-700' : '' }}
                                {{ $status === 'alpa' ? 'text-red-700' : '' }}
                                {{ !$status ? 'text-slate-600' : '' }}">
                                {{ $tanggal->day }}
                            </span>
                            @if($status === 'hadir')
                            <span class="absolute bottom-1 h-1 w-1 rounded-full bg-emerald-500"></span>
                            @elseif($status === 'terlambat')
                            <span class="absolute bottom-1 h-1 w-1 rounded-full bg-orange-500"></span>
                            @elseif($status === 'izin')
                            <span class="absolute bottom-1 h-1 w-1 rounded-full bg-violet-500"></span>
                            @elseif($status === 'alpa')
                            <span class="absolute bottom-1 h-1 w-1 rounded-full bg-red-500"></span>
                            @endif
                        </button>
                        @else
                        <div class="flex h-9 w-9 items-center justify-center">
                            <span class="text-[9px] text-slate-300">{{ $tanggal->day }}</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                {{-- LEGEND --}}
                <div class="mt-4 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 border-t border-slate-100 pt-3">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-[9px] text-slate-500">Hadir</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                        <span class="text-[9px] text-slate-500">Terlambat</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                        <span class="text-[9px] text-slate-500">Izin</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        <span class="text-[9px] text-slate-500">Tidak Hadir</span>
                    </div>
                </div>
            </div>
        </section>
        [ ]+$
        {{-- RIWAYAT ABSENSI --}}
        <section>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                {{-- HEADER --}}
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#17365D]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-bold text-[#17365D]">Riwayat Absensi</h3>
                        <p class="mt-0.5 text-[9px] text-slate-500">Catatan kehadiran terbaru</p>
                    </div>
                </div>

                {{-- DAFTAR RIWAYAT --}}
                <div class="max-h-[390px] overflow-y-auto overscroll-contain bg-white">
                    @if($riwayatAbsensi->count())
                    <div class="divide-y divide-slate-100">
                        @foreach($riwayatAbsensi as $item)
                        <div class="px-4 py-3">
                            {{-- INFORMASI UTAMA ABSENSI --}}
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                    @if($item->status === 'hadir')
                                    <svg class="h-5 w-5 text-[#17365D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    @elseif($item->status === 'terlambat')
                                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @elseif($item->status === 'alpa')
                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    @else
                                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                                    </svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($item->tanggal, 'Asia/Makassar')->translatedFormat('d F Y') }}
                                    </p>

                                    @if($item->status === 'alpa')
                                    <p class="mt-1 text-[9px] leading-relaxed text-slate-500">
                                        {{ $item->keterangan ?? 'Tidak melakukan presensi pada hari kerja ini.' }}
                                    </p>
                                    @else
                                    <p class="mt-1 text-[9px] leading-relaxed text-slate-500">
                                        @if($item->jam_masuk)
                                        Masuk {{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }} WITA
                                        @else
                                        Tidak ada jam masuk
                                        @endif

                                        @if($item->jam_pulang)
                                        <span class="mx-1">·</span>
                                        Pulang {{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') }} WITA
                                        @endif
                                    </p>
                                    @endif
                                </div>

                                {{-- STATUS ABSENSI --}}
                                @if($item->status === 'hadir')
                                <span class="shrink-0 rounded-md bg-emerald-50 px-2 py-1 text-[8px] font-semibold text-emerald-700">
                                    Hadir
                                </span>
                                @elseif($item->status === 'terlambat')
                                <span class="shrink-0 rounded-md bg-amber-50 px-2 py-1 text-[8px] font-semibold text-amber-700">
                                    Terlambat
                                </span>
                                @elseif($item->status === 'alpa')
                                <span class="shrink-0 rounded-md bg-red-50 px-2 py-1 text-[8px] font-semibold text-red-700">
                                    Tidak Hadir
                                </span>
                                @else
                                <span class="shrink-0 rounded-md bg-slate-100 px-2 py-1 text-[8px] font-semibold text-slate-600">
                                    {{ ucfirst($item->status) }}
                                </span>
                                @endif
                            </div>

                            {{-- DETAIL VERIFIKASI ABSENSI --}}
                            @if($item->status !== 'alpa')
                            @php
                            $detailVerifikasi = $item->verifikasis ?? collect();
                            $verifikasiMasuk = $detailVerifikasi->firstWhere('tipe_absensi', 'masuk');
                            $verifikasiPulang = $detailVerifikasi->firstWhere('tipe_absensi', 'pulang');
                            @endphp

                            <details class="mt-3 overflow-hidden rounded-xl border border-blue-100 bg-blue-50/60">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-3 py-2.5 text-[10px] font-bold text-blue-800">
                                    <span class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v2m7 7h2M3 12H1m11 7v2m6.36-3.64 1.42 1.42M5.22 5.22 3.8 3.8m12.4 0-1.42 1.42M5.22 18.78 3.8 20.2M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                        </svg>
                                        Detail Verifikasi Absensi
                                    </span>
                                    <span class="text-[9px] font-medium text-blue-600">
                                        Lihat detail
                                    </span>
                                </summary>

                                <div class="space-y-3 border-t border-blue-100 px-3 py-3">
                                    @foreach([
                                    ['label' => 'Absensi Masuk', 'data' => $verifikasiMasuk],
                                    ['label' => 'Absensi Pulang', 'data' => $verifikasiPulang]
                                    ] as $verifikasiItem)
                                    <div class="rounded-lg border border-white bg-white/80 p-3">
                                        <p class="mb-2 text-[10px] font-extrabold text-slate-700">
                                            {{ $verifikasiItem['label'] }}
                                        </p>

                                        @if($verifikasiItem['data'])
                                        @php
                                        $verifikasi = $verifikasiItem['data'];
                                        @endphp

                                        <div class="grid grid-cols-2 gap-x-3 gap-y-2">
                                            {{-- WAKTU VERIFIKASI --}}
                                            <div>
                                                <p class="text-[8px] text-slate-400">Waktu verifikasi</p>
                                                <p class="mt-0.5 text-[9px] font-semibold text-slate-700">
                                                    {{ $verifikasi->verified_at ? \Carbon\Carbon::parse($verifikasi->verified_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') . ' WITA' : '-' }}
                                                </p>
                                            </div>

                                            {{-- JARAK KANTOR --}}
                                            <div>
                                                <p class="text-[8px] text-slate-400">Jarak dari kantor</p>
                                                <p class="mt-0.5 text-[9px] font-semibold text-slate-700">
                                                    {{ $verifikasi->jarak_meter !== null ? number_format((float) $verifikasi->jarak_meter, 2, ',', '.') . ' meter' : '-' }}
                                                </p>
                                            </div>

                                            {{-- AKURASI GPS --}}
                                            <div>
                                                <p class="text-[8px] text-slate-400">Akurasi GPS</p>
                                                <p class="mt-0.5 text-[9px] font-semibold text-slate-700">
                                                    {{ $verifikasi->akurasi_gps !== null ? number_format((float) $verifikasi->akurasi_gps, 2, ',', '.') . ' meter' : '-' }}
                                                </p>
                                            </div>

                                            {{-- FACE RECOGNITION --}}
                                            <div>
                                                <p class="text-[8px] text-slate-400">Face Recognition</p>
                                                <p class="mt-0.5 text-[9px] font-semibold {{ $verifikasi->face_match_status === 'matched' ? 'text-emerald-600' : 'text-red-600' }}">
                                                    {{ $verifikasi->face_match_status === 'matched' ? 'Wajah cocok' : ucfirst($verifikasi->face_match_status ?? 'Tidak diketahui') }}
                                                </p>
                                            </div>

                                            {{-- NILAI KEC0COKAN WAJAH --}}
                                            <div>
                                                <p class="text-[8px] text-slate-400">Nilai kecocokan wajah</p>
                                                <p class="mt-0.5 text-[9px] font-semibold text-slate-700">
                                                    {{ $verifikasi->face_match_distance !== null ? number_format((float) $verifikasi->face_match_distance, 5, ',', '.') : '-' }}
                                                </p>
                                            </div>

                                            {{-- LIVENESS DETECTION --}}
                                            <div>
                                                <p class="text-[8px] text-slate-400">Liveness Detection</p>
                                                <p class="mt-0.5 text-[9px] font-semibold {{ $verifikasi->liveness_status === 'passed' ? 'text-emerald-600' : 'text-red-600' }}">
                                                    {{ $verifikasi->liveness_status === 'passed' ? 'Berhasil' : ucfirst($verifikasi->liveness_status ?? 'Tidak diketahui') }}
                                                </p>
                                            </div>

                                            {{-- VALIDASI LOKASI --}}
                                            <div class="col-span-2">
                                                <p class="text-[8px] text-slate-400">Validasi lokasi</p>
                                                <p class="mt-0.5 text-[9px] font-semibold {{ $verifikasi->location_status === 'within_radius' ? 'text-emerald-600' : 'text-red-600' }}">
                                                    {{ $verifikasi->location_status === 'within_radius' ? 'Di dalam radius kantor' : ucfirst(str_replace('_', ' ', $verifikasi->location_status ?? 'Tidak diketahui')) }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- LINK GOOGLE MAPS --}}
                                        @if($verifikasi->latitude !== null && $verifikasi->longitude !== null)
                                        <a
                                            href="https://www.google.com/maps?q={{ $verifikasi->latitude }},{{ $verifikasi->longitude }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-2 text-[9px] font-bold text-blue-700">
                                            Lihat lokasi di Google Maps
                                        </a>
                                        @endif
                                        @else
                                        <p class="text-[9px] text-slate-400">
                                            Belum ada data verifikasi untuk absensi ini.
                                        </p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </details>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    {{-- EMPTY STATE --}}
                    <div class="px-4 py-8 text-center">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1-1V6a1 1 0 011-1h14" />
                            </svg>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-slate-700">Belum ada absensi</p>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Belum ada data presensi pada bulan ini.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- RIWAYAT IZIN --}}
        <section>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#17365D]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h4m4 8H8a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-bold text-[#17365D]">Riwayat Izin</h3>
                        <p class="mt-0.5 text-[9px] text-slate-500">Catatan pengajuan izin terbaru</p>
                    </div>
                </div>
                <div class="max-h-[390px] overflow-y-auto overscroll-contain bg-white">
                    @if($izin->count())
                    <div class="divide-y divide-slate-100">
                        @foreach($izin as $item)
                        <div class="px-4 py-3">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                    <svg class="h-5 w-5 text-[#17365D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h4m4 8H8a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-bold text-slate-800">
                                                @switch($item->jenis_izin)
                                                @case('sakit') Sakit @break
                                                @case('keperluan_keluarga') Keperluan Keluarga @break
                                                @case('keperluan_dinas') Keperluan Dinas @break
                                                @case('keperluan_pribadi') Keperluan Pribadi @break
                                                @case('izin_terlambat') Izin Terlambat @break
                                                @default {{ ucfirst(str_replace('_', ' ', $item->jenis_izin)) }}
                                                @endswitch
                                            </p>
                                            <p class="mt-1 text-[9px] text-slate-500">
                                                Diajukan {{ $item->created_at?->translatedFormat('d F Y') ?? '-' }}
                                            </p>
                                        </div>
                                        @if($item->status === 'menunggu')
                                        <span class="shrink-0 rounded-md bg-amber-50 px-2 py-1 text-[8px] font-semibold text-amber-700">Menunggu</span>
                                        @elseif($item->status === 'disetujui')
                                        <span class="shrink-0 rounded-md bg-emerald-50 px-2 py-1 text-[8px] font-semibold text-emerald-700">Disetujui</span>
                                        @elseif($item->status === 'ditolak')
                                        <span class="shrink-0 rounded-md bg-red-50 px-2 py-1 text-[8px] font-semibold text-red-700">Ditolak</span>
                                        @else
                                        <span class="shrink-0 rounded-md bg-slate-100 px-2 py-1 text-[8px] font-semibold text-slate-600">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </div>
                                    @if($item->keterangan)
                                    <p class="mt-2 text-[10px] leading-relaxed text-slate-600">{{ $item->keterangan }}</p>
                                    @endif
                                    @if($item->tanggal_mulai)
                                    <div class="mt-2 flex items-center gap-1.5">
                                        <svg class="h-3 w-3 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1-1V6a1 1 0 011-1h14" />
                                        </svg>
                                        <p class="text-[9px] leading-relaxed text-slate-500">
                                            {{ $item->tanggal_mulai->translatedFormat('d F Y') }}
                                            @if($item->tanggal_selesai)
                                            — {{ $item->tanggal_selesai->translatedFormat('d F Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="px-4 py-9 text-center">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h4m4 8H8a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-slate-700">Belum ada pengajuan izin</p>
                        <p class="mt-1 text-[10px] text-slate-400">Riwayat pengajuan izin akan muncul di sini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        {{-- AJUKAN IZIN --}}
        <section class="pb-2">
            <a href="{{ url('/izin') }}" class="flex items-center gap-3 rounded-2xl border border-violet-100 bg-gradient-to-r from-white to-violet-50 px-3.5 py-3.5 shadow-sm transition active:scale-[0.99]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100">
                    <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-extrabold text-[#102f59]">Ajukan Izin</p>
                    <p class="mt-0.5 text-[8px] text-slate-400">Tidak dapat hadir? Ajukan izin melalui sistem.</p>
                </div>
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7" />
                </svg>
            </a>
        </section>
    </div>
    {{-- MODAL DETAIL TANGGAL --}}
    <div id="detailTanggalModal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" onclick="tutupDetailTanggal()"></div>
        <div class="absolute inset-x-3 bottom-24 mx-auto max-w-md">
            <div class="flex max-h-[70vh] flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
                <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div class="min-w-0 pr-3">
                        <p class="text-[9px] font-semibold uppercase tracking-wider text-slate-400">Detail Tanggal</p>
                        <h3 id="detailTanggal" class="mt-1 truncate text-base font-extrabold text-[#102f59]">-</h3>
                    </div>
                    <button type="button" onclick="tutupDetailTanggal()" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-3 overflow-y-auto p-5">
                    <div id="detailStatusBox" class="rounded-2xl bg-slate-50 px-4 py-4">
                        <p class="text-[9px] font-semibold uppercase tracking-wider text-slate-400">Status</p>
                        <p id="detailStatus" class="mt-1 text-xs font-extrabold text-slate-800">-</p>
                    </div>
                    <div id="detailJamBox" class="hidden grid-cols-2 gap-2">
                        <div class="rounded-2xl bg-slate-50 px-3.5 py-3">
                            <p class="text-[8px] font-semibold uppercase tracking-wider text-slate-400">Jam Masuk</p>
                            <p id="detailJamMasuk" class="mt-1 text-[11px] font-bold text-slate-800">-</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-3.5 py-3">
                            <p class="text-[8px] font-semibold uppercase tracking-wider text-slate-400">Jam Pulang</p>
                            <p id="detailJamPulang" class="mt-1 text-[11px] font-bold text-slate-800">-</p>
                        </div>
                    </div>
                    <div id="detailIzinBox" class="hidden space-y-2 rounded-2xl border border-violet-100 bg-violet-50 px-4 py-3.5">
                        <div>
                            <p class="text-[8px] font-semibold uppercase tracking-wider text-violet-400">Jenis Izin</p>
                            <p id="detailJenisIzin" class="mt-1 text-[11px] font-bold text-violet-800">-</p>
                        </div>
                        <div>
                            <p class="text-[8px] font-semibold uppercase tracking-wider text-violet-400">Status Pengajuan</p>
                            <p id="detailStatusIzin" class="mt-1 text-[11px] font-bold text-violet-800">-</p>
                        </div>
                        <div>
                            <p class="text-[8px] font-semibold uppercase tracking-wider text-violet-400">Keterangan</p>
                            <p id="detailKeterangan" class="mt-1 break-words text-[11px] leading-relaxed text-violet-900">-</p>
                        </div>
                    </div>
                    <button type="button" onclick="tutupDetailTanggal()" class="h-10 w-full rounded-xl bg-[#102f59] text-[10px] font-bold text-white shadow-sm transition active:scale-[0.98]">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function bukaDetailTanggal(button) {
        const modal = document.getElementById('detailTanggalModal');
        const data = JSON.parse(atob(button.dataset.detail));
        const statusMap = {
            hadir: ['Hadir', 'text-emerald-700', 'bg-emerald-50'],
            terlambat: ['Hadir — Terlambat', 'text-orange-700', 'bg-orange-50'],
            izin: ['Izin', 'text-violet-700', 'bg-violet-50'],
            alpa: ['Tidak Hadir', 'text-red-700', 'bg-red-50']
        };
        document.getElementById('detailTanggal').textContent = data.tanggal;
        const status = statusMap[data.status] || ['Belum Ada Aktivitas', 'text-slate-700', 'bg-slate-50'];
        const statusEl = document.getElementById('detailStatus');
        const statusBox = document.getElementById('detailStatusBox');
        statusEl.textContent = status[0];
        statusEl.className = 'mt-1 text-xs font-extrabold ' + status[1];
        statusBox.className = 'rounded-2xl px-4 py-4 ' + status[2];
        const jamBox = document.getElementById('detailJamBox');
        if (data.jam_masuk || data.jam_pulang) {
            jamBox.classList.remove('hidden');
            jamBox.classList.add('grid');
            document.getElementById('detailJamMasuk').textContent = data.jam_masuk || 'Belum ada';
            document.getElementById('detailJamPulang').textContent = data.jam_pulang || 'Belum ada';
        } else {
            jamBox.classList.add('hidden');
            jamBox.classList.remove('grid');
        }
        const izinBox = document.getElementById('detailIzinBox');
        if (data.jenis_izin) {
            izinBox.classList.remove('hidden');
            const jenisMap = {
                sakit: 'Sakit',
                keperluan_keluarga: 'Keperluan Keluarga',
                keperluan_dinas: 'Keperluan Dinas',
                keperluan_pribadi: 'Keperluan Pribadi',
                izin_terlambat: 'Izin Terlambat'
            };
            document.getElementById('detailJenisIzin').textContent = jenisMap[data.jenis_izin] || data.jenis_izin.replaceAll('_', ' ');
            document.getElementById('detailStatusIzin').textContent = data.status_izin ? data.status_izin.charAt(0).toUpperCase() + data.status_izin.slice(1) : '-';
            document.getElementById('detailKeterangan').textContent = data.keterangan || 'Tidak ada keterangan.';
        } else {
            izinBox.classList.add('hidden');
        }
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function tutupDetailTanggal() {
        document.getElementById('detailTanggalModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            tutupDetailTanggal();
        }
    });
</script>
@endpush