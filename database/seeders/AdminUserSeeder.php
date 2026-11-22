<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'pegawai_id' => null,
                'role' => 'admin',
                'password' => Hash::make($password),
            ]
        );
    }
}
