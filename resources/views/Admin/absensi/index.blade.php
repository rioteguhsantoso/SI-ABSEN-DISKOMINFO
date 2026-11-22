@extends('Admin.layouts.app')
@section('title', 'Data Absensi')
@section('page_title', 'Data Absensi')
@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="h-8 w-1.5 rounded-full bg-blue-700"></span>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Data Absensi</h1>
            </div>
            <p class="mt-2 text-sm text-slate-500">Monitoring dan pengelolaan kehadiran pegawai SI-ABSEN DISKOMINFO.</p>
        </div>
        <div class="flex items-center gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 4h14a1 1 0 011 1v15H4V5a1 1 0 011-1z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-500">Periode Data</p>
                <p class="text-sm font-extrabold text-blue-900">{{ \Carbon\Carbon::createFromFormat('!Y-m', $bulan)->locale('id')->translatedFormat('F Y') }}</p>
            </div>
        </div>
    </div>
    {{-- STATISTIK --}}
    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Hadir</p>
                    <p class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $hadir }}</p>
                    <p class="mt-1 text-[10px] text-slate-400">Termasuk terlambat</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500"></div>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Terlambat</p>
                    <p class="mt-2 text-2xl font-extrabold text-amber-500">{{ $terlambat }}</p>
                    <p class="mt-1 text-[10px] text-slate-400">Tetap dihitung hadir</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-amber-400"></div>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Izin</p>
                    <p class="mt-2 text-2xl font-extrabold text-indigo-600">{{ $izin }}</p>
                    <p class="mt-1 text-[10px] text-slate-400">Dihitung per hari izin</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                    </svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-indigo-500"></div>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Tidak Hadir</p>
                    <p class="mt-2 text-2xl font-extrabold text-red-500">{{ $tidakHadir }}</p>
                    <p class="mt-1 text-[10px] text-slate-400">Hari kerja yang telah selesai</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-red-500"></div>
        </div>
    </div>
    {{-- FILTER --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5h16M7 12h10m-7 7h4" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-slate-800">Filter Data Absensi</h2>
                <p class="mt-0.5 text-xs text-slate-400">Pilih periode, tanggal, atau pegawai untuk menyaring data.</p>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.absensi.index') }}" class="p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label for="bulan" class="mb-2 block text-xs font-bold text-slate-600">Periode Bulan</label>
                    <input type="month" id="bulan" name="bulan" value="{{ $bulan }}" class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-700 transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="tanggal" class="mb-2 block text-xs font-bold text-slate-600">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="pegawai_id" class="mb-2 block text-xs font-bold text-slate-600">Pegawai</label>
                    <select id="pegawai_id" name="pegawai_id" class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Pegawai</option>
                        @foreach($pegawai as $itemPegawai)
                        <option value="{{ $itemPegawai->id }}" {{ (string) $pegawaiId === (string) $itemPegawai->id ? 'selected' : '' }}>
                            {{ $itemPegawai->nama }} — {{ $itemPegawai->nip }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-xs font-bold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 9h12m-9 5h6m-4 5h2" />
                        </svg>
                        Terapkan
                    </button>
                    <a href="{{ route('admin.absensi.index') }}" class="flex h-11 items-center justify-center rounded-xl border border-slate-200 px-4 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Reset</a>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Filter aktif:</span>
                <span class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-[10px] font-bold text-blue-700">
                    {{ \Carbon\Carbon::createFromFormat('!Y-m', $bulan)->locale('id')->translatedFormat('F Y') }}
                </span>
                @if($tanggal)
                <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[10px] font-bold text-slate-600">
                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}
                </span>
                @endif
                @if($pegawaiId)
                @php $pegawaiTerpilih = $pegawai->firstWhere('id', (int) $pegawaiId); @endphp
                @if($pegawaiTerpilih)
                <span class="rounded-lg bg-indigo-50 px-2.5 py-1.5 text-[10px] font-bold text-indigo-700">{{ $pegawaiTerpilih->nama }}</span>
                @endif
                @endif
            </div>
        </form>
    </div>
    {{-- RIWAYAT ABSENSI --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-5 w-1 rounded-full bg-blue-700"></span>
                    <h2 class="text-sm font-extrabold text-slate-800">Riwayat Absensi</h2>
                </div>
                <p class="mt-1 text-xs text-slate-400">Daftar presensi, izin disetujui, dan ketidakhadiran pegawai.</p>
            </div>
            <div class="flex items-center gap-2 self-start rounded-xl bg-slate-50 px-3 py-2 sm:self-auto">
                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H5v4m0-4l5 5m5-5h4v4m0-4l-5 5M5 15v4h4m-4 0l5-5m5 5h4v-4m0 4l-5-5" />
                </svg>
                <span class="text-xs font-extrabold text-slate-700">{{ $absensi->count() }} data</span>
            </div>
        </div>
        @if($absensi->isNotEmpty())
        <div class="max-h-[500px] divide-y divide-slate-100 overflow-y-auto overscroll-contain">
            @foreach($absensi as $item)
            @php
            $statusTampilan = strtolower($item->status ?? '');
            $labelStatus = match ($statusTampilan) {
            'hadir' => 'Hadir',[ ]+$
            'terlambat' => 'Terlambat',
            'izin' => 'Izin',
            'izin dinas' => 'Izin Dinas',
            'sakit' => 'Sakit',
            'alpa', 'tidak_hadir' => 'Tidak Hadir',
            default => ucfirst(str_replace('_', ' ', $statusTampilan ?: 'Tidak diketahui')),
            };
            $warnaStatus = match ($statusTampilan) {
            'hadir' => 'bg-emerald-50 text-emerald-700',
            'terlambat' => 'bg-amber-50 text-amber-700',
            'izin' => 'bg-indigo-50 text-indigo-700',
            'izin dinas' => 'bg-violet-50 text-violet-700',
            'sakit' => 'bg-sky-50 text-sky-700',
            'alpa', 'tidak_hadir' => 'bg-red-50 text-red-700',
            default => 'bg-slate-100 text-slate-600',
            };
            @endphp
            <div class="px-5 py-4 transition hover:bg-slate-50/80 sm:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-extrabold text-blue-700">
                            {{ strtoupper(substr($item->pegawai->nama ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-extrabold text-slate-800">{{ $item->pegawai->nama ?? 'Pegawai tidak ditemukan' }}</p>
                            <p class="mt-1 text-[11px] text-slate-400">NIP <span class="font-semibold text-slate-600">{{ $item->pegawai->nip ?? '-' }}</span></p>
                            <p class="mt-1 text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-end">
                        <span class="rounded-lg px-3 py-1.5 text-[10px] font-bold {{ $warnaStatus }}">{{ $labelStatus }}</span>
                        <div class="min-w-[65px] text-right">
                            <p class="text-[10px] text-slate-400">Jam Masuk</p>
                            <p class="mt-0.5 text-sm font-extrabold text-slate-700">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</p>
                        </div>
                        <div class="min-w-[65px] border-l border-slate-200 pl-3 text-right">
                            <p class="text-[10px] text-slate-400">Jam Pulang</p>
                            <p class="mt-0.5 text-sm font-extrabold text-slate-700">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-14 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4v4m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="mt-4 text-sm font-extrabold text-slate-700">Belum ada data absensi</p>
            <p class="mx-auto mt-1 max-w-sm text-xs leading-5 text-slate-400">Tidak ditemukan data untuk periode atau filter yang dipilih. Coba ubah filter atau tekan Reset.</p>
            <a href="{{ route('admin.absensi.index') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800">Lihat Semua Data</a>
        </div>
        @endif
        <div class="border-t border-slate-100 bg-slate-50/70 px-5 py-3">
            <p class="text-[10px] leading-5 text-slate-400">Data mencakup catatan absensi, izin yang disetujui, dan ketidakhadiran otomatis pada hari kerja yang telah selesai. Ketidakhadiran otomatis tidak disimpan ke database.</p>
        </div>
    </div>
</div>
@endsection