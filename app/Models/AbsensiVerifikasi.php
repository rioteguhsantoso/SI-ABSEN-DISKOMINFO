<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiVerifikasi extends Model
{
    protected $table = 'absensi_verifikasis';

    protected $fillable = [
        'absensi_id',
        'tipe_absensi',
        'latitude',
        'longitude',
        'akurasi_gps',
        'jarak_meter',
        'office_latitude',
        'office_longitude',
        'face_match_status',
        'face_match_distance',
        'liveness_status',
        'location_status',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'akurasi_gps' => 'decimal:2',
            'jarak_meter' => 'decimal:2',
            'office_latitude' => 'decimal:7',
            'office_longitude' => 'decimal:7',
            'face_match_distance' => 'decimal:5',
            'verified_at' => 'datetime',
        ];
    }

    public function absensi(): BelongsTo
    {
        return $this->belongsTo(Absensi::class);
    }
}
