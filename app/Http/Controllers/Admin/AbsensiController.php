<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\Izin;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $waktuSekarang = Carbon::now('Asia/Makassar');
        $sekarang = $waktuSekarang->copy()->startOfDay();
        $bulan = $request->input('bulan', $sekarang->format('Y-m'));
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
            $bulan = $sekarang->format('Y-m');
        }
        $tanggal = $request->input('tanggal');
        if ($tanggal) {
            try {
                $tanggalValid = Carbon::createFromFormat('!Y-m-d', $tanggal, 'Asia/Makassar');
                if ($tanggalValid->format('Y-m-d') !== $tanggal) {
                    $tanggal = null;
                } else {
                    $bulan = $tanggalValid->format('Y-m');
                }
            } catch (\Throwable $e) {
                $tanggal = null;
            }
        }
        $awalBulan = Carbon::createFromFormat('!Y-m', $bulan, 'Asia/Makassar')->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();
        $batasTidakHadir = $waktuSekarang->format('H:i:s') >= '17:00:00'
            ? $sekarang->copy()
            : $sekarang->copy()->subDay();
        if ($batasTidakHadir->gt($akhirBulan)) {
            $batasTidakHadir = $akhirBulan->copy();
        }
        $pegawaiId = $request->input('pegawai_id');
        if ($pegawaiId && !Pegawai::whereKey($pegawaiId)->exists()) {
            $pegawaiId = null;
        }
        $pegawai = Pegawai::with('user')->orderBy('nama')->get();
        $dataAbsensi = Absensi::with('pegawai')
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'pegawai_id' => $item->pegawai_id,
                    'pegawai' => $item->pegawai,
                    'tanggal' => Carbon::parse($item->tanggal)->toDateString(),
                    'jam_masuk' => $item->jam_masuk,
                    'jam_pulang' => $item->jam_pulang,
                    'status' => strtolower($item->status ?? ''),
                ];
            });
        $izinDisetujui = Izin::with('pegawai')
            ->where('status', 'disetujui')
            ->whereNotNull('tanggal_mulai')
            ->whereDate('tanggal_mulai', '<=', $sekarang->toDateString())
            ->where(function ($query) use ($awalBulan) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $awalBulan->toDateString());
            })
            ->get();
        $dataIzin = collect();
        foreach ($izinDisetujui as $izinItem) {
            if (!$izinItem->pegawai) {
                continue;
            }
            $mulaiAsli = Carbon::parse($izinItem->tanggal_mulai, 'Asia/Makassar')->startOfDay();
            $selesaiAsli = $izinItem->tanggal_selesai
                ? Carbon::parse($izinItem->tanggal_selesai, 'Asia/Makassar')->startOfDay()
                : $mulaiAsli->copy();
            if (
                $selesaiAsli->lt($mulaiAsli) ||
                $selesaiAsli->lt($awalBulan) ||
                $mulaiAsli->gt($akhirBulan) ||
                $mulaiAsli->gt($sekarang)
            ) {
                continue;
            }
            $mulai = $mulaiAsli->lt($awalBulan) ? $awalBulan->copy() : $mulaiAsli->copy();
            $akhirIzinTampil = $sekarang->copy();
            if ($akhirIzinTampil->gt($akhirBulan)) {
                $akhirIzinTampil = $akhirBulan->copy();
            }
            $selesai = $selesaiAsli->gt($akhirIzinTampil)
                ? $akhirIzinTampil->copy()
                : $selesaiAsli->copy();
            if ($selesai->lt($mulai)) {
                continue;
            }
            $jenis = strtolower($izinItem->jenis_izin ?? '');
            if ($jenis === 'sakit') {
                $statusIzin = 'sakit';
            } elseif ($jenis === 'keperluan_dinas') {
                $statusIzin = 'izin dinas';
            } else {
                $statusIzin = 'izin';
            }
            foreach (CarbonPeriod::create($mulai, $selesai) as $hariIzin) {
                if ($hariIzin->isWeekend()) {
                    continue;
                }
                $dataIzin->push((object) [
                    'pegawai_id' => $izinItem->pegawai_id,
                    'pegawai' => $izinItem->pegawai,
                    'tanggal' => $hariIzin->toDateString(),
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'status' => $statusIzin,
                ]);
            }
        }
        // Tandai tanggal yang sudah memiliki catatan absensi atau izin disetujui.
        $tanggalTercatat = [];
        foreach ($dataAbsensi as $item) {
            $kunci = $item->pegawai_id . '|' . $item->tanggal;
            $tanggalTercatat[$kunci] = true;
        }
        foreach ($dataIzin as $item) {
            $kunci = $item->pegawai_id . '|' . $item->tanggal;
            $tanggalTercatat[$kunci] = true;
        }
        // Tentukan awal penghitungan berdasarkan hari kerja setelah akun dibuat.
        $tanggalMulaiHitung = [];
        foreach ($pegawai as $pegawaiItem) {
            $user = $pegawaiItem->user;
            if (!$user || !$user->created_at) {
                continue;
            }
            $mulaiHitung = Carbon::parse($user->created_at, 'Asia/Makassar')->startOfDay()->addDay();
            while ($mulaiHitung->isWeekend()) {
                $mulaiHitung->addDay();
            }
            $tanggalMulaiHitung[$pegawaiItem->id] = $mulaiHitung->toDateString();
        }
        // Tidak Hadir hanya dibuat untuk hari kerja yang sudah selesai.
        $dataTidakHadir = collect();
        if ($batasTidakHadir->gte($awalBulan)) {
            $akhirPemeriksaan = $batasTidakHadir->lt($akhirBulan)
                ? $batasTidakHadir->copy()
                : $akhirBulan->copy();
            foreach (CarbonPeriod::create($awalBulan, $akhirPemeriksaan) as $hari) {
                if ($hari->isWeekend()) {
                    continue;
                }
                foreach ($pegawai as $pegawaiItem) {
                    $tanggalMulai = $tanggalMulaiHitung[$pegawaiItem->id] ?? null;
                    if (!$tanggalMulai || $hari->toDateString() < $tanggalMulai) {
                        continue;
                    }
                    $kunci = $pegawaiItem->id . '|' . $hari->toDateString();
                    if (isset($tanggalTercatat[$kunci])) {
                        continue;
                    }
                    $dataTidakHadir->push((object) [
                        'pegawai_id' => $pegawaiItem->id,
                        'pegawai' => $pegawaiItem,
                        'tanggal' => $hari->toDateString(),
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'status' => 'tidak_hadir',
                    ]);
                    $tanggalTercatat[$kunci] = true;
                }
            }
        }
        // Gabungkan catatan absensi, izin per hari, dan Tidak Hadir.
        $semuaData = $dataAbsensi->merge($dataIzin)->merge($dataTidakHadir);
        if ($pegawaiId) {
            $semuaData = $semuaData->where('pegawai_id', (int) $pegawaiId);
        }
        if ($tanggal) {
            $semuaData = $semuaData->where('tanggal', $tanggal);
        }
        $absensi = $semuaData->sort(function ($a, $b) {
            $bandingTanggal = strcmp((string) $b->tanggal, (string) $a->tanggal);
            if ($bandingTanggal !== 0) {
                return $bandingTanggal;
            }
            return strcmp((string) ($b->jam_masuk ?? ''), (string) ($a->jam_masuk ?? ''));
        })->values();
        // Terlambat juga dihitung sebagai hadir.
        $hadir = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat = $absensi->where('status', 'terlambat')->count();
        // Izin dihitung per hari yang sudah tiba, termasuk sakit dan izin dinas.
        $izin = $absensi->whereIn('status', ['izin', 'izin dinas', 'sakit'])->count();
        $tidakHadir = $absensi->whereIn('status', ['tidak_hadir', 'alpa'])->count();
        return view('Admin.absensi.index', compact(
            'absensi',
            'pegawai',
            'pegawaiId',
            'tanggal',
            'bulan',
            'hadir',
            'terlambat',
            'izin',
            'tidakHadir'
        ));
    }
}
