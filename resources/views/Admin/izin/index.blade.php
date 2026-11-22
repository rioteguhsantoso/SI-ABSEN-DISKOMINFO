@extends('Admin.layouts.app')
@section('title', 'Pengajuan Izin')
@section('content')
<div class="space-y-5">
    <div>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Monitoring</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Pengajuan Izin</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola dan pantau pengajuan izin pegawai.</p>
    </div>
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl px-4 py-3 text-xs font-semibold">
        {{ session('success') }}
    </div>
    @endif
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-xs text-slate-400 font-semibold">Menunggu</p>
            <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ $menunggu }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Pengajuan</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-xs text-slate-400 font-semibold">Disetujui</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $disetujui }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Pengajuan</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4">
            <p class="text-xs text-slate-400 font-semibold">Ditolak</p>
            <p class="text-2xl font-extrabold text-red-500 mt-1">{{ $ditolak }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Pengajuan</p>
        </div>
    </div>
    <form method="GET" action="{{ route('admin.izin.index') }}" class="bg-white border border-slate-200 rounded-2xl p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Status</label>
                <select name="status" class="w-full h-10 rounded-xl border-slate-200 text-sm px-3 focus:border-slate-400 focus:ring-0">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Jenis Izin</label>
                <select name="jenis_izin" class="w-full h-10 rounded-xl border-slate-200 text-sm px-3 focus:border-slate-400 focus:ring-0">
                    <option value="">Semua Jenis</option>
                    <option value="sakit" {{ $jenisIzin === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="keperluan_keluarga" {{ $jenisIzin === 'keperluan_keluarga' ? 'selected' : '' }}>Keperluan Keluarga</option>
                    <option value="keperluan_dinas" {{ $jenisIzin === 'keperluan_dinas' ? 'selected' : '' }}>Keperluan Dinas</option>
                    <option value="keperluan_pribadi" {{ $jenisIzin === 'keperluan_pribadi' ? 'selected' : '' }}>Keperluan Pribadi</option>
                    <option value="izin_terlambat" {{ $jenisIzin === 'izin_terlambat' ? 'selected' : '' }}>Izin Terlambat</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Pegawai</label>
                <select name="pegawai_id" class="w-full h-10 rounded-xl border-slate-200 text-sm px-3 focus:border-slate-400 focus:ring-0">
                    <option value="">Semua Pegawai</option>
                    @foreach($pegawai as $item)
                    <option value="{{ $item->id }}" {{ (string) $pegawaiId === (string) $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-3">
            <a href="{{ route('admin.izin.index') }}" class="h-10 px-4 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold flex items-center hover:bg-slate-50 transition">
                Reset
            </a>
            <button type="submit" class="h-10 px-4 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                Terapkan
            </button>
        </div>
    </form>
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="text-sm font-extrabold text-slate-900">Daftar Pengajuan</h2>
            <p class="text-xs text-slate-400 mt-1">{{ $izin->count() }} pengajuan ditemukan</p>
        </div>
        @if($izin->count())
        <div class="divide-y divide-slate-100">
            @foreach($izin as $item)
            <div class="px-5 py-5 hover:bg-slate-50 transition">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-extrabold text-slate-800">
                                    {{ $item->pegawai->nama ?? '-' }}
                                </p>
                                <p class="text-[11px] text-slate-400 mt-1">
                                    {{ $item->pegawai->nip ?? '-' }}
                                </p>
                            </div>
                            @if($item->status === 'menunggu')
                            <span class="shrink-0 text-[10px] font-bold text-amber-700 bg-amber-50 px-2.5 py-1.5 rounded-lg">
                                Menunggu
                            </span>
                            @elseif($item->status === 'disetujui')
                            <span class="shrink-0 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-lg">
                                Disetujui
                            </span>
                            @elseif($item->status === 'ditolak')
                            <span class="shrink-0 text-[10px] font-bold text-red-700 bg-red-50 px-2.5 py-1.5 rounded-lg">
                                Ditolak
                            </span>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Jenis Izin</p>
                                <p class="text-xs font-bold text-slate-700 mt-1">
                                    @switch($item->jenis_izin)
                                    @case('sakit')
                                    Sakit
                                    @break
                                    @case('keperluan_keluarga')
                                    Keperluan Keluarga
                                    @break
                                    @case('keperluan_pribadi')
                                    Keperluan Pribadi
                                    @break
                                    @case('izin_terlambat')
                                    Izin Terlambat
                                    @break
                                    @case('izin_keluar')
                                    Izin Keluar
                                    @break
                                    @default
                                    {{ ucfirst(str_replace('_', ' ', $item->jenis_izin)) }}
                                    @endswitch
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Tanggal</p>
                                <p class="text-xs font-bold text-slate-700 mt-1">
                                    {{ $item->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}
                                    @if($item->tanggal_selesai)
                                    — {{ $item->tanggal_selesai->translatedFormat('d F Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Keterangan</p>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                {{ $item->keterangan }}
                            </p>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-3">
                            Diajukan {{ $item->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </p>
                    </div>
                    @if($item->status === 'menunggu')
                    <div class="flex lg:flex-col gap-2 shrink-0">
                        <form method="POST" action="{{ route('admin.izin.status', $item->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" class="w-full lg:w-24 h-9 px-3 rounded-xl bg-emerald-600 text-white text-[11px] font-bold hover:bg-emerald-700 transition">
                                Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.izin.status', $item->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <button type="submit" class="w-full lg:w-24 h-9 px-3 rounded-xl bg-red-50 text-red-600 border border-red-100 text-[11px] font-bold hover:bg-red-100 transition">
                                Tolak
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-700 mt-3">Belum ada pengajuan izin</p>
            <p class="text-xs text-slate-400 mt-1">Pengajuan izin pegawai akan muncul di sini.</p>
        </div>
        @endif
    </div>
</div>
@endsection