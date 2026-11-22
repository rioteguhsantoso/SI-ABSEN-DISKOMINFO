@extends('admin.layouts.app')
@section('title', 'Data Pegawai')
@section('page_title', 'Data Pegawai')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Data Pegawai</h1>
            <p class="mt-1 text-sm text-slate-500">Daftar pegawai dan keterhubungan akun User SI-ABSEN DISKOMINFO.</p>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Pegawai</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalPegawai }}</p>
            <p class="mt-1 text-xs text-slate-400">Data master pegawai</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Akun Terdaftar</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $akunTerdaftar }}</p>
            <p class="mt-1 text-xs text-slate-400">Pegawai yang memiliki akun User</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Wajah Terdaftar</p>
            <p class="mt-2 text-3xl font-extrabold text-blue-600">{{ $wajahTerdaftar }}</p>
            <p class="mt-1 text-xs text-slate-400">Data face vector sudah terisi</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-extrabold text-slate-800">Daftar Pegawai</h2>
            <p class="mt-1 text-xs text-slate-400">Informasi identitas, akun, dan pendaftaran wajah.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Pegawai</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">NIP</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Email</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Akun</th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-400 uppercase">Wajah</th>
                        <th class="px-5 py-4 text-right text-[11px] font-bold text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pegawai as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-700">{{ $item->nama }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">ID Pegawai #{{ $item->id }}</p>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->nip }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->user->email ?? '-' }}</td>
                        <td class="px-5 py-4">
                            @if($item->user)
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">Akun Terdaftar</span>
                            @else
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-bold">Belum Ada Akun</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if(!empty($item->face_vector))
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">Terdaftar</span>
                            @else
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">Belum Terdaftar</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.pegawai.show', $item->id) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#123873] text-white text-xs font-bold hover:bg-[#0d2a57] transition">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <p class="text-sm font-semibold text-slate-500">Belum ada pegawai terdaftar.</p>
                            <p class="text-xs text-slate-400 mt-1">Data pegawai akan muncul jika data master pegawai tersedia.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection