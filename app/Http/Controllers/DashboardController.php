<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        app(NotificationService::class)->generateForUser($user);
        $pegawai = $user->pegawai;
        if (!$pegawai) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data pegawai tidak ditemukan.');
        }

        $sekarang = Carbon::now('Asia/Makassar');
        $hariIni = $sekarang->toDateString();
        $jamSekarang = $sekarang->format('H:i:s');

        // Ambil data absensi hari ini terlebih dahulu
        $absensiHariIni = $pegawai->absensis()
            ->whereDate('tanggal', $hariIni)
            ->first();

        // Cek izin yang sudah disetujui dan masih berlaku hari ini
        $izinAktif = $pegawai->izins()
            ->where('status', 'disetujui')
            ->where('jenis_izin', '!=', 'izin_terlambat')
            ->whereDate('tanggal_mulai', '<=', $hariIni)
            ->whereDate('tanggal_selesai', '>=', $hariIni)
            ->first();

        // Izin aktif hanya berlaku jika belum melakukan absensi hari ini
        $sedangIzin = $izinAktif && !$absensiHariIni;

        $statusMasuk = 'belum';
        $statusPulang = 'belum';
        if ($absensiHariIni) {
            $statusMasuk = 'sudah';
            if ($absensiHariIni->jam_pulang) {
                $statusPulang = 'sudah';
            } elseif ($jamSekarang < '16:00:00') {
                $statusPulang = 'belum_dibuka';
            } elseif ($jamSekarang <= '17:00:00') {
                $statusPulang = 'dibuka';
            } else {
                $statusPulang = 'berakhir';
            }
        } else {
            if ($jamSekarang < '06:00:00') {
                $statusMasuk = 'belum_dibuka';
            } elseif ($jamSekarang < '16:00:00') {
                $statusMasuk = 'dibuka';
            } else {
                $statusMasuk = 'berakhir';
            }
            $statusPulang = 'belum_dibuka';
        }
        $bulanIni = $sekarang->copy()->startOfMonth();
        $akhirBulan = $sekarang->copy()->endOfMonth();
        $absensiBulanIni = $pegawai->absensis()
            ->whereBetween('tanggal', [
                $bulanIni->toDateString(),
                $akhirBulan->toDateString()
            ])
            ->get();
        $izinBulanIni = $pegawai->izins()
            ->where('status', 'disetujui')
            ->where('jenis_izin', '!=', 'izin_terlambat')
            ->where(function ($query) use ($bulanIni, $akhirBulan) {
                $query->where(function ($q) use ($bulanIni, $akhirBulan) {
                    $q->whereNotNull('tanggal_mulai')
                        ->whereBetween('tanggal_mulai', [
                            $bulanIni->toDateString(),
                            $akhirBulan->toDateString()
                        ]);
                })
                    ->orWhere(function ($q) use ($bulanIni, $akhirBulan) {
                        $q->whereNotNull('tanggal_selesai')
                            ->whereBetween('tanggal_selesai', [
                                $bulanIni->toDateString(),
                                $akhirBulan->toDateString()
                            ]);
                    })
                    ->orWhere(function ($q) use ($bulanIni, $akhirBulan) {
                        $q->whereNull('tanggal_mulai')
                            ->whereDate('created_at', '>=', $bulanIni->toDateString())
                            ->whereDate('created_at', '<=', $akhirBulan->toDateString());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
        // Hadir = hadir biasa + terlambat
        $hadir = $absensiBulanIni
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();
        // Terlambat = rincian dari jumlah hadir
        $terlambat = $absensiBulanIni
            ->where('status', 'terlambat')
            ->count();
        $tanggalAbsensi = $absensiBulanIni
            ->pluck('status', 'tanggal')
            ->mapWithKeys(function ($status, $tanggal) {
                return [
                    Carbon::parse($tanggal, 'Asia/Makassar')->toDateString() => $status
                ];
            });
        $tanggalIzin = collect();
        foreach ($izinBulanIni as $item) {
            if ($item->tanggal_mulai) {
                $mulai = Carbon::parse($item->tanggal_mulai, 'Asia/Makassar')->startOfDay();
                $selesai = $item->tanggal_selesai
                    ? Carbon::parse($item->tanggal_selesai, 'Asia/Makassar')->startOfDay()
                    : $mulai->copy();
                while ($mulai->lte($selesai)) {
                    if ($mulai->month === $bulanIni->month && $mulai->year === $bulanIni->year) {
                        $tanggalIzin->push($mulai->toDateString());
                    }
                    $mulai->addDay();
                }
            } else {
                $tanggalIzin->push(
                    Carbon::parse($item->created_at, 'Asia/Makassar')->toDateString()
                );
            }
        }
        $tanggalIzin = $tanggalIzin->unique()->values();
        $izin = $tanggalIzin->count();
        $hariKerjaBerjalan = 0;
        $tidakHadir = 0;
        $tanggalMulaiAktif = $pegawai->tanggal_mulai_kerja
            ? Carbon::parse($pegawai->tanggal_mulai_kerja, 'Asia/Makassar')->startOfDay()
            : Carbon::parse($pegawai->created_at, 'Asia/Makassar')->startOfDay();
        $tanggalCek = $bulanIni->copy()->startOfDay();
        $batasTanggal = $sekarang->copy()->startOfDay();
        if ($batasTanggal->gt($akhirBulan)) {
            $batasTanggal = $akhirBulan->copy()->startOfDay();
        }
        while ($tanggalCek->lte($batasTanggal)) {
            if ($tanggalCek->lt($tanggalMulaiAktif)) {
                $tanggalCek->addDay();
                continue;
            }
            if ($tanggalCek->isWeekday()) {
                $hariKerjaBerjalan++;
                $tanggal = $tanggalCek->toDateString();
                $adaAbsensi = $tanggalAbsensi->has($tanggal);
                $adaIzin = $tanggalIzin->contains($tanggal);
                if (!$adaAbsensi && !$adaIzin) {
                    $tidakHadir++;
                }
            }
            $tanggalCek->addDay();
        }
        $alpa = $tidakHadir;
        // Persentase kehadiran hanya menghitung hadir + terlambat
        $persentaseKehadiran = $hariKerjaBerjalan > 0
            ? round(($hadir / $hariKerjaBerjalan) * 100)
            : 0;
        $persentaseKehadiran = min($persentaseKehadiran, 100);
        $riwayatAbsensi = $pegawai->absensis()
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tipe' => 'absensi',
                    'tanggal' => $item->tanggal,
                    'status' => $item->status,
                    'jam_masuk' => $item->jam_masuk,
                    'jam_pulang' => $item->jam_pulang,
                    'jenis_izin' => null,
                    'keterangan' => null,
                    'created_at' => $item->created_at,
                ];
            });
        $riwayatIzin = $pegawai->izins()
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tipe' => 'izin',
                    'tanggal' => $item->tanggal_mulai ?? $item->created_at,
                    'status' => $item->status,
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'jenis_izin' => $item->jenis_izin,
                    'keterangan' => $item->keterangan,
                    'created_at' => $item->created_at,
                ];
            });
        $riwayatTerbaru = $riwayatAbsensi
            ->concat($riwayatIzin)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
        $jumlahNotifikasiBelumDibaca = $user->notifications()
            ->where('dibaca', false)
            ->count();
        $notifikasiTerbaru = $user->notifications()
            ->latest()
            ->take(5)
            ->get();
        return view('dashboard', compact(
            'user',
            'pegawai',
            'absensiHariIni',
            'statusMasuk',
            'statusPulang',
            'jamSekarang',
            'hadir',
            'izin',
            'alpa',
            'terlambat',
            'tidakHadir',
            'hariKerjaBerjalan',
            'persentaseKehadiran',
            'riwayatTerbaru',
            'jumlahNotifikasiBelumDibaca',
            'notifikasiTerbaru',
            'izinAktif',
            'sedangIzin'
        ));
    }
}
