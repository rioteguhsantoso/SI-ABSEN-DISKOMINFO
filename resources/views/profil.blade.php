@extends('layouts.app')
@section('title', 'Profil - SI-ABSEN DISKOMINFO')
@section('page_title', 'Profil')
@section('content')
@php
$unitKerja = $pegawai->unit_kerja ?? 'Dinas Komunikasi dan Informatika';
$bulanPilihan = [
1 => 'Januari',
2 => 'Februari',
3 => 'Maret',
4 => 'April',
5 => 'Mei',
6 => 'Juni',
7 => 'Juli',
8 => 'Agustus',
9 => 'September',
10 => 'Oktober',
11 => 'November',
12 => 'Desember',
];
$bulanSekarang = (int) $tanggalBulan->month;
$tahunSekarang = (int) $tanggalBulan->year;
$tahunPilihan = range(now('Asia/Makassar')->year - 5, now('Asia/Makassar')->year + 1);
$bulanTerpilih = sprintf('%04d-%02d', $tahunSekarang, $bulanSekarang);
@endphp
<div class="min-h-screen bg-[#f3f7fc] pb-28">
    <div class="max-w-md mx-auto px-3.5 pt-3 pb-7 space-y-3">
        {{-- PROFILE HEADER --}}
        <div class="relative overflow-hidden rounded-[20px] min-h-[158px] shadow-[0_6px_18px_rgba(25,70,120,0.10)] bg-[#dcecff]">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image:url('{{ asset('images/profil-header.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-white/20"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-white/20 to-transparent"></div>
            <div class="absolute inset-0 bg-blue-900/[0.025]"></div>
            {{-- TOP BAR --}}
            <div class="relative z-10 flex items-center justify-end px-4 pt-3">
                <a href="{{ route('profil.edit') }}" aria-label="Edit Profil" class="inline-flex items-center gap-1.5 rounded-full border border-white/50 bg-[#123873]/95 px-2.5 py-1.5 text-white shadow-[0_3px_10px_rgba(18,56,115,0.22)] backdrop-blur-sm transition active:scale-95">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a2.25 2.25 0 113.182 3.182l-8.69 8.69a4.5 4.5 0 01-1.897 1.13l-3.132.895.895-3.132a4.5 4.5 0 011.13-1.897l6.825-6.825z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 7.125L16.875 4.5" />
                    </svg>
                    <span class="text-[8px] font-bold tracking-[0.01em]">Edit Profil</span>
                </a>
            </div>
            {{-- PROFILE INFO --}}
            <div class="relative z-10 flex items-center gap-3 px-4 pt-3 pb-3">
                <div class="relative shrink-0">
                    <div class="h-[58px] w-[58px] rounded-full bg-white p-1 shadow-[0_3px_12px_rgba(20,55,100,0.18)]">
                        <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-slate-100">
                            @if(!empty($pegawai->foto))
                            <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="Foto Profil" class="h-full w-full object-cover">
                            @else
                            <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="1.7" stroke-linecap="round" d="M15 19a4 4 0 00-6 0m6-10a3 3 0 11-6 0 3 3 0 016 0zm6 10a7 7 0 00-14 0" />
                            </svg>
                            @endif
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 h-[17px] w-[17px] rounded-full border-[2px] border-white bg-emerald-400 shadow-sm"></div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[8px] font-extrabold uppercase tracking-[0.13em] text-[#5275a3]">Profil Pegawai</p>
                    <h2 class="mt-0.5 truncate text-[16px] font-extrabold leading-tight text-[#102f5b]">{{ $pegawai->nama }}</h2>
                    <p class="mt-0.5 truncate text-[9px] font-semibold text-[#5275a3]">NIP. {{ $pegawai->nip }}</p>
                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                        <div class="inline-flex items-center gap-1 rounded-full border border-emerald-100 bg-emerald-50 px-2 py-[3px]">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-[7px] font-extrabold text-emerald-600">Aktif</span>
                        </div>
                        <div class="inline-flex items-center gap-1 rounded-full border border-blue-100 bg-blue-50 px-2 py-[3px]">
                            <svg class="h-2.5 w-2.5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1.5l6.5 2.6v4.85c0 4.2-2.7 7.98-6.5 9.55-3.8-1.57-6.5-5.35-6.5-9.55V4.1L10 1.5zm3.2 6.35a.9.9 0 00-1.27 0L9 10.78 8.07 9.85a.9.9 0 10-1.27 1.27l1.57 1.57a.9.9 0 001.27 0l3.56-3.57a.9.9 0 000-1.27 .9.9 0 000-1.27 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-[7px] font-extrabold text-blue-700">{{ $wajahTerdaftar ? 'Wajah Terdaftar' : 'Belum Terdaftar' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- UNIT KERJA --}}
        <div class="bg-white rounded-[20px] border border-[#dce7f4] shadow-[0_4px_16px_rgba(35,75,120,0.06)] overflow-hidden">
            <div class="px-3 py-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[#eaf3ff] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#1763b0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21h16M6 21V6a2 2 0 012-2h8a2 2 0 012 2v15M9 8h1m4 0h1M9 12h1m4 0h1M9 16h1m4 0h1" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[8px] font-bold uppercase tracking-[0.1em] text-[#7390b2]">Unit Kerja</p>
                        <p class="text-[10px] font-extrabold leading-snug text-[#183962] mt-0.5">{{ $unitKerja }}</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- DATA PEGAWAI --}}
        <div class="bg-white rounded-[20px] border border-[#dce7f4] shadow-[0_4px_16px_rgba(35,75,120,0.06)] overflow-hidden">
            <div class="px-3.5 pt-3 pb-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[#eaf3ff] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#1763b0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[8px] text-[#7390b2] font-bold uppercase tracking-[0.1em]">Informasi</p>
                        <h3 class="text-[13px] font-extrabold text-[#102f5b] mt-0.5">Data Pegawai</h3>
                    </div>
                </div>
            </div>
            <div class="px-3.5 pb-1">
                <div class="flex items-center gap-2.5 py-2.5 border-t border-[#e8eef6]">
                    <div class="w-8 h-8 rounded-full bg-[#f0f6fd] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#2c639d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[8px] text-[#7892b1] font-semibold uppercase tracking-wide">Nama Lengkap</p>
                        <p class="text-[11px] font-extrabold text-[#183962] mt-0.5 truncate">{{ $pegawai->nama }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 py-2.5 border-t border-[#e8eef6]">
                    <div class="w-8 h-8 rounded-full bg-[#f0f6fd] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#2c639d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 00.707.293l5.414 5.414a1 1 0 00.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[8px] text-[#7892b1] font-semibold uppercase tracking-wide">NIP</p>
                        <p class="text-[11px] font-extrabold text-[#183962] mt-0.5 truncate">{{ $pegawai->nip }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 py-2.5 border-t border-[#e8eef6]">
                    <div class="w-8 h-8 rounded-full bg-[#f0f6fd] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#2c639d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[8px] text-[#7892b1] font-semibold uppercase tracking-wide">Email Akun</p>
                        <p class="text-[11px] font-extrabold text-[#183962] mt-0.5 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 py-2.5 border-t border-[#e8eef6]">
                    <div class="w-8 h-8 rounded-full bg-[#f0f6fd] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#2c639d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.5 3.5h2.2l1.2 4.1-1.8 1.5a13.5 13.5 0 006.8 6.8l1.5-1.8 4.1 1.2v2.2a2 2 0 01-2 2A15.5 15.5 0 013.5 5.5a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[8px] text-[#7892b1] font-semibold uppercase tracking-wide">Nomor HP</p>
                        <p class="text-[11px] font-extrabold text-[#183962] mt-0.5 truncate">{{ $pegawai->nomor_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- RINGKASAN PRESENSI --}}
        <div id="ringkasan-presensi" class="relative overflow-visible rounded-[26px] bg-gradient-to-br from-[#1d5794] via-[#17477f] to-[#123b6e] p-4 shadow-[0_10px_25px_rgba(18,59,110,0.20)] scroll-mt-4">
            <div class="absolute -top-16 -right-16 w-44 h-44 rounded-full bg-white/[0.05]"></div>
            <div class="absolute -bottom-20 -left-10 w-40 h-40 rounded-full bg-blue-300/[0.05]"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-[9px] text-blue-100/80 font-semibold uppercase tracking-[0.14em]">Periode Presensi</p>
                        <h3 class="text-[17px] font-extrabold text-white mt-1">Ringkasan Presensi</h3>
                    </div>
                    {{-- MONTH DROPDOWN --}}
                    <div class="relative">
                        <button type="button" id="monthDropdownButton" onclick="toggleMonthDropdown()" aria-expanded="false" class="flex items-center gap-1.5 bg-white/10 border border-white/20 text-[9px] font-bold text-white px-3 py-2 rounded-xl backdrop-blur-sm active:scale-95 transition">
                            <span id="selectedMonthLabel">{{ $bulanPilihan[$bulanSekarang] }} {{ $tahunSekarang }}</span>
                            <svg id="monthChevron" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="monthDropdown" class="hidden absolute right-0 top-[calc(100%+8px)] z-[60] w-44 bg-white rounded-2xl border border-[#dce7f4] shadow-[0_12px_30px_rgba(15,50,90,0.20)] p-2">
                            <form action="{{ route('profil') }}" method="GET" id="monthFilterForm">
                                <label for="pilihTahun" class="block text-[9px] font-bold text-[#7390b2] px-2 pt-1 pb-1">Pilih Tahun</label>
                                <select id="pilihTahun" class="w-full rounded-lg border border-[#dce7f4] bg-white px-2 py-2 text-[10px] font-bold text-[#183962] mb-2">
                                    @foreach($tahunPilihan as $tahun)
                                    <option value="{{ $tahun }}" {{ (int) $tahun === $tahunSekarang ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="bulan" id="nilaiBulan" value="{{ $bulanTerpilih }}">
                                <div class="max-h-56 overflow-y-auto pr-0.5">
                                    @foreach($bulanPilihan as $nomor => $namaBulan)
                                    @php
                                    $nilaiBulanOpsi = sprintf('%04d-%02d', $tahunSekarang, $nomor);
                                    $opsiTerpilih = $nomor === $bulanSekarang;
                                    @endphp
                                    <button type="button" data-month="{{ $nomor }}" data-month-name="{{ $namaBulan }}" class="month-option w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-left text-[10px] font-bold transition {{ $opsiTerpilih ? 'bg-[#eaf3ff] text-[#1763b0]' : 'text-[#183962] hover:bg-[#f3f7fc]' }}">
                                        <span>{{ $namaBulan }}</span>
                                        <span class="month-check {{ $opsiTerpilih ? '' : 'hidden' }} w-1.5 h-1.5 rounded-full bg-[#1763b0]"></span>
                                    </button>
                                    @endforeach
                                </div>
                                <button type="submit" class="w-full mt-2 rounded-xl bg-[#17477f] px-3 py-2.5 text-[10px] font-extrabold text-white">Tampilkan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-[22px] p-2.5 shadow-lg">
                    <div class="grid grid-cols-4 gap-2">
                        {{-- HADIR --}}
                        <div class="rounded-[16px] border border-emerald-100 bg-emerald-50 px-1 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="mt-2 text-[8px] font-bold text-[#173c70]">Hadir</p>
                            <p class="text-[21px] font-extrabold leading-none text-emerald-600 mt-1">{{ $hadir }}</p>
                            <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                        </div>
                        {{-- TERLAMBAT --}}
                        <div class="rounded-[16px] border border-orange-100 bg-orange-50 px-1 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-orange-100">
                                <svg class="h-4 w-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="2.3" d="M12 8v4l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="mt-2 text-[8px] font-bold text-[#173c70]">Terlambat</p>
                            <p class="text-[21px] font-extrabold leading-none text-orange-500 mt-1">{{ $terlambat }}</p>
                            <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                        </div>
                        {{-- IZIN --}}
                        <div class="rounded-[16px] border border-violet-100 bg-violet-50 px-1 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-violet-100">
                                <svg class="h-4 w-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="mt-2 text-[8px] font-bold text-[#173c70]">Izin</p>
                            <p class="text-[21px] font-extrabold leading-none text-violet-600 mt-1">{{ $izin }}</p>
                            <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                        </div>
                        {{-- TIDAK HADIR --}}
                        <div class="rounded-[16px] border border-red-100 bg-red-50 px-1 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="2.3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <p class="mt-2 text-[8px] font-bold text-[#173c70]">Tidak Hadir</p>
                            <p class="text-[21px] font-extrabold leading-none text-red-500 mt-1">{{ $alpa }}</p>
                            <p class="mt-1 text-[6px] text-slate-400">Hari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        [ ]+$
        {{-- INFORMASI AKUN --}}
        <div class="overflow-hidden rounded-[20px] border border-[#dce7f4] bg-white shadow-[0_4px_16px_rgba(35,75,120,0.06)]">

            {{-- HEADER --}}
            <div class="px-3.5 pb-3 pt-3.5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#eaf3ff]">
                        <svg class="h-4 w-4 text-[#123873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[8px] font-semibold uppercase tracking-[0.1em] text-[#7390b2]">Keamanan</p>
                        <h3 class="mt-0.5 text-[13px] font-extrabold text-[#102f5b]">Informasi Akun</h3>
                    </div>
                </div>
            </div>

            {{-- KONTEN --}}
            <div class="px-3 pb-3">
                <div class="grid grid-cols-2 gap-2">

                    {{-- STATUS WAJAH --}}
                    <div class="rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $wajahTerdaftar ? 'bg-emerald-50' : 'bg-red-50' }}">
                                <svg class="h-4 w-4 {{ $wajahTerdaftar ? 'text-emerald-600' : 'text-red-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="2"
                                        d="{{ $wajahTerdaftar ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                                </svg>
                            </div>
                            <span class="rounded-full border px-2 py-1 text-[7px] font-extrabold {{ $wajahTerdaftar ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-red-100 bg-red-50 text-red-700' }}">
                                {{ $wajahTerdaftar ? 'Terdaftar' : 'Belum' }}
                            </span>
                        </div>
                        <p class="mt-2 text-[10px] font-extrabold text-[#183962]">Status Wajah</p>
                        <p class="mt-0.5 text-[8px] leading-relaxed text-[#7892b1]">
                            {{ $wajahTerdaftar ? 'Data wajah sudah tersimpan' : 'Data wajah belum terdaftar' }}
                        </p>
                    </div>

                    {{-- KELOLA DATA WAJAH --}}
                    <a href="{{ route('profil.face') }}"
                        class="flex flex-col rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3 transition active:bg-[#f1f7ff]">
                        <div class="flex w-full items-center justify-between gap-2">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50">
                                <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 11a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 0114 0M18 8h3m-1.5-1.5v3" />
                                </svg>
                            </div>
                            <svg class="h-4 w-4 shrink-0 text-[#7fa0c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <p class="mt-2 text-[10px] font-extrabold text-[#183962]">Kelola Data Wajah</p>
                        <p class="mt-0.5 text-[8px] leading-relaxed text-[#7892b1]">
                            {{ $wajahTerdaftar ? 'Perbarui data wajah untuk absensi' : 'Daftarkan wajah untuk absensi' }}
                        </p>
                        <span class="mt-2 w-fit rounded-full border border-blue-100 bg-blue-50 px-2 py-1 text-[7px] font-extrabold text-blue-700">
                            {{ $wajahTerdaftar ? 'Kelola Wajah' : 'Daftar Wajah' }}
                        </span>
                    </a>

                    {{-- AKUN LOGIN --}}
                    <div class="rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#eaf3ff]">
                                <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="rounded-full border border-blue-100 bg-blue-50 px-2 py-1 text-[7px] font-extrabold text-blue-700">
                                {{ $user->email ? 'Terhubung' : 'Belum tersedia' }}
                            </span>
                        </div>
                        <p class="mt-2 text-[10px] font-extrabold text-[#183962]">Akun Login</p>
                        <p class="mt-0.5 text-[8px] leading-relaxed text-[#7892b1]">
                            Akun terhubung dengan data pegawai.
                        </p>
                    </div>

                    {{-- JAM ABSEN --}}
                    <div class="rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#f0f6fd]">
                                <svg class="h-4 w-4 text-[#416889]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-width="1.8"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-[10px] font-extrabold text-[#183962]">Jam Absen</p>
                        <p class="mt-0.5 text-[8px] leading-relaxed text-[#7892b1]">
                            Masuk: 08:00–16:00<br>
                            Pulang: 16:00–17:00 (WITA)
                        </p>
                    </div>

                    {{-- AKSES APLIKASI --}}
                    <div class="rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#f0f6fd]">
                                <svg class="h-4 w-4 text-[#416889]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2zm3 4h6m-6 4h6m-6 4h3" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-[10px] font-extrabold text-[#183962]">Akses Aplikasi</p>
                        <p class="mt-0.5 text-[8px] leading-relaxed text-[#7892b1]">
                            Sistem Absensi DISKOMINFO
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- PENGATURAN & LAINNYA --}}
        <div class="bg-white rounded-[20px] border border-[#dce7f4] shadow-[0_4px_16px_rgba(35,75,120,0.06)] overflow-hidden">
            <div class="px-3.5 pt-3.5 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('images/icons/settings.png') }}" alt="Pengaturan" class="w-4 h-4 object-contain">
                    </div>
                    <div>
                        <p class="text-[8px] text-[#7390b2] font-semibold uppercase tracking-[0.1em]">Pengaturan</p>
                        <h3 class="text-[13px] font-extrabold text-[#102f5b] mt-0.5">Pengaturan & Lainnya</h3>
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div class="grid grid-cols-2 gap-2">
                    {{-- DATA PROFIL --}}
                    <a href="{{ route('profil.edit') }}" class="rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3 active:bg-[#f3f8fd] transition">
                        <div class="w-8 h-8 rounded-full bg-[#f0f6fd] flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#416889]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-width="1.8" d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM5 21a7 7 0 0114 0" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-extrabold text-[#183962] mt-2">Data Profil</p>
                        <p class="text-[8px] text-[#7892b1] mt-0.5 leading-relaxed">Perbarui nama, foto, nomor HP, dan alamat.</p>
                        <div class="flex justify-end mt-1">
                            <svg class="w-3.5 h-3.5 text-[#7fa0c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                    {{-- RIWAYAT PRESENSI --}}
                    <a href="{{ route('riwayat') }}" class="rounded-[15px] border border-[#e5edf6] bg-[#fbfdff] p-3 active:bg-[#f3f8fd] transition">
                        <div class="w-8 h-8 rounded-full bg-[#f0f6fd] flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#416889]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-width="1.8" d="M9 12h6m-6 4h4m4 4H7a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-extrabold text-[#183962] mt-2">Riwayat Presensi</p>
                        <p class="text-[8px] text-[#7892b1] mt-0.5 leading-relaxed">Lihat detail kehadiran bulan sebelumnya.</p>
                        <div class="flex justify-end mt-1">
                            <svg class="w-3.5 h-3.5 text-[#7fa0c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        {{-- LOGOUT --}}
        <form action="{{ route('logout') }}" method="POST" class="pt-0.5">
            @csrf
            <button type="submit" class="group w-full bg-white border-[1.5px] border-red-400 text-red-500 rounded-[22px] py-3.5 px-4 text-[11px] font-extrabold flex items-center justify-center gap-2 shadow-sm hover:bg-red-50 transition active:scale-[0.98]">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 00-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                </svg>
                Keluar dari Akun
            </button>
        </form>
    </div>
</div>
<script>
    function toggleMonthDropdown() {
        const dropdown = document.getElementById('monthDropdown');
        const chevron = document.getElementById('monthChevron');
        const button = document.getElementById('monthDropdownButton');
        if (!dropdown || !chevron || !button) return;
        dropdown.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
        button.setAttribute('aria-expanded', String(!dropdown.classList.contains('hidden')));
    }

    function updateMonthOptions() {
        const tahun = document.getElementById('pilihTahun')?.value;
        const input = document.getElementById('nilaiBulan');
        if (!tahun || !input) return;
        const bulan = input.value.split('-')[1] || String({
            {
                $bulanSekarang
            }
        }).padStart(2, '0');
        input.value = tahun + '-' + bulan;
        document.getElementById('selectedMonthLabel').textContent = document.querySelector('.month-option.bg-\\[\\#eaf3ff\\]')?.dataset.monthName + ' ' + tahun;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const tahunSelect = document.getElementById('pilihTahun');
        const nilaiBulan = document.getElementById('nilaiBulan');
        const labelBulan = document.getElementById('selectedMonthLabel');
        const monthOptions = document.querySelectorAll('.month-option');
        let bulanAktif = {
            {
                $bulanSekarang
            }
        };
        tahunSelect?.addEventListener('change', function() {
            updateMonthOptions();
        });
        monthOptions.forEach(function(button) {
            button.addEventListener('click', function() {
                bulanAktif = Number(this.dataset.month);
                const tahun = tahunSelect?.value;
                if (!tahun || !nilaiBulan) return;
                nilaiBulan.value = tahun + '-' + String(bulanAktif).padStart(2, '0');
                labelBulan.textContent = this.dataset.monthName + ' ' + tahun;
                monthOptions.forEach(function(option) {
                    option.classList.remove('bg-[#eaf3ff]', 'text-[#1763b0]');
                    option.classList.add('text-[#183962]');
                    option.querySelector('.month-check')?.classList.add('hidden');
                });
                this.classList.add('bg-[#eaf3ff]', 'text-[#1763b0]');
                this.classList.remove('text-[#183962]');
                this.querySelector('.month-check')?.classList.remove('hidden');
            });
        });
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('monthDropdownButton')?.parentElement;
            const dropdown = document.getElementById('monthDropdown');
            const chevron = document.getElementById('monthChevron');
            const button = document.getElementById('monthDropdownButton');
            if (!wrapper || !dropdown || !chevron || !button) return;
            if (!wrapper.contains(event.target)) {
                dropdown.classList.add('hidden');
                chevron.classList.remove('rotate-180');
                button.setAttribute('aria-expanded', 'false');
            }
        });
        if (window.location.hash === '#ringkasan-presensi') {
            setTimeout(function() {
                document.getElementById('ringkasan-presensi')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        }
    });
</script>
@endsection