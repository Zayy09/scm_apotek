<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Obat;
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
        // Buat User default
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Buat Master Data Kategori
        $kat1 = \App\Models\Kategori::create(['nama' => 'Analgesik']);
        $kat2 = \App\Models\Kategori::create(['nama' => 'Antibiotik']);
        $kat3 = \App\Models\Kategori::create(['nama' => 'Vitamin']);

        // Buat Master Data Jenis
        $jen1 = \App\Models\Jenis::create(['nama' => 'Tablet']);
        $jen2 = \App\Models\Jenis::create(['nama' => 'Sirup']);
        $jen3 = \App\Models\Jenis::create(['nama' => 'Kapsul']);

        // Buat Master Data Satuan
        $sat1 = \App\Models\Satuan::create(['nama' => 'Strip']);
        $sat2 = \App\Models\Satuan::create(['nama' => 'Botol']);
        $sat3 = \App\Models\Satuan::create(['nama' => 'Pcs']);

        // Buat Supplier
        $sup1 = \App\Models\Supplier::create([
            'nama' => 'Kimia Farma Trading',
            'no_telepon' => '021-1234567',
            'email' => 'kftd@kimiafarma.com',
            'alamat' => 'Jakarta, Indonesia'
        ]);
        $sup2 = \App\Models\Supplier::create([
            'nama' => 'Bina San Prima',
            'no_telepon' => '021-7654321',
            'email' => 'contact@binasanprima.com',
            'alamat' => 'Bandung, Indonesia'
        ]);

        // Buat Data Obat
        $obt1 = Obat::create([
            'nama' => 'Paracetamol 500mg',
            'kode' => 'OBT-001',
            'kategori_id' => $kat1->id,
            'jenis_id' => $jen1->id,
            'satuan_id' => $sat1->id,
            'stok' => 100,
            'tgl_kadaluarsa' => now()->addDays(120)->format('Y-m-d')
        ]);

        $obt2 = Obat::create([
            'nama' => 'Amoxicillin 500mg',
            'kode' => 'OBT-002',
            'kategori_id' => $kat2->id,
            'jenis_id' => $jen3->id,
            'satuan_id' => $sat1->id,
            'stok' => 50,
            'tgl_kadaluarsa' => now()->addDays(200)->format('Y-m-d')
        ]);

        $obt3 = Obat::create([
            'nama' => 'Sanadryl DMP Sirup',
            'kode' => 'OBT-003',
            'kategori_id' => $kat1->id,
            'jenis_id' => $jen2->id,
            'satuan_id' => $sat2->id,
            'stok' => 20,
            'tgl_kadaluarsa' => now()->addDays(15)->format('Y-m-d') // Dekat kadaluarsa (<90 hari)
        ]);

        // Buat Transaksi Awal
        \App\Models\Transaksi::create([
            'obat_id' => $obt1->id,
            'jenis' => 'masuk',
            'jumlah' => 100,
            'tanggal' => now()->subDays(2)->format('Y-m-d'),
            'keterangan' => 'Stok awal dari supplier ' . $sup1->nama
        ]);

        \App\Models\Transaksi::create([
            'obat_id' => $obt2->id,
            'jenis' => 'masuk',
            'jumlah' => 50,
            'tanggal' => now()->subDays(1)->format('Y-m-d'),
            'keterangan' => 'Pengadaan dari supplier ' . $sup2->nama
        ]);

        \App\Models\Transaksi::create([
            'obat_id' => $obt1->id,
            'jenis' => 'keluar',
            'jumlah' => 10,
            'tanggal' => now()->format('Y-m-d'),
            'keterangan' => 'Resep dokter'
        ]);
        
        // Sesuaikan stok obat setelah keluar
        $obt1->stok -= 10;
        $obt1->save();
    }
}
