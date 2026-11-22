<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->enum('jenis_izin', [
                'sakit',
                'keperluan_keluarga',
                'keperluan_dinas',
                'keperluan_pribadi',
                'izin_terlambat'
            ]);

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('keterangan');
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('izins');
    }
};
