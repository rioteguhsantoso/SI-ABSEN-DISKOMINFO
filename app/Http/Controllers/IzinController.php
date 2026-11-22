<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IzinController extends Controller
{
    public function index()
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
        $izins = $pegawai->izins()
            ->orderByDesc('created_at')
            ->get();
        return view('izin', compact('user', 'pegawai', 'izins'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            Auth::logout();

            return redirect()->route('login')
                ->with('error', 'Data pegawai tidak ditemukan.');
        }

        $jenisIzin = $request->input('jenis_izin');

        $request->validate([
            'jenis_izin' => [
                'required',
                'in:sakit,keperluan_keluarga,keperluan_dinas,keperluan_pribadi,cuti,izin_terlambat'
            ],
            'keterangan' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ]);

        $tanggalHariIni = Carbon::now('Asia/Makassar')->toDateString();

        // Perjalanan dinas dan cuti membutuhkan periode tanggal.
        if (in_array($jenisIzin, ['keperluan_dinas', 'cuti'])) {

            $request->validate([
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_selesai' => [
                    'required',
                    'date',
                    'after_or_equal:tanggal_mulai'
                ],
            ]);

            $tanggalMulai = $request->tanggal_mulai;
            $tanggalSelesai = $request->tanggal_selesai;
        } else {
            $tanggalMulai = $tanggalHariIni;
            $tanggalSelesai = $tanggalHariIni;
        }

        $pegawai->izins()->create([
            'jenis_izin' => $jenisIzin,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('izin')
            ->with('success', 'Pengajuan izin berhasil dikirim dan sedang menunggu persetujuan.');
    }
}
