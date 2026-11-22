@extends('admin.layouts.app')
@section('title', 'Detail Pegawai')
@section('page_title', 'Detail Pegawai')
@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Detail Pegawai</h1>
            <p class="mt-1 text-sm text-slate-500">Informasi identitas, akun, dan aktivitas pegawai SI-ABSEN DISKOMINFO.</p>
        </div>
        <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-bold hover:bg-slate-50 transition">
            <span>&larr;</span>
            Kembali ke Data Pegawai
        </a>
    </div>

    {{-- Identitas Pegawai --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-[#123873] px-6 py-6 sm:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-white text-2xl font-extrabold">
                    {{ strtoupper(substr($pegawai->nama ?? 'P', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-200">Profil Pegawai</p>
                    <h2 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">{{ $pegawai->nama }}</h2>
                    <p class="mt-1 text-sm text-blue-100">NIP: {{ $pegawai->nip }}</p>
                </div>
                <div>
                    @if($pegawai->user)
                    <span class="inline-flex px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Akun Terdaftar</span>
                    @else
                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white/15 text-white text-xs font-bold">Belum Ada Akun</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">ID Pegawai</p>
                    <p class="mt-2 font-bold text-slate-800">#{{ $pegawai->id }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">NIP</p>
                    <p class="mt-2 font-bold text-slate-800 break-words">{{ $pegawai->nip }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Email Akun</p>
                    <p class="mt-2 font-bold text-slate-800 break-words">{{ $pegawai->user->email ?? 'Belum ada akun' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Pendaftaran Wajah</p>
                    @if(!empty($pegawai->face_vector))
                    <p class="mt-2 font-bold text-emerald-600">Sudah Terdaftar</p>
                    <p class="mt-1 text-xs text-slate-400">Data face vector tersedia.</p>
                    @else
                    <p class="mt-2 font-bold text-amber-600">Belum Terdaftar</p>
                    <p class="mt-1 text-xs text-slate-400">Data face vector belum tersedia.</p>
                    @endif
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Tanggal Data Dibuat</p>
                    <p class="mt-2 font-bold text-slate-800">{{ $pegawai->created_at ? $pegawai->created_at->format('d M Y, H:i') : '-' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Pembaruan Terakhir</p>
                    <p class="mt-2 font-bold text-slate-800">{{ $pegawai->updated_at ? $pegawai->updated_at->format('d M Y, H:i') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Kehadiran Bulan Ini --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-5 sm:px-6 border-b border-slate-100">
            <h2 class="font-extrabold text-slate-800">Rekap Kehadiran Bulan Ini</h2>
            <p class="mt-1 text-xs text-slate-400">Rekap hari kerja pegawai sejak awal bulan hingga hari ini.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Tanggal</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Jam Masuk</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Jam Pulang</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Status</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekapKehadiran as $rekap)
                    @php
                    $statusClass = match(strtolower($rekap['status'])) {
                    'hadir' => 'bg-emerald-50 text-emerald-700',
                    'terlambat' => 'bg-amber-50 text-amber-700',
                    'izin' => 'bg-violet-50 text-violet-700',
                    'sakit' => 'bg-blue-50 text-blue-700',
                    'tidak hadir', 'alpa' => 'bg-red-50 text-red-700',
                    'belum absen' => 'bg-slate-100 text-slate-600',
                    default => 'bg-slate-100 text-slate-600',
                    };
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4 font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($rekap['tanggal'])->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            {{ $rekap['jam_masuk'] ? \Carbon\Carbon::parse($rekap['jam_masuk'])->format('H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            {{ $rekap['jam_pulang'] ? \Carbon\Carbon::parse($rekap['jam_pulang'])->format('H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold {{ $statusClass }}">
                                {{ $rekap['status'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-500">{{ $rekap['keterangan'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                            Belum ada rekap kehadiran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-5 sm:px-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-extrabold text-slate-800">Riwayat Absensi</h2>
                <p class="mt-1 text-xs text-slate-400">Catatan absensi pegawai berdasarkan data yang tersimpan.</p>
            </div>
            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">{{ $pegawai->absensis->count() }} Catatan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Tanggal</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Jam Masuk</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Jam Pulang</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pegawai->absensis as $absensi)
                    @php
                    $status = strtolower($absensi->status ?? '');
                    $statusClass = match($status) {
                    'hadir' => 'bg-emerald-50 text-emerald-700',
                    'terlambat' => 'bg-amber-50 text-amber-700',
                    'izin' => 'bg-violet-50 text-violet-700',
                    'sakit' => 'bg-blue-50 text-blue-700',
                    'alpa' => 'bg-red-50 text-red-700',
                    default => 'bg-slate-100 text-slate-600',
                    };
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4 font-semibold text-slate-700">
                            {{ $absensi->tanggal ? \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            {{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold {{ $statusClass }}">
                                {{ ucfirst($status ?: 'Tidak diketahui') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <p class="font-semibold text-slate-500">Belum ada riwayat absensi.</p>
                            <p class="mt-1 text-xs text-slate-400">Riwayat akan muncul setelah data absensi tersimpan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat Izin --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-5 sm:px-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-extrabold text-slate-800">Riwayat Pengajuan Izin</h2>
                <p class="mt-1 text-xs text-slate-400">Daftar pengajuan izin yang terhubung dengan pegawai ini.</p>
            </div>
            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">{{ $pegawai->izins->count() }} Pengajuan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Jenis</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Tanggal Mulai</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Tanggal Selesai</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Keterangan</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pegawai->izins as $izin)
                    @php
                    $statusIzin = strtolower($izin->status ?? '');
                    $izinClass = match($statusIzin) {
                    'disetujui' => 'bg-emerald-50 text-emerald-700',
                    'menunggu' => 'bg-amber-50 text-amber-700',
                    'ditolak' => 'bg-red-50 text-red-700',
                    default => 'bg-slate-100 text-slate-600',
                    };
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4 font-semibold text-slate-700">{{ ucfirst($izin->jenis_izin ?? '-') }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $izin->tanggal_mulai ? \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $izin->tanggal_selesai ? \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-4 text-slate-600 min-w-48">{{ $izin->keterangan ?: '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold {{ $izinClass }}">
                                {{ ucfirst($statusIzin ?: 'Tidak diketahui') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center">
                            <p class="font-semibold text-slate-500">Belum ada pengajuan izin.</p>
                            <p class="mt-1 text-xs text-slate-400">Pengajuan izin pegawai akan tampil di bagian ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection