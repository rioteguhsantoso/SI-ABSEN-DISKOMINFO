
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_verifikasis', function (Blueprint $table) {
            $table->id();

            // Relasi ke data absensi utama
            $table->foreignId('absensi_id')
                ->constrained('absensis')
                ->cascadeOnDelete();

            // Jenis absensi: masuk atau pulang
            $table->string('tipe_absensi', 20);

            // Data lokasi pegawai saat absensi
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('akurasi_gps', 8, 2);
            $table->decimal('jarak_meter', 10, 2);

            // Snapshot koordinat kantor saat validasi
            $table->decimal('office_latitude', 10, 7);
            $table->decimal('office_longitude', 10, 7);

            // Hasil Face Recognition
            $table->string('face_match_status', 20);
            $table->decimal('face_match_distance', 8, 5);

            // Hasil Liveness Detection
            $table->string('liveness_status', 20);

            // Hasil validasi lokasi
            $table->string('location_status', 20);

            // Waktu seluruh verifikasi dinyatakan berhasil
            $table->dateTime('verified_at');

            $table->timestamps();

            // Satu absensi hanya boleh memiliki satu detail
            // untuk masing-masing jenis (masuk/pulang)
            $table->unique(
                ['absensi_id', 'tipe_absensi'],
                'absensi_verifikasi_unique'
            );

            // Memudahkan pencarian berdasarkan waktu verifikasi
            $table->index('verified_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_verifikasis');
    }
};
