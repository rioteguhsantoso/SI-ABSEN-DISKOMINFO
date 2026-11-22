
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `izins`
            MODIFY `jenis_izin`
            ENUM(
                'sakit',
                'keperluan_keluarga',
                'keperluan_dinas',
                'keperluan_pribadi',
                'izin_terlambat',
                'cuti'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('izins')->where('jenis_izin', 'cuti')->exists()) {
            throw new \RuntimeException(
                'Migrasi tidak dapat dibatalkan karena masih terdapat data izin kategori cuti.'
            );
        }

        DB::statement("
            ALTER TABLE `izins`
            MODIFY `jenis_izin`
            ENUM(
                'sakit',
                'keperluan_keluarga',
                'keperluan_dinas',
                'keperluan_pribadi',
                'izin_terlambat'
            ) NOT NULL
        ");
    }
};
