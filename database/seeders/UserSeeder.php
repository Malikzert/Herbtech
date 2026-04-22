<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@sipjamu.com'],
            [
                'name' => 'Admin SIP',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Operator Produksi
        User::updateOrCreate(
            ['email' => 'operator@sipjamu.com'],
            [
                'name' => 'Operator Produksi',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun Spesifik Dwi Rizky
        User::updateOrCreate(
            ['email' => 'dwirsk6@gmail.com'],
            [
                'name' => 'Dwi Rizky',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}