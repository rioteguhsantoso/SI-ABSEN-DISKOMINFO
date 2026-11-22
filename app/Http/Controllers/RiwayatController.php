<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $pegawai = $user->pegawai;
        if (!$pegawai) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data pegawai tidak ditemukan.');
        }
        $sekarang = Carbon::now('Asia/Makassar');
        /*
        |--------------------------------------------------------------------------
        | TANGGAL MULAI AKTIF PEGAWAI
        |--------------------------------------------------------------------------
        */
        $tanggalMulaiAktif = $pegawai->tanggal_mulai_kerja
            ? Carbon::parse($pegawai->tanggal_mulai_kerja, 'Asia/Makassar')->startOfDay()
            : Carbon::parse($pegawai->created_at, 'Asia/Makassar')->startOfDay();
        $bulan = $request->query('bulan');
        try {
            $tanggalBulan = $bulan
                ? Carbon::createFromFormat('Y-m', $bulan, 'Asia/Makassar')->startOfMonth()
                : $sekarang->copy()->startOfMonth();
        } catch (\Exception $e) {
            $tanggalBulan = $sekarang->copy()->startOfMonth();
        }
        $awalBulan = $tanggalBulan->copy()->startOfMonth();
        $akhirBulan = $tanggalBulan->copy()->endOfMonth();
        /*
        |--------------------------------------------------------------------------
        | DATA ABSENSI
        |--------------------------------------------------------------------------
        */
        $absensi = $pegawai->absensis()
            ->with(['verifikasis' => function ($query) {
                $query->orderBy('verified_at');
            }])
            ->whereBetween('tanggal', [
                $awalBulan->toDateString(),
                $akhirBulan->toDateString()
            ])
            ->orderBy('tanggal')
            ->orderBy('jam_masuk')
            ->get();
        /*
        |--------------------------------------------------------------------------
        | DATA IZIN
        |--------------------------------------------------------------------------
        */
        $izin = $pegawai->izins()
            ->where(function ($query) use ($awalBulan, $akhirBulan) {
                $query->where(function ($q) use ($awalBulan, $akhirBulan) {
                    $q->whereNotNull('tanggal_mulai')
                        ->whereBetween('tanggal_mulai', [
                            $awalBulan->toDateString(),
                            $akhirBulan->toDateString()
                        ]);
                })
                    ->orWhere(function ($q) use ($awalBulan, $akhirBulan) {
                        $q->whereNotNull('tanggal_selesai')
                            ->whereBetween('tanggal_selesai', [
                                $awalBulan->toDateString(),
                                $akhirBulan->toDateString()
                            ]);
                    })
                    ->orWhere(function ($q) use ($awalBulan, $akhirBulan) {
                        $q->whereNull('tanggal_mulai')
                            ->whereDate('created_at', '>=', $awalBulan->toDateString())
                            ->whereDate('created_at', '<=', $akhirBulan->toDateString());
                    });
            })
            ->orderByDesc('created_at')
            ->get();
        /*
        |--------------------------------------------------------------------------
        | RINGKASAN ABSENSI
        |--------------------------------------------------------------------------
        */
        $hadir = $absensi
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();
        $terlambat = $absensi
            ->where('status', 'terlambat')
            ->count();
        /*
        |--------------------------------------------------------------------------
        | TANGGAL ABSENSI
        |--------------------------------------------------------------------------
        */
        $tanggalAbsensi = $absensi->keyBy(function ($item) {
            return Carbon::parse($item->tanggal, 'Asia/Makassar')->format('Y-m-d');
        });
        /*
        |--------------------------------------------------------------------------
        | TANGGAL IZIN YANG DISETUJUI
        |--------------------------------------------------------------------------
        */
        $izinDisetujui = $izin->where('status', 'disetujui');
        $tanggalIzin = collect();
        foreach ($izinDisetujui as $item) {
            if ($item->tanggal_mulai) {
                $mulai = Carbon::parse($item->tanggal_mulai, 'Asia/Makassar')->startOfDay();
                $selesai = $item->tanggal_selesai
                    ? Carbon::parse($item->tanggal_selesai, 'Asia/Makassar')->startOfDay()
                    : $mulai->copy();
                while ($mulai->lte($selesai)) {
                    if ($mulai->betweenIncluded($awalBulan, $akhirBulan) && $mulai->gte($tanggalMulaiAktif)) {
                        $tanggalIzin->push($mulai->format('Y-m-d'));
                    }
                    $mulai->addDay();
                }
            } else {
                $tanggal = Carbon::parse($item->created_at)
                    ->setTimezone('Asia/Makassar')
                    ->startOfDay();
                if ($tanggal->betweenIncluded($awalBulan, $akhirBulan) && $tanggal->gte($tanggalMulaiAktif)) {
                    $tanggalIzin->push($tanggal->format('Y-m-d'));
                }
            }
        }
        $tanggalIzin = $tanggalIzin->unique()->values();
        $izinCount = $tanggalIzin->count();
        /*
        |--------------------------------------------------------------------------
        | HITUNG TIDAK HADIR
        |--------------------------------------------------------------------------
        */
        $alpa = 0;
        $batasAlpa = $sekarang->copy()->startOfDay();
        if ($akhirBulan->lt($batasAlpa)) {
            $batasAlpa = $akhirBulan->copy()->startOfDay();
        }
        $tanggal = $awalBulan->copy();
        while ($tanggal->lte($batasAlpa)) {
            if ($tanggal->lt($tanggalMulaiAktif)) {
                $tanggal->addDay();
                continue;
            }
            if ($tanggal->isWeekday()) {
                $key = $tanggal->toDateString();
                $adaAbsensi = $tanggalAbsensi->has($key);
                $adaIzin = $tanggalIzin->contains($key);
                if (!$adaAbsensi && !$adaIzin) {
                    $alpa++;
                }
            }
            $tanggal->addDay();
        }
        /*
        |--------------------------------------------------------------------------
        | RIWAYAT ABSENSI
        |--------------------------------------------------------------------------
        | Menggabungkan absensi asli dengan hari kerja yang tidak hadir.
        | Tidak hadir hanya muncul jika:
        | - sudah melewati tanggal tersebut, atau
        | - hari ini sudah lewat pukul 17:00 WITA
        | - merupakan hari kerja
        | - sudah masuk masa aktif pegawai
        | - tidak ada absensi
        | - tidak ada izin yang disetujui
        */
        $riwayatAbsensi = $absensi->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'tanggal' => $item->tanggal,
                'status' => $item->status,
                'jam_masuk' => $item->jam_masuk,
                'jam_pulang' => $item->jam_pulang,
                'keterangan' => null,
                'verifikasis' => $item->verifikasis,
            ];
        })->values();

        $batasRiwayat = $sekarang->copy()->startOfDay();
        if ($akhirBulan->lt($batasRiwayat)) {
            $batasRiwayat = $akhirBulan->copy()->startOfDay();
        }
        $tanggalRiwayat = $awalBulan->copy();
        while ($tanggalRiwayat->lte($batasRiwayat)) {
            if ($tanggalRiwayat->lt($tanggalMulaiAktif)) {
                $tanggalRiwayat->addDay();
                continue;
            }
            if ($tanggalRiwayat->isWeekday()) {
                $key = $tanggalRiwayat->toDateString();
                $adaAbsensi = $tanggalAbsensi->has($key);
                $adaIzin = $tanggalIzin->contains($key);
                $hariIniSelesai = $tanggalRiwayat->isSameDay($sekarang)
                    && $sekarang->format('H:i:s') >= '17:00:00';
                $sudahBisaDinilaiTidakHadir = $tanggalRiwayat->lt($sekarang->copy()->startOfDay())
                    || $hariIniSelesai;
                if (!$adaAbsensi && !$adaIzin && $sudahBisaDinilaiTidakHadir) {
                    $riwayatAbsensi->push((object) [
                        'tanggal' => $tanggalRiwayat->toDateString(),
                        'status' => 'alpa',
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'keterangan' => 'Tidak melakukan presensi pada hari kerja ini.',
                    ]);
                }
            }
            $tanggalRiwayat->addDay();
        }
        $riwayatAbsensi = $riwayatAbsensi
            ->sortByDesc(function ($item) {
                return Carbon::parse($item->tanggal, 'Asia/Makassar')->timestamp;
            })
            ->values();
        /*
        |--------------------------------------------------------------------------
        | KALENDER
        |--------------------------------------------------------------------------
        */
        $kalender = [];
        $hariPertama = $awalBulan->copy()->startOfWeek(Carbon::MONDAY);
        $hariTerakhir = $akhirBulan->copy()->endOfWeek(Carbon::SUNDAY);
        for ($tanggal = $hariPertama->copy(); $tanggal->lte($hariTerakhir); $tanggal->addDay()) {
            $key = $tanggal->format('Y-m-d');
            $absenHariIni = $tanggalAbsensi->get($key);
            $izinHariIni = $tanggalIzin->contains($key);
            $status = $absenHariIni?->status;
            if ($izinHariIni) {
                $status = 'izin';
            }
            /*
            |--------------------------------------------------------------------------
            | TIDAK HADIR DI KALENDER
            |--------------------------------------------------------------------------
            */
            $isHariKerja = $tanggal->isWeekday();
            $isHariTerlewat = $tanggal->lt($sekarang->copy()->startOfDay());
            $isHariIniSelesai = $tanggal->isSameDay($sekarang)
                && $sekarang->format('H:i:s') >= '17:00:00';
            $isSetelahMulaiAktif = $tanggal->gte($tanggalMulaiAktif);
            $isTidakHadir = $tanggal->month === $tanggalBulan->month
                && $tanggal->year === $tanggalBulan->year
                && $isSetelahMulaiAktif
                && $isHariKerja
                && ($isHariTerlewat || $isHariIniSelesai)
                && !$status;
            if ($isTidakHadir) {
                $status = 'alpa';
            }
            $kalender[] = [
                'tanggal' => $tanggal->copy(),
                'absensi' => $absenHariIni,
                'izin' => $izinHariIni,
                'status' => $status,
                'bulanAktif' => $tanggal->month === $tanggalBulan->month
                    && $tanggal->year === $tanggalBulan->year,
                'sebelumMulaiAktif' => $tanggal->lt($tanggalMulaiAktif),
            ];
        }
        $bulanSebelumnya = $tanggalBulan->copy()->subMonth()->format('Y-m');
        $bulanBerikutnya = $tanggalBulan->copy()->addMonth()->format('Y-m');
        return view('riwayat', compact(
            'user',
            'pegawai',
            'absensi',
            'riwayatAbsensi',
            'izin',
            'hadir',
            'alpa',
            'terlambat',
            'izinCount',
            'tanggalBulan',
            'kalender',
            'bulanSebelumnya',
            'bulanBerikutnya',
            'sekarang',
            'tanggalMulaiAktif'
        ));
    }
}
