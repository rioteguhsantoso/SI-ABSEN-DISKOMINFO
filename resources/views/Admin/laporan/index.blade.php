@extends('Admin.layouts.app')

@section('title', 'Laporan & Rekap')
@section('page_title', 'Laporan & Rekap')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="h-8 w-1.5 rounded-full bg-blue-700"></span>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">
                    Laporan & Rekap
                </h1>
            </div>
            <p class="mt-2 text-sm text-slate-500">
                Monitoring dan rekapitulasi data pegawai SI-ABSEN DISKOMINFO.
            </p>
        </div>

        <div class="flex items-center gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M8 2v4m8-4v4M4 9h16M5 4h14a1 1 0 011 1v15H4V5a1 1 0 011-1z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-blue-500">
                    Periode Laporan
                </p>
                <p class="text-sm font-extrabold text-blue-900">
                    {{ \Carbon\Carbon::createFromFormat('!Y-m', $bulan)->locale('id')->translatedFormat('F Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- KARTU JUMLAH LAPORAN --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">

        @php
        $kartu = [
        [
        'jenis' => 'hadir',
        'judul' => 'Daftar Hadir',
        'jumlah' => $jumlah['hadir'],
        'warna' => 'blue',
        'ikon' => 'M5 13l4 4L19 7'
        ],
        [
        'jenis' => 'dinas',
        'judul' => 'Perjalanan Dinas',
        'jumlah' => $jumlah['dinas'],
        'warna' => 'indigo',
        'ikon' => 'M3 7h18M5 7V4h14v3M5 7v13h14V7M9 11h6'
        ],
        [
        'jenis' => 'terlambat',
        'judul' => 'Izin Terlambat',
        'jumlah' => $jumlah['terlambat'],
        'warna' => 'amber',
        'ikon' => 'M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z'
        ],
        [
        'jenis' => 'cuti',
        'judul' => 'Pengajuan Cuti',
        'jumlah' => $jumlah['cuti'],
        'warna' => 'rose',
        'ikon' => 'M8 2v4m8-4v4M4 9h16M5 5h14v15H5z'
        ],
        [
        'jenis' => 'pengguna',
        'judul' => 'Rekap Pengguna',
        'jumlah' => $jumlah['pengguna'],
        'warna' => 'emerald',
        'ikon' => 'M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-10a4 4 0 110 8 4 4 0 010-8z'
        ],
        ];
        @endphp

        @foreach($kartu as $item)
        <a href="{{ route('admin.laporan.index', ['bulan' => $bulan, 'jenis' => $item['jenis']]) }}"
            class="relative overflow-hidden rounded-2xl border p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
                {{ $jenis === $item['jenis'] ? 'border-blue-300 bg-blue-50/50' : 'border-slate-200 bg-white' }}">

            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold text-slate-500">
                        {{ $item['judul'] }}
                    </p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-800">
                        {{ $item['jumlah'] }}
                    </p>
                    <p class="mt-1 text-[10px] text-slate-400">
                        Lihat laporan
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl
                        bg-{{ $item['warna'] }}-50 text-{{ $item['warna'] }}-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8" d="{{ $item['ikon'] }}" />
                    </svg>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 h-1 w-full
                    {{ $jenis === $item['jenis'] ? 'bg-blue-600' : 'bg-slate-100' }}">
            </div>
        </a>
        @endforeach
    </div>

    {{-- FILTER PERIODE --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.8" d="M4 5h16M7 12h10m-7 7h4" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-extrabold text-slate-800">Filter Laporan</h2>
                <p class="mt-0.5 text-xs text-slate-400">
                    Pilih periode untuk menampilkan data laporan.
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="p-5">
            <input type="hidden" name="jenis" value="{{ $jenis }}">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-xs">
                    <label for="bulan" class="mb-2 block text-xs font-bold text-slate-600">
                        Periode Bulan
                    </label>
                    <input type="month" id="bulan" name="bulan" value="{{ $bulan }}"
                        class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex h-11 items-center gap-2 rounded-xl bg-slate-900 px-5 text-xs font-bold text-white transition hover:bg-slate-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 5h16M7 12h10m-7 7h4" />
                        </svg>
                        Terapkan
                    </button>

                    <a href="{{ route('admin.laporan.index', ['jenis' => $jenis]) }}"
                        class="flex h-11 items-center rounded-xl border border-slate-200 px-5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL LAPORAN --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800">
                    @switch($jenis)
                    @case('hadir')
                    Daftar Hadir Pegawai
                    @break
                    @case('dinas')
                    Daftar Perjalanan Dinas
                    @break
                    @case('terlambat')
                    Daftar Izin Terlambat
                    @break
                    @case('cuti')
                    Daftar Pengajuan Cuti
                    @break
                    @case('pengguna')
                    Rekap Pengguna
                    @break
                    @endswitch
                </h2>
                <p class="mt-1 text-xs text-slate-400">
                    {{ $data->count() }} data ditemukan
                </p>
            </div>


            <div class="flex flex-wrap items-center gap-2">

                {{-- DOWNLOAD PDF --}}
                <a href="{{ route('admin.laporan.export.pdf', ['bulan' => $bulan, 'jenis' => $jenis]) }}"
                    class="flex h-10 items-center justify-center gap-2 rounded-xl bg-red-600 px-4 text-xs font-bold text-white transition hover:bg-red-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3" />
                    </svg>
                    Unduh PDF
                </a>

                {{-- DOWNLOAD EXCEL --}}
                <a href="{{ route('admin.laporan.export.excel', ['bulan' => $bulan, 'jenis' => $jenis]) }}"
                    class="flex h-10 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 text-xs font-bold text-white transition hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3" />
                    </svg>
                    Unduh Excel
                </a>

            </div>

        </div>

        {{-- KONDISI DATA KOSONG UNTUK CUTI --}}
        @if($jenis === 'cuti' && $data->isEmpty())

        <div class="p-10 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 5h14v15H5z" />
                </svg>
            </div>

            <h3 class="mt-4 text-sm font-extrabold text-slate-700">
                Belum ada pengajuan cuti
            </h3>
            <p class="mt-1 text-xs text-slate-400">
                Belum ditemukan pengajuan cuti pada periode yang dipilih.
            </p>
        </div>

        @elseif($data->count() > 0)

        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">

                    @if($jenis === 'hadir')
                    <tr>
                        <th class="px-5 py-4 font-bold">Tanggal</th>
                        <th class="px-5 py-4 font-bold">Pegawai</th>
                        <th class="px-5 py-4 font-bold">Jam Masuk</th>
                        <th class="px-5 py-4 font-bold">Jam Pulang</th>
                        <th class="px-5 py-4 font-bold">Status</th>
                        <th class="px-5 py-4 font-bold">Verifikasi</th>
                    </tr>

                    @elseif($jenis === 'dinas' || $jenis === 'cuti')
                    <tr>
                        <th class="px-5 py-4 font-bold">Pegawai</th>
                        <th class="px-5 py-4 font-bold">Tanggal Mulai</th>
                        <th class="px-5 py-4 font-bold">Tanggal Selesai</th>
                        <th class="px-5 py-4 font-bold">Durasi</th>
                        <th class="px-5 py-4 font-bold">Keterangan</th>
                        <th class="px-5 py-4 font-bold">Status</th>
                    </tr>

                    @elseif($jenis === 'terlambat')
                    <tr>
                        <th class="px-5 py-4 font-bold">Pegawai</th>
                        <th class="px-5 py-4 font-bold">Tanggal</th>
                        <th class="px-5 py-4 font-bold">Keterangan</th>
                        <th class="px-5 py-4 font-bold">Status</th>
                    </tr>

                    @elseif($jenis === 'pengguna')
                    <tr>
                        <th class="px-5 py-4 font-bold">NIP</th>
                        <th class="px-5 py-4 font-bold">Nama Pegawai</th>
                        <th class="px-5 py-4 font-bold">Nomor HP</th>
                        <th class="px-5 py-4 font-bold">Status Pegawai</th>
                        <th class="px-5 py-4 font-bold">Akun</th>
                        <th class="px-5 py-4 font-bold">Face Recognition</th>
                    </tr>
                    @endif

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($data as $item)

                    {{-- DAFTAR HADIR --}}
                    @if($jenis === 'hadir')
                    <tr class="transition hover:bg-slate-50">
                        <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-700">
                            {{ $item->tanggal?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-800">
                                {{ $item->pegawai->nama ?? '-' }}
                            </p>
                            <p class="mt-1 text-[10px] text-slate-400">
                                {{ $item->pegawai->nip ?? '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            {{ $item->jam_masuk ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            {{ $item->jam_pulang ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            @php
                            $warnaStatus = match($item->status) {
                            'hadir' => 'bg-emerald-50 text-emerald-700',
                            'terlambat' => 'bg-amber-50 text-amber-700',
                            'izin' => 'bg-blue-50 text-blue-700',
                            'sakit' => 'bg-indigo-50 text-indigo-700',
                            'alpa' => 'bg-red-50 text-red-700',
                            default => 'bg-slate-100 text-slate-600',
                            };
                            @endphp

                            <span class="rounded-lg px-2.5 py-1.5 text-[10px] font-bold {{ $warnaStatus }}">
                                {{ ucfirst($item->status ?? '-') }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            @if($item->verifikasis->count() > 0)
                            <span class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[10px] font-bold text-emerald-700">
                                Terdapat data verifikasi
                            </span>
                            @else
                            <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[10px] font-bold text-slate-500">
                                Tidak tersedia
                            </span>
                            @endif
                        </td>
                    </tr>

                    {{-- PERJALANAN DINAS DAN CUTI --}}
                    @elseif($jenis === 'dinas' || $jenis === 'cuti')
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-800">
                                {{ $item->pegawai->nama ?? '-' }}
                            </p>
                            <p class="mt-1 text-[10px] text-slate-400">
                                {{ $item->pegawai->nip ?? '-' }}
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                            {{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                            {{ $item->tanggal_selesai?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td class="px-5 py-4 font-bold text-slate-700">
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                            {{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 }} hari
                            @else
                            -
                            @endif
                        </td>

                        <td class="max-w-xs px-5 py-4 text-slate-600">
                            {{ $item->keterangan ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            @include('Admin.laporan.partials.status', ['status' => $item->status])
                        </td>
                    </tr>

                    {{-- IZIN TERLAMBAT --}}
                    @elseif($jenis === 'terlambat')
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-800">
                                {{ $item->pegawai->nama ?? '-' }}
                            </p>
                            <p class="mt-1 text-[10px] text-slate-400">
                                {{ $item->pegawai->nip ?? '-' }}
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                            {{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td class="max-w-xs px-5 py-4 text-slate-600">
                            {{ $item->keterangan ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            @include('Admin.laporan.partials.status', ['status' => $item->status])
                        </td>
                    </tr>

                    {{-- REKAP PENGGUNA --}}
                    @elseif($jenis === 'pengguna')
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold text-slate-600">
                            {{ $item->nip ?? '-' }}
                        </td>

                        <td class="px-5 py-4 font-bold text-slate-800">
                            {{ $item->nama ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            {{ $item->nomor_hp ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            {{ ucfirst($item->status ?? '-') }}
                        </td>

                        <td class="px-5 py-4">
                            @if($item->user)
                            <span class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[10px] font-bold text-emerald-700">
                                Terdaftar
                            </span>
                            @else
                            <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[10px] font-bold text-slate-500">
                                Belum ada akun
                            </span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            @if($item->face_vector)
                            <span class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[10px] font-bold text-emerald-700">
                                Terdaftar
                            </span>
                            @else
                            <span class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-[10px] font-bold text-amber-700">
                                Belum terdaftar
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endif

                    @endforeach

                </tbody>
            </table>
        </div>

        @else

        <div class="p-10 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5M5 3h14a1 1 0 011 1v16H4V4a1 1 0 011-1z" />
                </svg>
            </div>

            <h3 class="mt-4 text-sm font-extrabold text-slate-700">
                Belum ada data laporan
            </h3>
            <p class="mt-1 text-xs text-slate-400">
                Belum ditemukan data untuk periode yang dipilih.
            </p>
        </div>

        @endif

    </div>

    {{-- INFORMASI --}}
    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z" />
            </svg>
            <div>
                <p class="text-xs font-extrabold text-blue-900">
                    Informasi Laporan
                </p>
                <p class="mt-1 text-[11px] leading-5 text-blue-800">
                    Data perjalanan dinas menggunakan kategori keperluan dinas
                    pada pengajuan izin. Durasi dihitung berdasarkan tanggal mulai
                    dan tanggal selesai. Data pengajuan cuti ditampilkan berdasarkan
                    periode tanggal mulai dan tanggal selesai pengajuan.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection