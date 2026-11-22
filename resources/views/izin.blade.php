
@extends('layouts.app')
@section('title', 'Izin - SI-ABSEN DISKOMINFO')
@section('page_title', 'Pengajuan Izin')
@section('content')
@php
$headerImage = file_exists(public_path('images/dashboard-header.jpg'))
? asset('images/dashboard-header.jpg')
: null;
$fotoProfil = $pegawai->foto
? asset('storage/' . $pegawai->foto)
: asset('images/default-profile.png');
@endphp
<div class="min-h-screen bg-[#f5f8fd] pb-8">
    <style>
        .izin-hero {
            background: linear-gradient(180deg, rgba(20, 99, 181, .94) 0%, rgba(45, 125, 203, .86) 48%, rgba(38, 105, 177, .97) 100%);
        }
        .izin-hero.has-image {
            background-image: linear-gradient(180deg, rgba(24, 108, 190, .82) 0%, rgba(43, 126, 201, .62) 46%, rgba(15, 71, 130, .90) 100%), url('{{ $headerImage }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    {{-- HEADER --}}
    <section class="izin-hero {{ $headerImage ? 'has-image' : '' }} relative overflow-hidden rounded-b-[34px] text-white">
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#1c69ad]/30 to-transparent"></div>
        <div class="relative z-10 px-5 pb-10 pt-5">
            {{-- BRAND --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/95 shadow-lg shadow-black/10">
                        <img src="{{ asset('images/logo-header.jpg') }}" alt="Logo Diskominfo" class="h-8 w-8 object-contain">
                    </div>
                    <div>
                        <p class="text-[15px] font-extrabold leading-none tracking-tight text-white">DISKOMINFO</p>
                        <p class="mt-1 text-[8px] font-bold text-white/90">Kota Banjarmasin</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- NOTIFIKASI --}}
                    <a href="{{ route('notifications.index') }}" aria-label="Notifikasi" class="relative flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-white/15 backdrop-blur-sm transition active:scale-95">
                        <svg class="h-5 w-5 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                        </svg>
                        @if(($jumlahNotifikasiBelumDibaca ?? 0) > 0)
                        <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[6px] font-extrabold text-white ring-2 ring-[#3784c7]">
                            {{ $jumlahNotifikasiBelumDibaca > 9 ? '9+' : $jumlahNotifikasiBelumDibaca }}
                        </span>
                        @endif
                    </a>
                    {{-- FOTO PROFIL --}}
                    <a href="{{ url('/profil') }}" aria-label="Profil" class="block h-9 w-9 overflow-hidden rounded-full border-2 border-white/70 bg-white/20 shadow-sm backdrop-blur-sm transition active:scale-95">
                        <img src="{{ $fotoProfil }}" alt="Foto Profil" class="h-full w-full object-cover">
                    </a>
                </div>
            </div>
            {{-- JUDUL HALAMAN --}}
            <div class="mt-7 flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 shadow-sm backdrop-blur-sm">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-[20px] font-extrabold leading-tight tracking-tight">Pengajuan Izin</h1>
                    <p class="mt-1 text-[8px] font-medium text-white/75">Ajukan izin ketidakhadiran atau keterlambatan Anda.</p>
                </div>
            </div>
        </div>
    </section>
    <div class="px-4 py-5 space-y-4">
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-3.5 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold text-emerald-700">Pengajuan Berhasil</p>
                <p class="text-[10px] text-emerald-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-50 border border-red-100 rounded-2xl p-3.5 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.63 19.7h14.74a2 2 0 001.74-3.02l-7.4-12.82a2 2 0 00-3.42 0z" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-extrabold text-red-700">Periksa Pengajuan</p>
                @foreach($errors->all() as $error)
                <p class="text-[10px] text-red-600 mt-0.5 leading-relaxed">{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 pt-5 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#123873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V5a4 4 0 018 0v2m-9 0h10a2 2 0 012 2v9a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2zm2 5h6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Form Pengajuan</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Lengkapi data izin sesuai kebutuhan.</p>
                    </div>
                </div>
            </div>
            <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <div>
                    <label for="jenis_izin" class="block text-[11px] font-bold text-slate-700 mb-1.5">Jenis Izin</label>
                    <select id="jenis_izin" name="jenis_izin" required class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-3 text-xs font-medium text-slate-700 outline-none focus:border-[#123873] focus:ring-2 focus:ring-blue-100 transition">
                        <option value="">Pilih jenis izin</option>
                        <option value="sakit" {{ old('jenis_izin') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="keperluan_keluarga" {{ old('jenis_izin') === 'keperluan_keluarga' ? 'selected' : '' }}>Keperluan Keluarga</option>
                        <option value="keperluan_dinas" {{ old('jenis_izin') === 'keperluan_dinas' ? 'selected' : '' }}>Keperluan Dinas</option>
                        <option value="keperluan_pribadi" {{ old('jenis_izin') === 'keperluan_pribadi' ? 'selected' : '' }}>Keperluan Pribadi</option>
                        <option value="cuti" {{ old('jenis_izin') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="izin_terlambat" {{ old('jenis_izin') === 'izin_terlambat' ? 'selected' : '' }}>Izin Terlambat</option>
                    </select>
                </div>
                <div id="tanggalDinas" class="hidden space-y-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <div>
                        <p id="judulTanggal" class="text-[10px] font-extrabold text-[#123873]">Jadwal Dinas</p>
                        <p id="deskripsiTanggal" class="text-[9px] text-slate-500 mt-0.5">Tanggal diperlukan untuk mencatat periode keperluan dinas.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="tanggal_mulai" class="block text-[10px] font-bold text-slate-700 mb-1.5">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-2.5 text-[11px] font-medium text-slate-700 outline-none focus:border-[#123873] focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-[10px] font-bold text-slate-700 mb-1.5">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full h-10 rounded-xl border border-slate-200 bg-white px-2.5 text-[11px] font-medium text-slate-700 outline-none focus:border-[#123873] focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                    </div>
                </div>
                {{-- UPLOAD SURAT TUGAS DINAS --}}
                <div id="uploadSuratDinas" class="hidden space-y-2 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <div>
                        <label for="surat_dinas" class="block text-[10px] font-extrabold text-[#123873]">Upload Surat Tugas</label>
                        <p class="mt-0.5 text-[9px] text-slate-500">Lampirkan surat tugas untuk keperluan dinas.</p>
                    </div>
                    <input type="file" id="surat_dinas" name="surat_dinas" accept=".pdf,.jpg,.jpeg,.png" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[10px] text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-[10px] file:font-bold file:text-[#123873]">
                    <p class="text-[9px] text-slate-400">Format yang didukung: PDF, JPG, JPEG, PNG.</p>
                </div>
                <div id="infoTerlambat" class="hidden bg-amber-50 border border-amber-100 rounded-2xl p-3">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-[10px] leading-relaxed text-amber-700"><strong>Izin Terlambat:</strong> Anda tetap wajib melakukan scan absensi ketika tiba di kantor. Status kehadiran akan tercatat sebagai terlambat.</p>
                    </div>
                </div>
                <div>
                    <label for="keterangan" class="block text-[11px] font-bold text-slate-700 mb-1.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="4" maxlength="1000" required placeholder="Jelaskan alasan pengajuan izin..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-xs font-medium text-slate-700 placeholder:text-slate-400 outline-none resize-none focus:border-[#123873] focus:ring-2 focus:ring-blue-100 transition">{{ old('keterangan') }}</textarea>
                    <p class="text-[9px] text-slate-400 mt-1.5">Maksimal 1000 karakter.</p>
                </div>
                <button type="submit" class="w-full h-11 rounded-xl bg-[#123873] hover:bg-[#0c2b5b] text-white text-xs font-bold shadow-lg shadow-blue-900/15 transition active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6" />
                    </svg>
                    <span>Kirim Pengajuan</span>
                </button>
            </form>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#123873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l2.5 1.5M19 12a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Riwayat Pengajuan</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Status pengajuan izin Anda.</p>
                    </div>
                </div>
            </div>
            @forelse($izins as $izin)
            <div class="px-5 py-4 border-b border-slate-100 last:border-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800">
                            @switch($izin->jenis_izin)
                            @case('sakit') Sakit @break
                            @case('keperluan_keluarga') Keperluan Keluarga @break
                            @case('keperluan_dinas') Keperluan Dinas @break
                            @case('keperluan_pribadi') Keperluan Pribadi @break
                            @case('cuti') Cuti @break
                            @case('izin_terlambat') Izin Terlambat @break
                            @default {{ ucfirst(str_replace('_', ' ', $izin->jenis_izin)) }}
                            @endswitch
                        </p>
                        @if($izin->tanggal_mulai)
                        <p class="text-[10px] text-slate-400 mt-1">
                            {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }}
                            @if($izin->tanggal_selesai)
                            - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                            @endif
                        </p>
                        @endif
                        <p class="text-[10px] text-slate-500 mt-2 leading-relaxed">{{ $izin->keterangan }}</p>
                    </div>
                    @if($izin->status === 'menunggu')
                    <span class="shrink-0 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 text-[9px] font-bold">Menunggu</span>
                    @elseif($izin->status === 'disetujui')
                    <span class="shrink-0 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-bold">Disetujui</span>
                    @elseif($izin->status === 'ditolak')
                    <span class="shrink-0 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-[9px] font-bold">Ditolak</span>
                    @else
                    <span class="shrink-0 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[9px] font-bold">{{ ucfirst($izin->status) }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-5 py-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-600 mt-3">Belum Ada Pengajuan</p>
                <p class="text-[10px] text-slate-400 mt-1">Pengajuan izin yang Anda buat akan muncul di sini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    const jenisIzin = document.getElementById('jenis_izin');
    const tanggalDinas = document.getElementById('tanggalDinas');
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const infoTerlambat = document.getElementById('infoTerlambat');
    const uploadSuratDinas = document.getElementById('uploadSuratDinas');
    const suratDinas = document.getElementById('surat_dinas');
    const judulTanggal = document.getElementById('judulTanggal');
    const deskripsiTanggal = document.getElementById('deskripsiTanggal');
    function updateFormIzin() {
        const isDinas = jenisIzin.value === 'keperluan_dinas';
        const isCuti = jenisIzin.value === 'cuti';
        const isTerlambat = jenisIzin.value === 'izin_terlambat';
        const perluTanggal = isDinas || isCuti;
        tanggalDinas.classList.toggle('hidden', !perluTanggal);
        infoTerlambat.classList.toggle('hidden', !isTerlambat);
        uploadSuratDinas.classList.toggle('hidden', !isDinas);
        tanggalMulai.required = perluTanggal;
        tanggalSelesai.required = perluTanggal;
        if (isCuti) {
            judulTanggal.textContent = 'Periode Cuti';
            deskripsiTanggal.textContent = 'Tentukan tanggal mulai dan selesai cuti.';
        } else {
            judulTanggal.textContent = 'Jadwal Dinas';
            deskripsiTanggal.textContent = 'Tanggal diperlukan untuk mencatat periode keperluan dinas.';
        }
        if (!perluTanggal) {
            tanggalMulai.value = '';
            tanggalSelesai.value = '';
        }
        if (!isDinas) {
            suratDinas.value = '';
        }
    }
    jenisIzin.addEventListener('change', updateFormIzin);
    updateFormIzin();
</script>
@endpush
