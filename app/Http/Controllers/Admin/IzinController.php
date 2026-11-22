<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $jenisIzin = $request->input('jenis_izin');
        $pegawaiId = $request->input('pegawai_id');
        $query = Izin::with('pegawai');
        if ($status) {
            $query->where('status', $status);
        }
        if ($jenisIzin) {
            $query->where('jenis_izin', $jenisIzin);
        }
        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }
        $izin = $query->orderByDesc('created_at')->get();
        $menunggu = $izin->where('status', 'menunggu')->count();
        $disetujui = $izin->where('status', 'disetujui')->count();
        $ditolak = $izin->where('status', 'ditolak')->count();
        $pegawai = Pegawai::orderBy('nama')->get();
        return view('Admin.izin.index', compact(
            'izin',
            'pegawai',
            'status',
            'jenisIzin',
            'pegawaiId',
            'menunggu',
            'disetujui',
            'ditolak'
        ));
    }
    public function updateStatus(Request $request, Izin $izin)
    {
        $request->validate([
            'status' => ['required', 'in:menunggu,disetujui,ditolak'],
        ]);
        $izin->update([
            'status' => $request->status,
        ]);
        return redirect()
            ->route('admin.izin.index')
            ->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }
}
