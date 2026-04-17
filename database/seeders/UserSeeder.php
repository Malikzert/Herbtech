<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin SIP',
            'email' => 'admin@sipjamu.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Operator Produksi',
            'email' => 'operator@sipjamu.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);
    }
}
