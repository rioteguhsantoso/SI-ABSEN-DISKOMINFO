<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Pegawai;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menentukan periode laporan.
     */
    private function periode(Request $request): string
    {
        $bulan = $request->input('bulan');

        if (!$bulan || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
            return Carbon::now('Asia/Makassar')->format('Y-m');
        }

        return $bulan;
    }

    /**
     * Mengambil data berdasarkan jenis laporan.
     */
    private function dataLaporan(string $jenis, string $bulan)
    {
        $awalBulan = Carbon::createFromFormat(
            '!Y-m',
            $bulan,
            'Asia/Makassar'
        )->startOfMonth();

        $akhirBulan = $awalBulan->copy()->endOfMonth();

        switch ($jenis) {
            case 'hadir':
                return Absensi::with(['pegawai', 'verifikasis'])
                    ->whereBetween('tanggal', [
                        $awalBulan->toDateString(),
                        $akhirBulan->toDateString()
                    ])
                    ->orderByDesc('tanggal')
                    ->orderByDesc('jam_masuk')
                    ->get();

            case 'dinas':
            case 'cuti':
                return Izin::with('pegawai')
                    ->where('jenis_izin', $jenis === 'dinas'
                        ? 'keperluan_dinas'
                        : 'cuti')
                    ->whereNotNull('tanggal_mulai')
                    ->whereDate(
                        'tanggal_mulai',
                        '<=',
                        $akhirBulan->toDateString()
                    )
                    ->where(function ($query) use ($awalBulan) {
                        $query->whereNull('tanggal_selesai')
                            ->orWhereDate(
                                'tanggal_selesai',
                                '>=',
                                $awalBulan->toDateString()
                            );
                    })
                    ->orderByDesc('tanggal_mulai')
                    ->get();

            case 'terlambat':
                return Izin::with('pegawai')
                    ->where('jenis_izin', 'izin_terlambat')
                    ->whereBetween('tanggal_mulai', [
                        $awalBulan->toDateString(),
                        $akhirBulan->toDateString()
                    ])
                    ->orderByDesc('tanggal_mulai')
                    ->get();

            case 'pengguna':
                return Pegawai::with('user')
                    ->orderBy('nama')
                    ->get();

            default:
                return collect();
        }
    }

    /**
     * Halaman utama laporan dan rekap.
     */
    public function index(Request $request)
    {
        $bulan = $this->periode($request);

        $jenis = $request->input('jenis', 'hadir');

        $daftarJenis = [
            'hadir',
            'dinas',
            'terlambat',
            'cuti',
            'pengguna'
        ];

        if (!in_array($jenis, $daftarJenis, true)) {
            $jenis = 'hadir';
        }

        $data = $this->dataLaporan($jenis, $bulan);

        $jumlah = [
            'hadir' => $this->dataLaporan('hadir', $bulan)->count(),
            'dinas' => $this->dataLaporan('dinas', $bulan)->count(),
            'terlambat' => $this->dataLaporan('terlambat', $bulan)->count(),
            'cuti' => $this->dataLaporan('cuti', $bulan)->count(),
            'pengguna' => Pegawai::count(),
        ];

        return view('Admin.laporan.index', compact(
            'bulan',
            'jenis',
            'data',
            'jumlah'
        ));
    }

    /**
     * Mengunduh laporan dalam format Excel.
     */
    public function exportExcel(Request $request)
    {
        $bulan = $this->periode($request);
        $jenis = $request->input('jenis', 'hadir');

        $daftarJenis = [
            'hadir',
            'dinas',
            'terlambat',
            'cuti',
            'pengguna'
        ];

        if (!in_array($jenis, $daftarJenis, true)) {
            abort(404, 'Jenis laporan tidak tersedia.');
        }

        $data = $this->dataLaporan($jenis, $bulan);

        $namaFile = "laporan-{$jenis}-{$bulan}.xlsx";

        return Excel::download(
            new LaporanExport($data, $jenis, $bulan),
            $namaFile
        );
    }

    /**
     * Mengunduh laporan dalam format PDF.
     */
    public function exportPdf(Request $request)
    {
        $bulan = $this->periode($request);
        $jenis = $request->input('jenis', 'hadir');

        $daftarJenis = [
            'hadir',
            'dinas',
            'terlambat',
            'cuti',
            'pengguna'
        ];

        if (!in_array($jenis, $daftarJenis, true)) {
            abort(404, 'Jenis laporan tidak tersedia.');
        }

        $data = $this->dataLaporan($jenis, $bulan);

        $pdf = Pdf::loadView('Admin.laporan.pdf', [
            'data' => $data,
            'jenis' => $jenis,
            'bulan' => $bulan,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            "laporan-{$jenis}-{$bulan}.pdf"
        );
    }
}
