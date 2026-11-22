@extends('Admin.layouts.app')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')
@section('content')
<div class="space-y-6">
    {{-- HEADER DAN FILTER PERIODE --}}
    <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="h-8 w-1 rounded-full bg-blue-700"></span>
                <h1 class="text-xl font-extrabold tracking-tight text-slate-800">Dashboard Admin</h1>
            </div>
            <p class="mt-2 text-sm text-slate-500">Pantau ringkasan kehadiran dan pengajuan izin pegawai SI-ABSEN DISKOMINFO.</p>
        </div>
        <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-60">
            <label for="bulan" class="mb-2 block text-xs font-bold text-slate-500">Periode Dashboard</label>
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-100">
                <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 4h14a1 1 0 011 1v15H4V5a1 1 0 011-1z" />
                </svg>
                <select id="bulan" name="bulan" onchange="this.form.submit()" class="w-full border-0 bg-transparent p-0 text-sm font-bold text-slate-700 outline-none focus:ring-0">
                    @foreach($daftarBulan as $nilaiBulan => $labelBulan)
                    <option value="{{ $nilaiBulan }}" {{ $bulanDipilih === $nilaiBulan ? 'selected' : '' }}>{{ $labelBulan }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </section>
    {{-- RINGKASAN KEHADIRAN --}}
    <section>
        <div class="mb-3">
            <h2 class="text-base font-extrabold text-slate-800">Ringkasan Kehadiran</h2>
            <p class="mt-1 text-xs text-slate-500">Statistik untuk periode {{ $daftarBulan[$bulanDipilih] ?? $bulanDipilih }}.</p>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-200">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-slate-500">Total Pegawai</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-extrabold text-slate-800">{{ $totalPegawai }}</p>
                <p class="mt-1 text-xs text-slate-400">Pegawai terdaftar</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-emerald-200">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-slate-500">Hadir</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-extrabold text-emerald-600">{{ $hadir }}</p>
                <p class="mt-1 text-xs text-slate-400">Termasuk terlambat</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-amber-200">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-slate-500">Terlambat</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-extrabold text-amber-600">{{ $terlambat }}</p>
                <p class="mt-1 text-xs text-slate-400">Bagian dari total hadir</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-purple-200">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-slate-500">Izin Disetujui</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m2-7H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-extrabold text-purple-600">{{ $izin }}</p>
                <p class="mt-1 text-xs text-slate-400">Pengajuan yang beririsan dengan bulan ini</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-red-200">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-slate-500">Tidak Hadir</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9L1.8 18.5A1.5 1.5 0 003.1 21h17.8a1.5 1.5 0 001.3-2.5L13.7 3.9a2 2 0 00-3.4 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-extrabold text-red-600">{{ $tidakHadir }}</p>
                <p class="mt-1 text-xs text-slate-400">Hari kerja tanpa catatan hadir atau izin</p>
            </div>
        </div>
    </section>
    {{-- CATATAN ABSENSI DAN IZIN MENUNGGU --}}
    <section class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 4h14a1 1 0 011 1v15H4V5a1 1 0 011-1z" />
                </svg>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-slate-700">Catatan Absensi</p>
                <p class="mt-1 text-xs text-slate-500">Catatan absensi aktual sampai tanggal ini.</p>
            </div>
            <p class="text-2xl font-extrabold text-slate-800">{{ $totalAbsensi }}</p>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-slate-700">Izin Menunggu</p>
                <p class="mt-1 text-xs text-slate-500">Pengajuan yang perlu diproses admin.</p>
            </div>
            <p class="text-2xl font-extrabold text-orange-600">{{ $izinMenunggu }}</p>
        </div>
    </section>
    {{-- AKTIVITAS TERBARU DAN PENGAJUAN IZIN --}}
    <section class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white xl:col-span-2">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-slate-800">Aktivitas Terbaru</h2>
                    <p class="mt-1 text-xs text-slate-500">Catatan absensi aktual dan izin yang tanggalnya sudah tiba.</p>
                </div>
                <a href="{{ url('/admin/absensi') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-xs font-bold text-blue-700 transition hover:bg-blue-100">
                    Lihat Data Absensi <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="max-h-[360px] overflow-auto">
                <table class="w-full min-w-[650px] text-sm">
                    <thead class="sticky top-0 z-10 border-b border-slate-100 bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Pegawai</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Masuk</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Pulang</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($absensiTerbaru as $absensi)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-3.5">
                                <p class="font-bold text-slate-700">{{ $absensi->pegawai->nama ?? '-' }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $absensi->pegawai->nip ?? '-' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-xs text-slate-600">{{ $absensi->tanggal ? \Carbon\Carbon::parse($absensi->tanggal, 'Asia/Makassar')->format('d M Y') : '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-xs text-slate-600">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-xs text-slate-600">{{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3.5">
                                @php
                                $status = strtolower(trim($absensi->status ?? ''));
                                $statusTampilan = [
                                'hadir' => ['Hadir', 'bg-emerald-50 text-emerald-700'],
                                'terlambat' => ['Terlambat', 'bg-amber-50 text-amber-700'],
                                'izin' => ['Izin', 'bg-purple-50 text-purple-700'],
                                'sakit' => ['Sakit', 'bg-blue-50 text-blue-700'],
                                'izin dinas' => ['Izin Dinas', 'bg-indigo-50 text-indigo-700'],
                                'alpa' => ['Alpa', 'bg-red-50 text-red-700'],
                                'tidak hadir' => ['Tidak Hadir', 'bg-red-50 text-red-700'],
                                ];
                                $tampilan = $statusTampilan[$status] ?? [$status !== '' ? ucfirst($status) : 'Belum diketahui', 'bg-slate-100 text-slate-600'];
                                @endphp
                                <span class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-[10px] font-bold {{ $tampilan[1] }}">{{ $tampilan[0] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M4 9h16M5 4h14a1 1 0 011 1v15H4V5a1 1 0 011-1z" />
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-600">Belum ada aktivitas</p>
                                <p class="mt-1 text-xs text-slate-400">Belum ada catatan untuk periode ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- PENGAJUAN IZIN --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-800">Pengajuan Izin</h2>
                    <p class="mt-1 text-xs text-slate-500">Pengajuan yang menunggu keputusan.</p>
                </div>
                <span class="flex h-8 min-w-8 items-center justify-center rounded-full bg-orange-50 px-2 text-xs font-extrabold text-orange-700">{{ $izinMenunggu }}</span>
            </div>
            <div class="max-h-[360px] divide-y divide-slate-100 overflow-y-auto">
                @forelse($pengajuanTerbaru as $izinItem)
                <div class="p-5 transition hover:bg-slate-50">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-700">{{ $izinItem->pegawai->nama ?? '-' }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ str_replace('_', ' ', ucfirst($izinItem->jenis_izin ?? 'izin')) }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-orange-50 px-2 py-1 text-[10px] font-bold text-orange-700">Menunggu</span>
                    </div>
                    <p class="mt-3 line-clamp-3 text-xs leading-relaxed text-slate-500">{{ $izinItem->keterangan ?: 'Tidak ada keterangan.' }}</p>
                    <p class="mt-3 text-xs font-medium text-slate-500">
                        {{ $izinItem->tanggal_mulai ? \Carbon\Carbon::parse($izinItem->tanggal_mulai, 'Asia/Makassar')->format('d M Y') : '-' }}
                        @if($izinItem->tanggal_selesai)
                        – {{ \Carbon\Carbon::parse($izinItem->tanggal_selesai, 'Asia/Makassar')->format('d M Y') }}
                        @endif
                    </p>
                    <a href="{{ url('/admin/izin') }}" class="mt-4 inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:underline">Tinjau pengajuan <span aria-hidden="true">→</span></a>
                </div>
                @empty
                <div class="px-5 py-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-slate-600">Tidak ada pengajuan menunggu</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-400">Semua pengajuan izin sudah diproses atau belum ada pengajuan baru.</p>
                </div>
                @endforelse
            </div>
            @if($izinMenunggu > 0)
            <div class="border-t border-slate-100 p-4">
                <a href="{{ url('/admin/izin') }}" class="flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">Lihat Semua Pengajuan</a>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection