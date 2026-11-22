<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Izin extends Model
{
    use HasFactory;
    protected $table = 'izins';
    protected $fillable = [
        'pegawai_id',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status',
    ];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
