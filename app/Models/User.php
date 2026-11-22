<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'pegawai_id',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }
}
