<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\Izin;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        $sekarang = Carbon::now('Asia/Makassar');
        $hariIni = $sekarang->copy()->startOfDay();
        $bulanDipilih = request('bulan', $sekarang->format('Y-m'));
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulanDipilih)) {
            $bulanDipilih = $sekarang->format('Y-m');
        }
        $awalBulan = Carbon::createFromFormat('!Y-m', $bulanDipilih, 'Asia/Makassar')->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();
        $totalPegawai = Pegawai::count();
        $absensiBulan = Absensi::with('pegawai')
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->get();
        $absensiAktual = $absensiBulan->filter(function ($item) use ($hariIni) {
            return Carbon::parse($item->tanggal, 'Asia/Makassar')->startOfDay()->lte($hariIni);
        });
        $hadir = $absensiBulan->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat = $absensiBulan->where('status', 'terlambat')->count();
        $izinDisetujui = Izin::with('pegawai')
            ->where('status', 'disetujui')
            ->whereNotNull('tanggal_mulai')
            ->whereDate('tanggal_mulai', '<=', $akhirBulan->toDateString())
            ->where(function ($query) use ($awalBulan) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $awalBulan->toDateString());
            })
            ->get();
        $izin = $izinDisetujui->count();
        $izinMenunggu = Izin::where('status', 'menunggu')->count();
        $tanggalTercatat = [];
        foreach ($absensiBulan as $item) {
            $tanggalTercatat[$item->pegawai_id . '|' . Carbon::parse($item->tanggal, 'Asia/Makassar')->toDateString()] = true;
        }
        $dataIzin = collect();
        foreach ($izinDisetujui as $izinItem) {
            if (!$izinItem->pegawai || !$izinItem->tanggal_mulai) {
                continue;
            }
            $mulaiAsli = Carbon::parse($izinItem->tanggal_mulai, 'Asia/Makassar')->startOfDay();
            $selesaiAsli = $izinItem->tanggal_selesai
                ? Carbon::parse($izinItem->tanggal_selesai, 'Asia/Makassar')->startOfDay()
                : $mulaiAsli->copy();
            if ($selesaiAsli->lt($mulaiAsli) || $selesaiAsli->lt($awalBulan) || $mulaiAsli->gt($akhirBulan) || $mulaiAsli->gt($hariIni)) {
                continue;
            }
            $mulai = $mulaiAsli->lt($awalBulan) ? $awalBulan->copy() : $mulaiAsli->copy();
            $akhirTampil = $hariIni->lt($akhirBulan) ? $hariIni->copy() : $akhirBulan->copy();
            $selesai = $selesaiAsli->gt($akhirTampil) ? $akhirTampil->copy() : $selesaiAsli->copy();
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
            foreach (CarbonPeriod::create($mulai, $selesai) as $tanggalIzin) {
                if ($tanggalIzin->isWeekend()) {
                    continue;
                }
                $tanggalString = $tanggalIzin->toDateString();
                $kunci = $izinItem->pegawai_id . '|' . $tanggalString;
                $tanggalTercatat[$kunci] = true;
                $dataIzin->push((object) [
                    'pegawai' => $izinItem->pegawai,
                    'tanggal' => $tanggalString,
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'status' => $statusIzin,
                ]);
            }
        }
        $batasTidakHadir = $sekarang->format('H:i:s') >= '17:00:00'
            ? $hariIni->copy()
            : $hariIni->copy()->subDay();
        if ($batasTidakHadir->gt($akhirBulan)) {
            $batasTidakHadir = $akhirBulan->copy();
        }
        $dataTidakHadir = collect();
        $pegawaiDenganUser = Pegawai::with('user')->orderBy('nama')->get();
        $tanggalMulaiHitung = [];
        foreach ($pegawaiDenganUser as $pegawaiItem) {
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
        if ($batasTidakHadir->gte($awalBulan)) {
            $akhirPemeriksaan = $batasTidakHadir->lt($akhirBulan)
                ? $batasTidakHadir->copy()
                : $akhirBulan->copy();
            foreach (CarbonPeriod::create($awalBulan, $akhirPemeriksaan) as $tanggalPeriksa) {
                if ($tanggalPeriksa->isWeekend()) {
                    continue;
                }
                foreach ($pegawaiDenganUser as $pegawaiItem) {
                    $tanggalMulai = $tanggalMulaiHitung[$pegawaiItem->id] ?? null;
                    if (!$tanggalMulai || $tanggalPeriksa->toDateString() < $tanggalMulai) {
                        continue;
                    }
                    $kunci = $pegawaiItem->id . '|' . $tanggalPeriksa->toDateString();
                    if (isset($tanggalTercatat[$kunci])) {
                        continue;
                    }
                    $dataTidakHadir->push((object) [
                        'pegawai' => $pegawaiItem,
                        'tanggal' => $tanggalPeriksa->toDateString(),
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'status' => 'tidak hadir',
                    ]);
                    $tanggalTercatat[$kunci] = true;
                }
            }
        }
        $tidakHadir = $dataTidakHadir->count();
        $dataAbsensiTerbaru = $absensiAktual->map(function ($item) {
            return (object) [
                'pegawai' => $item->pegawai,
                'tanggal' => Carbon::parse($item->tanggal, 'Asia/Makassar')->toDateString(),
                'jam_masuk' => $item->jam_masuk,
                'jam_pulang' => $item->jam_pulang,
                'status' => strtolower($item->status ?? ''),
            ];
        });
        $absensiTerbaru = $dataAbsensiTerbaru
            ->merge($dataIzin)
            ->sort(function ($a, $b) {
                $bandingTanggal = strcmp((string) $b->tanggal, (string) $a->tanggal);
                if ($bandingTanggal !== 0) {
                    return $bandingTanggal;
                }
                return strcmp((string) ($b->jam_masuk ?? ''), (string) ($a->jam_masuk ?? ''));
            })
            ->take(10)
            ->values();
        $totalAbsensi = $absensiAktual->count();
        $daftarBulan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $nilaiBulan = sprintf('%04d-%02d', $sekarang->year, $bulan);
            $labelBulan = Carbon::create($sekarang->year, $bulan, 1, 0, 0, 0, 'Asia/Makassar')
                ->locale('id')
                ->translatedFormat('F Y');
            $daftarBulan[$nilaiBulan] = ucfirst($labelBulan);
        }
        $pengajuanTerbaru = Izin::with('pegawai')
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();
        return view('admin.dashboard', compact(
            'totalPegawai',
            'hadir',
            'terlambat',
            'izin',
            'izinMenunggu',
            'tidakHadir',
            'totalAbsensi',
            'absensiTerbaru',
            'pengajuanTerbaru',
            'bulanDipilih',
            'daftarBulan'
        ));
    }
}
