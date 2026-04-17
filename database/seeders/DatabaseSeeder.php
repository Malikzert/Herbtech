<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
        ]);

        // Seed some initial materials
        \App\Models\RawMaterial::create(['name' => 'Jahe Merah', 'type' => 'herbal', 'unit' => 'Kg', 'current_stock' => 50.0]);
        \App\Models\RawMaterial::create(['name' => 'Kunyit', 'type' => 'herbal', 'unit' => 'Kg', 'current_stock' => 30.5]);
        \App\Models\RawMaterial::create(['name' => 'Botol Kaca 250ml', 'type' => 'packaging', 'unit' => 'Pcs', 'current_stock' => 1000]);

        // Seed product
        \App\Models\Product::create(['sku_code' => 'JHM-250', 'name' => 'Jamu Jahe Merah Super', 'description' => 'Jamu penghangat badan']);

    }
}
