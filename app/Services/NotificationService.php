<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    public function generateForUser(User $user): void
    {
        $sekarang = Carbon::now('Asia/Makassar');
        if ($sekarang->isWeekend()) {
            $this->buat(
                $user,
                'Hari Libur',
                'Hari ini adalah hari libur. Absensi tidak tersedia.',
                'schedule',
                $sekarang->toDateString() . '-libur'
            );
            return;
        }
        $tanggal = $sekarang->toDateString();
        $jam = $sekarang->format('H:i:s');
        if ($jam >= '06:00:00') {
            $this->buat(
                $user,
                'Absen Pagi Dibuka',
                'Waktu absensi pagi telah dibuka. Silakan melakukan absensi sampai pukul 16:00 WITA.',
                'schedule',
                $tanggal . '-absen-pagi-dibuka'
            );
        }
        if ($jam >= '07:00:00') {
            $this->buat(
                $user,
                'Waktu Absensi Normal',
                'Waktu absensi normal berlangsung sampai pukul 08:00 WITA.',
                'schedule',
                $tanggal . '-absen-normal-dimulai'
            );
        }
        if ($jam >= '08:00:00') {
            $this->buat(
                $user,
                'Waktu Normal Berakhir',
                'Waktu absensi normal telah berakhir. Anda masih dapat melakukan absensi sampai pukul 16:00 WITA, tetapi akan tercatat sebagai terlambat.',
                'schedule',
                $tanggal . '-absen-normal-berakhir'
            );
        }
        if ($jam >= '16:00:00') {
            $this->buat(
                $user,
                'Absen Pagi Ditutup',
                'Waktu absensi pagi telah ditutup. Selanjutnya waktu absensi pulang telah dibuka.',
                'schedule',
                $tanggal . '-absen-pagi-ditutup'
            );
            $this->buat(
                $user,
                'Absen Sore Dibuka',
                'Waktu absensi pulang telah dibuka. Silakan melakukan absensi sampai pukul 17:00 WITA.',
                'schedule',
                $tanggal . '-absen-sore-dibuka'
            );
        }
        if ($jam > '17:00:00') {
            $this->buat(
                $user,
                'Absen Sore Ditutup',
                'Waktu absensi sore hari ini telah berakhir.',
                'schedule',
                $tanggal . '-absen-sore-ditutup'
            );
        }
    }
    private function buat(
        User $user,
        string $judul,
        string $pesan,
        string $tipe,
        string $referenceKey
    ): void {
        $user->notifications()->firstOrCreate(
            [
                'reference_key' => $referenceKey,
            ],
            [
                'judul' => $judul,
                'pesan' => $pesan,
                'tipe' => $tipe,
                'dibaca' => false,
            ]
        );
    }
}
