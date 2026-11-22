<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pegawais', 'tanggal_mulai_kerja')) {
            Schema::table('pegawais', function (Blueprint $table) {
                $table->date('tanggal_mulai_kerja')->nullable()->after('status');
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasColumn('pegawais', 'tanggal_mulai_kerja')) {
            Schema::table('pegawais', function (Blueprint $table) {
                $table->dropColumn('tanggal_mulai_kerja');
            });
        }
    }
};