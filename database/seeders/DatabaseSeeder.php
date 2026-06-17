<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Obat;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat User default
        User::firstOrCreate(
            ['email' => 'admin@apotek.com'],
            [
                'name'     => 'Admin Apotek',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // Jalankan ObatSeeder (berisi 50 obat realistis + supplier + satuan dengan stok_min)
        $this->call(ObatSeeder::class);
    }
}
