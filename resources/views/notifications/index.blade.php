@extends('layouts.app')
@section('title', 'Notifikasi - SI-ABSEN DISKOMINFO')
@section('page_title', 'Notifikasi')
@section('content')
@php
$sekarang = now('Asia/Makassar');
$hariIni = $sekarang->translatedFormat('l, d F Y');
$headerImage = file_exists(public_path('images/dashboard-header.jpg'))
? asset('images/dashboard-header.jpg')
: null;
$inisial = collect(explode(' ', trim(auth()->user()->pegawai->nama ?? 'User')))
->map(fn($n) => strtoupper(substr($n, 0, 1)))
->take(2)
->implode('');
$notifikasiHariIni = $notifikasi->filter(function ($item) {
return $item->created_at && $item->created_at->timezone('Asia/Makassar')->isToday();
});
$notifikasiSebelumnya = $notifikasi->filter(function ($item) {
return !$item->created_at || !$item->created_at->timezone('Asia/Makassar')->isToday();
});
@endphp
<style>
    .notification-page {
        background: #f5f8fd;
    }

    .notification-hero {
        background: linear-gradient(180deg, rgba(20, 99, 181, .94) 0%, rgba(45, 125, 203, .90) 50%, rgba(38, 105, 177, .98) 100%);
    }

    .notification-hero.has-image {
        background-image: linear-gradient(180deg, rgba(24, 108, 190, .78) 0%, rgba(43, 126, 201, .72) 50%, rgba(15, 71, 130, .92) 100%),
        url('{{ $headerImage }}');
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    .notification-soft-shadow {
        box-shadow: 0 8px 28px rgba(30, 76, 130, .08);
    }
</style>
<div class="notification-page min-h-screen pb-24">
    {{-- HEADER --}}
    <section class="notification-hero {{ $headerImage ? 'has-image' : '' }} relative overflow-hidden rounded-b-[28px] text-white">
        <div class="relative z-10 px-5 pb-8 pt-5">
            {{-- TOMBOL KEMBALI --}}
            <button type="button"
                onclick="history.back()"
                aria-label="Kembali"
                class="inline-flex h-8 items-center gap-1.5 rounded-full border border-white/25 bg-[#173c70]/80 px-3 pr-3.5 text-[10px] font-semibold tracking-wide text-white shadow-md backdrop-blur-md active:scale-95 transition">
                <svg class="h-3.5 w-3.5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.2"
                        d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </button>

            {{-- JUDUL --}}
            <div class="mt-7 flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-sm backdrop-blur-sm">
                    <svg class="h-5 w-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 17h5l-1.4-1.4A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-[20px] font-extrabold leading-tight tracking-tight">
                        Notifikasi
                    </h1>
                    <p class="mt-1 text-[9px] font-medium text-white/75">
                        Informasi absensi dan aktivitas akun Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>
    {{-- CONTENT --}}
    <main class="mx-auto max-w-[430px] px-4 pt-5">
        @if($jumlahBelumDibaca > 0)
        <div class="notification-soft-shadow mb-5 flex items-center justify-between rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5" />
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 20h6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        Ada notifikasi baru
                    </p>
                    <p class="text-[11px] text-slate-500">
                        {{ $jumlahBelumDibaca }} notifikasi belum dibaca.
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="text-xs font-bold text-blue-600 transition hover:text-blue-700">
                    Baca semua
                </button>
            </form>
        </div>
        @endif
        @if($notifikasi->count() === 0)
        <div class="notification-soft-shadow rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5" />
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 20h6" />
                </svg>
            </div>
            <h2 class="mt-5 text-base font-bold text-slate-800">
                Belum ada notifikasi
            </h2>
            <p class="mx-auto mt-2 max-w-[280px] text-xs leading-5 text-slate-500">
                Notifikasi absensi dan aktivitas akun kamu akan muncul di halaman ini.
            </p>
        </div>
        @else
        @if($notifikasiHariIni->count() > 0)
        <section>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Hari Ini
                </h2>
                <span class="text-[10px] text-slate-400">
                    {{ $notifikasiHariIni->count() }} notifikasi
                </span>
            </div>
            <div class="space-y-3">
                @foreach($notifikasiHariIni as $item)
                @php
                $tipe = $item->tipe;
                $ikon = match ($tipe) {
                'absensi' => '✓',
                'izin' => 'I',
                'schedule' => '◷',
                default => 'i',
                };
                $ikonClass = match ($tipe) {
                'absensi' => 'bg-emerald-100 text-emerald-600',
                'izin' => 'bg-amber-100 text-amber-600',
                'schedule' => 'bg-blue-100 text-blue-600',
                default => 'bg-slate-100 text-slate-600',
                };
                @endphp
                <div class="relative rounded-2xl border {{ $item->dibaca ? 'border-slate-200 bg-white' : 'border-blue-100 bg-blue-50/60' }} p-4 shadow-sm">
                    @if(!$item->dibaca)
                    <span class="absolute right-4 top-4 h-2 w-2 rounded-full bg-blue-600"></span>
                    @endif
                    <div class="flex gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $ikonClass }} text-sm font-bold">
                            {{ $ikon }}
                        </div>
                        <div class="min-w-0 flex-1 pr-4">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-sm font-bold text-slate-800">
                                    {{ $item->judul }}
                                </h3>
                                <span class="shrink-0 text-[10px] text-slate-400">
                                    {{ $item->created_at->timezone('Asia/Makassar')->format('H:i') }} WITA
                                </span>
                            </div>
                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                {{ $item->pesan }}
                            </p>
                            @if(!$item->dibaca)
                            <form method="POST"
                                action="{{ route('notifications.read', $item->id) }}"
                                class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-[11px] font-bold text-blue-600">
                                    Tandai sudah dibaca
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
        @if($notifikasiSebelumnya->count() > 0)
        <section class="mt-7">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Sebelumnya
                </h2>
                <span class="text-[10px] text-slate-400">
                    {{ $notifikasiSebelumnya->count() }} notifikasi
                </span>
            </div>
            <div class="space-y-3">
                @foreach($notifikasiSebelumnya as $item)
                @php
                $tipe = $item->tipe;
                $ikon = match ($tipe) {
                'absensi' => '✓',
                'izin' => 'I',
                'schedule' => '◷',
                default => 'i',
                };
                $ikonClass = match ($tipe) {
                'absensi' => 'bg-emerald-100 text-emerald-600',
                'izin' => 'bg-amber-100 text-amber-600',
                'schedule' => 'bg-blue-100 text-blue-600',
                default => 'bg-slate-100 text-slate-600',
                };
                @endphp
                <div class="relative rounded-2xl border {{ $item->dibaca ? 'border-slate-200 bg-white' : 'border-blue-100 bg-blue-50/60' }} p-4 shadow-sm">
                    @if(!$item->dibaca)
                    <span class="absolute right-4 top-4 h-2 w-2 rounded-full bg-blue-600"></span>
                    @endif
                    <div class="flex gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $ikonClass }} text-sm font-bold">
                            {{ $ikon }}
                        </div>
                        <div class="min-w-0 flex-1 pr-4">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-sm font-bold text-slate-800">
                                    {{ $item->judul }}
                                </h3>
                                <span class="shrink-0 text-[10px] text-slate-400">
                                    {{ $item->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                {{ $item->pesan }}
                            </p>
                            @if(!$item->dibaca)
                            <form method="POST"
                                action="{{ route('notifications.read', $item->id) }}"
                                class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-[11px] font-bold text-blue-600">
                                    Tandai sudah dibaca
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
        @endif
        @if($notifikasi->hasPages())
        <div class="mt-6">
            {{ $notifikasi->links() }}
        </div>
        @endif
    </main>
</div>
@endsection