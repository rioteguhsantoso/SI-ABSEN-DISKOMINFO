<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('nama');
            $table->string('nomor_hp', 20)->nullable()->after('foto');
            $table->text('alamat')->nullable()->after('nomor_hp');
        });
    }
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn(['foto', 'nomor_hp', 'alamat']);
        });
    }
};