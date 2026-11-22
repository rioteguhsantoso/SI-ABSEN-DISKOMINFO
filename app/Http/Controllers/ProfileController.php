<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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
        $zonaWaktu = 'Asia/Makassar';
        $sekarang = Carbon::now($zonaWaktu);
        $tanggalMulaiAktif = $pegawai->tanggal_mulai_kerja
            ? Carbon::parse($pegawai->tanggal_mulai_kerja, $zonaWaktu)->startOfDay()
            : Carbon::parse($pegawai->created_at, $zonaWaktu)->startOfDay();
        $bulan = $request->query('bulan');
        $tanggalBulan = $sekarang->copy()->startOfMonth();
        if (is_string($bulan) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
            try {
                $pilihanBulan = Carbon::createFromFormat('!Y-m', $bulan, $zonaWaktu);
                if ($pilihanBulan && $pilihanBulan->format('Y-m') === $bulan) {
                    $tanggalBulan = $pilihanBulan->startOfMonth();
                }
            } catch (\Throwable $e) {
                $tanggalBulan = $sekarang->copy()->startOfMonth();
            }
        }
        $awalBulan = $tanggalBulan->copy()->startOfMonth();
        $akhirBulan = $tanggalBulan->copy()->endOfMonth();
        $absensi = $pegawai->absensis()
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('jam_masuk')
            ->get();
        $hadir = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat = $absensi->where('status', 'terlambat')->count();
        $tanggalAbsensi = $absensi->keyBy(function ($item) use ($zonaWaktu) {
            return Carbon::parse($item->tanggal, $zonaWaktu)->toDateString();
        });
        $izinData = $pegawai->izins()
            ->where('status', 'disetujui')
            ->where(function ($query) use ($awalBulan, $akhirBulan) {
                $query->where(function ($q) use ($awalBulan, $akhirBulan) {
                    $q->whereNotNull('tanggal_mulai')
                        ->whereDate('tanggal_mulai', '<=', $akhirBulan->toDateString())
                        ->where(function ($sub) use ($awalBulan) {
                            $sub->whereNull('tanggal_selesai')
                                ->orWhereDate('tanggal_selesai', '>=', $awalBulan->toDateString());
                        });
                })->orWhere(function ($q) use ($awalBulan, $akhirBulan) {
                    $q->whereNull('tanggal_mulai')
                        ->whereDate('created_at', '>=', $awalBulan->toDateString())
                        ->whereDate('created_at', '<=', $akhirBulan->toDateString());
                });
            })
            ->orderByDesc('created_at')
            ->get();
        $tanggalIzin = collect();
        foreach ($izinData as $item) {
            if ($item->tanggal_mulai) {
                $mulai = Carbon::parse($item->tanggal_mulai, $zonaWaktu)->startOfDay();
                $selesai = $item->tanggal_selesai
                    ? Carbon::parse($item->tanggal_selesai, $zonaWaktu)->startOfDay()
                    : $mulai->copy();
                if ($mulai->lt($awalBulan)) {
                    $mulai = $awalBulan->copy();
                }
                if ($selesai->gt($akhirBulan)) {
                    $selesai = $akhirBulan->copy();
                }
                while ($mulai->lte($selesai)) {
                    if ($mulai->gte($tanggalMulaiAktif)) {
                        $tanggalIzin->push($mulai->toDateString());
                    }
                    $mulai->addDay();
                }
            } else {
                $tanggal = Carbon::parse($item->created_at, $zonaWaktu)->startOfDay();
                if ($tanggal->betweenIncluded($awalBulan, $akhirBulan) && $tanggal->gte($tanggalMulaiAktif)) {
                    $tanggalIzin->push($tanggal->toDateString());
                }
            }
        }
        $tanggalIzin = $tanggalIzin->unique()->values();
        $izin = $tanggalIzin->count();
        $hariLibur = collect(config('absensi.hari_libur', []))
            ->filter(function ($tanggal) {
                if (!is_string($tanggal) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
                    return false;
                }
                [$tahun, $bulan, $hari] = array_map('intval', explode('-', $tanggal));
                return checkdate($bulan, $hari, $tahun);
            })
            ->unique()
            ->values();
        $batasAlpa = $akhirBulan->copy()->startOfDay();
        $hariIni = $sekarang->copy()->startOfDay();
        if ($batasAlpa->gt($hariIni)) {
            $batasAlpa = $hariIni;
        }
        if ($awalBulan->gt($batasAlpa)) {
            $batasAlpa = $awalBulan->copy()->subDay();
        }
        $alpa = 0;
        $tanggal = $awalBulan->copy()->startOfDay();
        while ($tanggal->lte($batasAlpa)) {
            if ($tanggal->lt($tanggalMulaiAktif)) {
                $tanggal->addDay();
                continue;
            }
            if ($tanggal->isWeekday()) {
                $key = $tanggal->toDateString();
                if (!$tanggalAbsensi->has($key) && !$tanggalIzin->contains($key) && !$hariLibur->contains($key)) {
                    $alpa++;
                }
            }
            $tanggal->addDay();
        }
        $wajahTerdaftar = !empty($pegawai->face_vector);
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
        $bulanSekarang = $tanggalBulan->month;
        $tahunSekarang = $tanggalBulan->year;
        $tahunPilihan = range($sekarang->year - 5, $sekarang->year + 1);
        return view('profil', compact(
            'user',
            'pegawai',
            'hadir',
            'terlambat',
            'izin',
            'alpa',
            'wajahTerdaftar',
            'bulanPilihan',
            'bulanSekarang',
            'tahunSekarang',
            'tanggalBulan',
            'tahunPilihan'
        ));
    }
    public function edit()
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
        return view('profil_edit', compact('user', 'pegawai'));
    }
    public function update(Request $request)
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
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.max' => 'Nama terlalu panjang.',
            'nomor_hp.max' => 'Nomor HP terlalu panjang.',
            'alamat.max' => 'Alamat terlalu panjang.',
            'foto.image' => 'File yang dipilih harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
        if ($request->hasFile('foto')) {
            $fotoLama = $pegawai->foto;
            $fotoBaru = $request->file('foto')->store('profile', 'public');
            $validated['foto'] = $fotoBaru;
            $pegawai->update($validated);
            if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
                Storage::disk('public')->delete($fotoLama);
            }
        } else {
            $pegawai->update($validated);
        }
        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
