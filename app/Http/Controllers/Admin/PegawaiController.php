<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with('user')
            ->orderBy('nama')
            ->get();
        $totalPegawai = $pegawai->count();
        $akunTerdaftar = $pegawai->filter(fn($item) => $item->user !== null)->count();
        $wajahTerdaftar = $pegawai->filter(fn($item) => !empty($item->face_vector))->count();
        return view('admin.pegawai.index', compact(
            'pegawai',
            'totalPegawai',
            'akunTerdaftar',
            'wajahTerdaftar'
        ));
    }
[ 	]+$
    public function show(Pegawai $pegawai)
    {
        $pegawai->load([
            'user',
            'absensis' => function ($query) {
                $query->orderByDesc('tanggal')->orderByDesc('jam_masuk');
            },
            'izins' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ]);
        $sekarang = \Carbon\Carbon::now('Asia/Makassar')->startOfDay();
        $awalBulan = $sekarang->copy()->startOfMonth();
        $absensiPerTanggal = $pegawai->absensis->keyBy(function ($absensi) {
            return \Carbon\Carbon::parse($absensi->tanggal)->toDateString();
        });
        $rekapKehadiran = collect();
        for ($tanggal = $sekarang->copy(); $tanggal->gte($awalBulan); $tanggal->subDay()) {
            if ($tanggal->isWeekend()) {
                continue;
            }
            $tanggalString = $tanggal->toDateString();
            $absensi = $absensiPerTanggal->get($tanggalString);
            $izinDisetujui = $pegawai->izins->first(function ($izin) use ($tanggalString) {
                if (strtolower($izin->status ?? '') !== 'disetujui') {
                    return false;
                }
                $mulai = $izin->tanggal_mulai
                    ? \Carbon\Carbon::parse($izin->tanggal_mulai)->toDateString()
                    : null;
                $selesai = $izin->tanggal_selesai
                    ? \Carbon\Carbon::parse($izin->tanggal_selesai)->toDateString()
                    : $mulai;
                return $mulai && $selesai && $tanggalString >= $mulai && $tanggalString <= $selesai;
            });
            if ($absensi) {
                $status = ucfirst(strtolower($absensi->status ?? 'Tidak diketahui'));
                $keterangan = 'Berdasarkan catatan absensi';
            } elseif ($izinDisetujui) {
                $jenisIzin = strtolower($izinDisetujui->jenis_izin ?? 'izin');
                $status = str_contains($jenisIzin, 'sakit') ? 'Sakit' : 'Izin';
                $keterangan = 'Pengajuan izin disetujui';
            } elseif ($tanggal->isSameDay($sekarang)) {
                $status = 'Belum Absen';
                $keterangan = 'Belum ada catatan absensi hari ini';
            } else {
                $status = 'Tidak Hadir';
                $keterangan = 'Tidak ditemukan absensi atau izin yang disetujui';
            }
            $rekapKehadiran->push([
                'tanggal' => $tanggalString,
                'jam_masuk' => $absensi->jam_masuk ?? null,
                'jam_pulang' => $absensi->jam_pulang ?? null,
                'status' => $status,
                'keterangan' => $keterangan,
            ]);
        }
        return view('admin.pegawai.show', compact(
            'pegawai',
            'rekapKehadiran'
        ));
    }
}
