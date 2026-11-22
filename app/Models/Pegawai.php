<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Izin;

class Pegawai extends Model
{
    use HasFactory;
    protected $fillable = [
        'nip',
        'nama',
        'foto',
        'nomor_hp',
        'alamat',
        'status',
        'face_vector',
        'tanggal_mulai_kerja',
    ];
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
    public function izins(): HasMany
    {
        return $this->hasMany(Izin::class);
    }
}
