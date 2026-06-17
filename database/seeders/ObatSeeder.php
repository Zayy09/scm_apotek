<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Models\Transaksi;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Master Data Kategori ──────────────────────────────────
        $katAnalgesik    = Kategori::firstOrCreate(['nama' => 'Analgesik']);
        $katAntibiotik   = Kategori::firstOrCreate(['nama' => 'Antibiotik']);
        $katVitamin      = Kategori::firstOrCreate(['nama' => 'Vitamin']);
        $katAntaasid     = Kategori::firstOrCreate(['nama' => 'Antasid']);
        $katAntihistamin = Kategori::firstOrCreate(['nama' => 'Antihistamin']);
        $katCardio       = Kategori::firstOrCreate(['nama' => 'Kardiovaskular']);
        $katDiabetes     = Kategori::firstOrCreate(['nama' => 'Antidiabetes']);
        $katDermatologi  = Kategori::firstOrCreate(['nama' => 'Dermatologi']);
        $katMata         = Kategori::firstOrCreate(['nama' => 'Tetes Mata']);
        $katHerbal       = Kategori::firstOrCreate(['nama' => 'Herbal']);

        // ─── Master Data Jenis ─────────────────────────────────────
        $jenTablet  = Jenis::firstOrCreate(['nama' => 'Tablet']);
        $jenSirup   = Jenis::firstOrCreate(['nama' => 'Sirup']);
        $jenKapsul  = Jenis::firstOrCreate(['nama' => 'Kapsul']);
        $jenSalep   = Jenis::firstOrCreate(['nama' => 'Salep']);
        $jenTetes   = Jenis::firstOrCreate(['nama' => 'Tetes']);
        $jenInjeksi = Jenis::firstOrCreate(['nama' => 'Injeksi']);

        // ─── Master Data Satuan (dengan stok_min) ─────────────────
        $satStrip = Satuan::firstOrCreate(['nama' => 'Strip'],  ['stok_min' => 10]);
        $satBotol = Satuan::firstOrCreate(['nama' => 'Botol'],  ['stok_min' => 5]);
        $satPcs   = Satuan::firstOrCreate(['nama' => 'Pcs'],    ['stok_min' => 5]);
        $satBox   = Satuan::firstOrCreate(['nama' => 'Box'],    ['stok_min' => 3]);
        $satTube  = Satuan::firstOrCreate(['nama' => 'Tube'],   ['stok_min' => 5]);
        $satAmpul = Satuan::firstOrCreate(['nama' => 'Ampul'],  ['stok_min' => 5]);

        // ─── Supplier ──────────────────────────────────────────────
        $sup1 = Supplier::firstOrCreate(
            ['nama' => 'Kimia Farma Trading'],
            ['no_telepon' => '021-1234567', 'email' => 'kftd@kimiafarma.com', 'alamat' => 'Jakarta, Indonesia']
        );
        $sup2 = Supplier::firstOrCreate(
            ['nama' => 'Bina San Prima'],
            ['no_telepon' => '022-7654321', 'email' => 'contact@binasanprima.com', 'alamat' => 'Bandung, Indonesia']
        );
        $sup3 = Supplier::firstOrCreate(
            ['nama' => 'Anugrah Argon Medica'],
            ['no_telepon' => '031-8765432', 'email' => 'aam@aam.co.id', 'alamat' => 'Surabaya, Indonesia']
        );
        $sup4 = Supplier::firstOrCreate(
            ['nama' => 'Merapi Utama Pharma'],
            ['no_telepon' => '0274-555123', 'email' => 'mup@merapi.com', 'alamat' => 'Yogyakarta, Indonesia']
        );

        // ─── 50 Data Obat ─────────────────────────────────────────
        $obatList = [
            // Analgesik
            ['nama' => 'Paracetamol 500mg',          'kode' => 'OBT-001', 'kat' => $katAnalgesik,    'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 365,  'stok' => 200, 'stok2' => null],
            ['nama' => 'Ibuprofen 400mg',             'kode' => 'OBT-002', 'kat' => $katAnalgesik,    'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 500,  'stok' => 150, 'stok2' => 50],
            ['nama' => 'Asam Mefenamat 500mg',        'kode' => 'OBT-003', 'kat' => $katAnalgesik,    'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 300,  'stok' => 120, 'stok2' => null],
            ['nama' => 'Natrium Diklofenak 50mg',     'kode' => 'OBT-004', 'kat' => $katAnalgesik,    'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 400,  'stok' => 80,  'stok2' => null],
            ['nama' => 'Tramadol 50mg',               'kode' => 'OBT-005', 'kat' => $katAnalgesik,    'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup3, 'hari' => 500,  'stok' => 60,  'stok2' => null],

            // Antibiotik
            ['nama' => 'Amoxicillin 500mg',           'kode' => 'OBT-006', 'kat' => $katAntibiotik,   'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 360,  'stok' => 100, 'stok2' => 50],
            ['nama' => 'Ciprofloxacin 500mg',         'kode' => 'OBT-007', 'kat' => $katAntibiotik,   'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 365,  'stok' => 90,  'stok2' => null],
            ['nama' => 'Eritromisin 500mg',           'kode' => 'OBT-008', 'kat' => $katAntibiotik,   'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 270,  'stok' => 70,  'stok2' => null],
            ['nama' => 'Cefadroxil 500mg',            'kode' => 'OBT-009', 'kat' => $katAntibiotik,   'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 400,  'stok' => 85,  'stok2' => null],
            ['nama' => 'Amoxicillin Sirup 125mg/5ml', 'kode' => 'OBT-010', 'kat' => $katAntibiotik,   'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup3, 'hari' => 25,   'stok' => 30,  'stok2' => null], // near exp

            // Vitamin
            ['nama' => 'Vitamin C 500mg',             'kode' => 'OBT-011', 'kat' => $katVitamin,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 730,  'stok' => 300, 'stok2' => null],
            ['nama' => 'Vitamin B Kompleks',          'kode' => 'OBT-012', 'kat' => $katVitamin,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 600,  'stok' => 200, 'stok2' => null],
            ['nama' => 'Vitamin D3 1000IU',           'kode' => 'OBT-013', 'kat' => $katVitamin,      'jen' => $jenKapsul,  'sat' => $satBox,   'sup' => $sup2, 'hari' => 540,  'stok' => 150, 'stok2' => null],
            ['nama' => 'Multivitamin Anak Sirup',     'kode' => 'OBT-014', 'kat' => $katVitamin,      'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup3, 'hari' => 365,  'stok' => 60,  'stok2' => null],
            ['nama' => 'Zinc 20mg',                   'kode' => 'OBT-015', 'kat' => $katVitamin,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup4, 'hari' => 500,  'stok' => 120, 'stok2' => null],

            // Antasid
            ['nama' => 'Antasida DOEN',               'kode' => 'OBT-016', 'kat' => $katAntaasid,     'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 365,  'stok' => 100, 'stok2' => null],
            ['nama' => 'Omeprazole 20mg',             'kode' => 'OBT-017', 'kat' => $katAntaasid,     'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 400,  'stok' => 80,  'stok2' => null],
            ['nama' => 'Ranitidin 150mg',             'kode' => 'OBT-018', 'kat' => $katAntaasid,     'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup3, 'hari' => 300,  'stok' => 90,  'stok2' => null],
            ['nama' => 'Sucralfate Sirup',            'kode' => 'OBT-019', 'kat' => $katAntaasid,     'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup4, 'hari' => 180,  'stok' => 40,  'stok2' => null],
            ['nama' => 'Domperidone 10mg',            'kode' => 'OBT-020', 'kat' => $katAntaasid,     'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 365,  'stok' => 70,  'stok2' => null],

            // Antihistamin
            ['nama' => 'CTM (Chlorpheniramine)',      'kode' => 'OBT-021', 'kat' => $katAntihistamin,  'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 365,  'stok' => 130, 'stok2' => null],
            ['nama' => 'Loratadine 10mg',             'kode' => 'OBT-022', 'kat' => $katAntihistamin,  'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 400,  'stok' => 100, 'stok2' => null],
            ['nama' => 'Cetirizine 10mg',             'kode' => 'OBT-023', 'kat' => $katAntihistamin,  'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup3, 'hari' => 365,  'stok' => 90,  'stok2' => null],
            ['nama' => 'Diphenhydramine Sirup',       'kode' => 'OBT-024', 'kat' => $katAntihistamin,  'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup3, 'hari' => 20,   'stok' => 20,  'stok2' => null], // near exp
            ['nama' => 'Fexofenadine 120mg',          'kode' => 'OBT-025', 'kat' => $katAntihistamin,  'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup4, 'hari' => 500,  'stok' => 75,  'stok2' => null],

            // Kardiovaskular
            ['nama' => 'Captopril 25mg',              'kode' => 'OBT-026', 'kat' => $katCardio,        'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 365,  'stok' => 110, 'stok2' => null],
            ['nama' => 'Amlodipine 5mg',              'kode' => 'OBT-027', 'kat' => $katCardio,        'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 400,  'stok' => 95,  'stok2' => null],
            ['nama' => 'Atorvastatin 20mg',           'kode' => 'OBT-028', 'kat' => $katCardio,        'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 365,  'stok' => 80,  'stok2' => null],
            ['nama' => 'Bisoprolol 5mg',              'kode' => 'OBT-029', 'kat' => $katCardio,        'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup3, 'hari' => 500,  'stok' => 70,  'stok2' => null],
            ['nama' => 'Furosemide 40mg',             'kode' => 'OBT-030', 'kat' => $katCardio,        'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup4, 'hari' => 300,  'stok' => 60,  'stok2' => null],

            // Antidiabetes
            ['nama' => 'Metformin 500mg',             'kode' => 'OBT-031', 'kat' => $katDiabetes,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 365,  'stok' => 120, 'stok2' => null],
            ['nama' => 'Glibenclamide 5mg',           'kode' => 'OBT-032', 'kat' => $katDiabetes,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup1, 'hari' => 400,  'stok' => 100, 'stok2' => null],
            ['nama' => 'Glimepiride 2mg',             'kode' => 'OBT-033', 'kat' => $katDiabetes,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup2, 'hari' => 365,  'stok' => 85,  'stok2' => null],
            ['nama' => 'Acarbose 50mg',               'kode' => 'OBT-034', 'kat' => $katDiabetes,      'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup3, 'hari' => 300,  'stok' => 65,  'stok2' => null],
            ['nama' => 'Insulin Novorapid 100IU',     'kode' => 'OBT-035', 'kat' => $katDiabetes,      'jen' => $jenInjeksi, 'sat' => $satAmpul, 'sup' => $sup4, 'hari' => 180,  'stok' => 30,  'stok2' => null],

            // Dermatologi
            ['nama' => 'Hidrokortison Krim 1%',       'kode' => 'OBT-036', 'kat' => $katDermatologi,   'jen' => $jenSalep,   'sat' => $satTube,  'sup' => $sup2, 'hari' => 365,  'stok' => 50,  'stok2' => null],
            ['nama' => 'Miconazole Krim 2%',          'kode' => 'OBT-037', 'kat' => $katDermatologi,   'jen' => $jenSalep,   'sat' => $satTube,  'sup' => $sup2, 'hari' => 400,  'stok' => 45,  'stok2' => null],
            ['nama' => 'Betamethasone Krim',          'kode' => 'OBT-038', 'kat' => $katDermatologi,   'jen' => $jenSalep,   'sat' => $satTube,  'sup' => $sup3, 'hari' => 300,  'stok' => 40,  'stok2' => null],
            ['nama' => 'Salicyl Talk',                'kode' => 'OBT-039', 'kat' => $katDermatologi,   'jen' => $jenSalep,   'sat' => $satPcs,   'sup' => $sup4, 'hari' => 730,  'stok' => 60,  'stok2' => null],
            ['nama' => 'Gentamicin Salep 0.1%',       'kode' => 'OBT-040', 'kat' => $katDermatologi,   'jen' => $jenSalep,   'sat' => $satTube,  'sup' => $sup1, 'hari' => 365,  'stok' => 35,  'stok2' => null],

            // Tetes Mata
            ['nama' => 'Chloramphenicol Tetes Mata',  'kode' => 'OBT-041', 'kat' => $katMata,          'jen' => $jenTetes,   'sat' => $satPcs,   'sup' => $sup3, 'hari' => 365,  'stok' => 55,  'stok2' => null],
            ['nama' => 'Cendo Lyteers Tetes Mata',    'kode' => 'OBT-042', 'kat' => $katMata,          'jen' => $jenTetes,   'sat' => $satPcs,   'sup' => $sup3, 'hari' => 400,  'stok' => 40,  'stok2' => null],
            ['nama' => 'Saline Tetes Mata',           'kode' => 'OBT-043', 'kat' => $katMata,          'jen' => $jenTetes,   'sat' => $satPcs,   'sup' => $sup4, 'hari' => 300,  'stok' => 30,  'stok2' => null],

            // Herbal
            ['nama' => 'Tolak Angin Cair',            'kode' => 'OBT-044', 'kat' => $katHerbal,        'jen' => $jenSirup,   'sat' => $satPcs,   'sup' => $sup4, 'hari' => 540,  'stok' => 80,  'stok2' => null],
            ['nama' => 'Antangin JRG',                'kode' => 'OBT-045', 'kat' => $katHerbal,        'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup4, 'hari' => 500,  'stok' => 70,  'stok2' => null],
            ['nama' => 'Kiranti Sehat Datang Bulan',  'kode' => 'OBT-046', 'kat' => $katHerbal,        'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup4, 'hari' => 365,  'stok' => 50,  'stok2' => null],
            ['nama' => 'Enkasari Tablet',             'kode' => 'OBT-047', 'kat' => $katHerbal,        'jen' => $jenTablet,  'sat' => $satStrip, 'sup' => $sup4, 'hari' => 400,  'stok' => 60,  'stok2' => null],
            ['nama' => 'Curcuma Plus Sirup',          'kode' => 'OBT-048', 'kat' => $katHerbal,        'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup4, 'hari' => 365,  'stok' => 45,  'stok2' => null],
            ['nama' => 'Stimuno Sirup',               'kode' => 'OBT-049', 'kat' => $katHerbal,        'jen' => $jenSirup,   'sat' => $satBotol, 'sup' => $sup4, 'hari' => 20,   'stok' => 25,  'stok2' => null], // near exp
            ['nama' => 'Diapet NR Kapsul',            'kode' => 'OBT-050', 'kat' => $katHerbal,        'jen' => $jenKapsul,  'sat' => $satStrip, 'sup' => $sup4, 'hari' => 450,  'stok' => 55,  'stok2' => null],
        ];

        foreach ($obatList as $data) {
            if (Obat::where('kode', $data['kode'])->exists()) continue;

            $tglKadaluarsa = now()->addDays($data['hari'])->format('Y-m-d');

            $obat = Obat::create([
                'nama'           => $data['nama'],
                'kode'           => $data['kode'],
                'kategori_id'    => $data['kat']->id,
                'jenis_id'       => $data['jen']->id,
                'satuan_id'      => $data['sat']->id,
                'supplier_id'    => $data['sup']->id,
                'tgl_kadaluarsa' => $tglKadaluarsa,
            ]);

            // Batch pertama masuk
            Transaksi::create([
                'obat_id'        => $obat->id,
                'jenis'          => 'masuk',
                'jumlah'         => $data['stok'],
                'tanggal'        => now()->subDays(rand(15, 60))->format('Y-m-d'),
                'keterangan'     => 'Stok awal dari ' . $data['sup']->nama,
                'tgl_kadaluarsa' => $tglKadaluarsa,
            ]);

            // Batch kedua jika ada (contoh kadaluarsa berbeda)
            if (!empty($data['stok2'])) {
                $tglKad2 = now()->addDays($data['hari'] + 90)->format('Y-m-d');
                Transaksi::create([
                    'obat_id'        => $obat->id,
                    'jenis'          => 'masuk',
                    'jumlah'         => $data['stok2'],
                    'tanggal'        => now()->subDays(rand(1, 14))->format('Y-m-d'),
                    'keterangan'     => 'Pengadaan batch ke-2 dari ' . $data['sup']->nama,
                    'tgl_kadaluarsa' => $tglKad2,
                ]);
                // Update tgl_kadaluarsa di tabel obat ke batch terbaru
                $obat->tgl_kadaluarsa = $tglKad2;
                $obat->save();
            }
        }

        $this->command->info('✅ 50 data obat, 4 supplier, dan satuan dengan stok_min berhasil ditambahkan!');
    }
}
