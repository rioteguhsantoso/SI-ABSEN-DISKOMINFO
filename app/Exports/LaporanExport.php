<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;
    protected $jenis;
    protected $bulan;

    public function __construct($data, string $jenis, string $bulan)
    {
        $this->data = $data;
        $this->jenis = $jenis;
        $this->bulan = $bulan;
    }

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return match ($this->jenis) {
            'hadir' => [
                'Tanggal',
                'NIP',
                'Nama Pegawai',
                'Jam Masuk',
                'Jam Pulang',
                'Status',
                'Face Recognition Masuk',
                'Liveness Masuk',
                'Lokasi Masuk',
                'Face Recognition Pulang',
                'Liveness Pulang',
                'Lokasi Pulang',
            ],
            'dinas', 'cuti' => [
                'NIP',
                'Nama Pegawai',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Durasi (Hari)',
                'Keterangan',
                'Status',
            ],
            'terlambat' => [
                'NIP',
                'Nama Pegawai',
                'Tanggal',
                'Keterangan',
                'Status',
            ],
            'pengguna' => [
                'NIP',
                'Nama Pegawai',
                'Nomor HP',
                'Status Pegawai',
                'Email',
                'Status Akun',
                'Pendaftaran Wajah',
            ],
            default => [],
        };
    }

    public function map($item): array
    {
        if ($this->jenis === 'hadir') {
            $masuk = $item->verifikasis->firstWhere('tipe_absensi', 'masuk');
            $pulang = $item->verifikasis->firstWhere('tipe_absensi', 'pulang');

            return [
                $item->tanggal,
                $item->pegawai->nip ?? '-',
                $item->pegawai->nama ?? '-',
                $item->jam_masuk ?? '-',
                $item->jam_pulang ?? '-',
                $item->status ?? '-',
                $masuk->face_match_status ?? '-',
                $masuk->liveness_status ?? '-',
                $masuk->location_status ?? '-',
                $pulang->face_match_status ?? '-',
                $pulang->liveness_status ?? '-',
                $pulang->location_status ?? '-',
            ];
        }

        if (in_array($this->jenis, ['dinas', 'cuti'])) {
            $mulai = Carbon::parse($item->tanggal_mulai);
            $selesai = Carbon::parse($item->tanggal_selesai);

            return [
                $item->pegawai->nip ?? '-',
                $item->pegawai->nama ?? '-',
                $mulai->format('d-m-Y'),
                $selesai->format('d-m-Y'),
                $mulai->diffInDays($selesai) + 1,
                $item->keterangan ?? '-',
                $item->status ?? '-',
            ];
        }

        if ($this->jenis === 'terlambat') {
            return [
                $item->pegawai->nip ?? '-',
                $item->pegawai->nama ?? '-',
                $item->tanggal_mulai
                    ? Carbon::parse($item->tanggal_mulai)->format('d-m-Y')
                    : '-',
                $item->keterangan ?? '-',
                $item->status ?? '-',
            ];
        }

        if ($this->jenis === 'pengguna') {
            return [
                $item->nip ?? '-',
                $item->nama ?? '-',
                $item->nomor_hp ?? '-',
                $item->status ?? '-',
                $item->user->email ?? '-',
                $item->user ? 'Aktif' : 'Belum Memiliki Akun',
                $item->face_vector ? 'Terdaftar' : 'Belum Terdaftar',
            ];
        }

        return [];
    }
}
