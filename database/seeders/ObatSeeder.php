<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Satuan;
use App\Models\Transaksi;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil atau buat master data
        $katAnalgesik   = Kategori::firstOrCreate(['nama' => 'Analgesik']);
        $katAntibiotik  = Kategori::firstOrCreate(['nama' => 'Antibiotik']);
        $katVitamin     = Kategori::firstOrCreate(['nama' => 'Vitamin']);
        $katAntaasid    = Kategori::firstOrCreate(['nama' => 'Antasid']);
        $katAntihistamin= Kategori::firstOrCreate(['nama' => 'Antihistamin']);
        $katCardio      = Kategori::firstOrCreate(['nama' => 'Kardiovaskular']);
        $katDiabetes    = Kategori::firstOrCreate(['nama' => 'Antidiabetes']);
        $katDermatologi = Kategori::firstOrCreate(['nama' => 'Dermatologi']);
        $katMata        = Kategori::firstOrCreate(['nama' => 'Tetes Mata']);
        $katHerbal      = Kategori::firstOrCreate(['nama' => 'Herbal']);

        $jenTablet  = Jenis::firstOrCreate(['nama' => 'Tablet']);
        $jenSirup   = Jenis::firstOrCreate(['nama' => 'Sirup']);
        $jenKapsul  = Jenis::firstOrCreate(['nama' => 'Kapsul']);
        $jenSalep   = Jenis::firstOrCreate(['nama' => 'Salep']);
        $jenTetes   = Jenis::firstOrCreate(['nama' => 'Tetes']);
        $jenInjeksi = Jenis::firstOrCreate(['nama' => 'Injeksi']);

        $satStrip   = Satuan::firstOrCreate(['nama' => 'Strip']);
        $satBotol   = Satuan::firstOrCreate(['nama' => 'Botol']);
        $satPcs     = Satuan::firstOrCreate(['nama' => 'Pcs']);
        $satBox     = Satuan::firstOrCreate(['nama' => 'Box']);
        $satTube    = Satuan::firstOrCreate(['nama' => 'Tube']);
        $satAmpul   = Satuan::firstOrCreate(['nama' => 'Ampul']);

        // 50 Data Obat
        $obatList = [
            // ─── Analgesik ───────────────────────────────────────────────
            ['nama' => 'Paracetamol 500mg',         'kode' => 'OBT-001', 'kategori' => $katAnalgesik,    'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 180, 'stok' => 200],
            ['nama' => 'Ibuprofen 400mg',            'kode' => 'OBT-002', 'kategori' => $katAnalgesik,    'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 150],
            ['nama' => 'Asam Mefenamat 500mg',       'kode' => 'OBT-003', 'kategori' => $katAnalgesik,    'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 300, 'stok' => 120],
            ['nama' => 'Natrium Diklofenak 50mg',    'kode' => 'OBT-004', 'kategori' => $katAnalgesik,    'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 80],
            ['nama' => 'Tramadol 50mg',              'kode' => 'OBT-005', 'kategori' => $katAnalgesik,    'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 500, 'stok' => 60],

            // ─── Antibiotik ──────────────────────────────────────────────
            ['nama' => 'Amoxicillin 500mg',          'kode' => 'OBT-006', 'kategori' => $katAntibiotik,   'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 360, 'stok' => 100],
            ['nama' => 'Ciprofloxacin 500mg',        'kode' => 'OBT-007', 'kategori' => $katAntibiotik,   'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 90],
            ['nama' => 'Eritromisin 500mg',          'kode' => 'OBT-008', 'kategori' => $katAntibiotik,   'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 270, 'stok' => 70],
            ['nama' => 'Cefadroxil 500mg',           'kode' => 'OBT-009', 'kategori' => $katAntibiotik,   'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 85],
            ['nama' => 'Amoxicillin Sirup 125mg/5ml','kode' => 'OBT-010', 'kategori' => $katAntibiotik,   'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 20,  'stok' => 30],  // near expired

            // ─── Vitamin & Suplemen ──────────────────────────────────────
            ['nama' => 'Vitamin C 500mg',            'kode' => 'OBT-011', 'kategori' => $katVitamin,      'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 730, 'stok' => 300],
            ['nama' => 'Vitamin B Kompleks',         'kode' => 'OBT-012', 'kategori' => $katVitamin,      'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 600, 'stok' => 200],
            ['nama' => 'Vitamin D3 1000IU',          'kode' => 'OBT-013', 'kategori' => $katVitamin,      'jenis' => $jenKapsul,  'satuan' => $satBox,    'kadaluarsa' => 540, 'stok' => 150],
            ['nama' => 'Multivitamin Anak Sirup',    'kode' => 'OBT-014', 'kategori' => $katVitamin,      'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 365, 'stok' => 60],
            ['nama' => 'Zinc 20mg',                  'kode' => 'OBT-015', 'kategori' => $katVitamin,      'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 500, 'stok' => 120],

            // ─── Antasid & Lambung ───────────────────────────────────────
            ['nama' => 'Antasida DOEN',              'kode' => 'OBT-016', 'kategori' => $katAntaasid,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 100],
            ['nama' => 'Omeprazole 20mg',            'kode' => 'OBT-017', 'kategori' => $katAntaasid,     'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 80],
            ['nama' => 'Ranitidin 150mg',            'kode' => 'OBT-018', 'kategori' => $katAntaasid,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 300, 'stok' => 90],
            ['nama' => 'Sucralfate Sirup',           'kode' => 'OBT-019', 'kategori' => $katAntaasid,     'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 180, 'stok' => 40],
            ['nama' => 'Domperidone 10mg',           'kode' => 'OBT-020', 'kategori' => $katAntaasid,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 70],

            // ─── Antihistamin ────────────────────────────────────────────
            ['nama' => 'CTM (Chlorpheniramine)',     'kode' => 'OBT-021', 'kategori' => $katAntihistamin, 'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 130],
            ['nama' => 'Loratadine 10mg',            'kode' => 'OBT-022', 'kategori' => $katAntihistamin, 'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 100],
            ['nama' => 'Cetirizine 10mg',            'kode' => 'OBT-023', 'kategori' => $katAntihistamin, 'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 90],
            ['nama' => 'Diphenhydramine Sirup',      'kode' => 'OBT-024', 'kategori' => $katAntihistamin, 'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 25,  'stok' => 20],  // near expired
            ['nama' => 'Fexofenadine 120mg',         'kode' => 'OBT-025', 'kategori' => $katAntihistamin, 'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 500, 'stok' => 75],

            // ─── Kardiovaskular ──────────────────────────────────────────
            ['nama' => 'Captopril 25mg',             'kode' => 'OBT-026', 'kategori' => $katCardio,       'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 110],
            ['nama' => 'Amlodipine 5mg',             'kode' => 'OBT-027', 'kategori' => $katCardio,       'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 95],
            ['nama' => 'Atorvastatin 20mg',          'kode' => 'OBT-028', 'kategori' => $katCardio,       'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 80],
            ['nama' => 'Bisoprolol 5mg',             'kode' => 'OBT-029', 'kategori' => $katCardio,       'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 500, 'stok' => 70],
            ['nama' => 'Furosemide 40mg',            'kode' => 'OBT-030', 'kategori' => $katCardio,       'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 300, 'stok' => 60],

            // ─── Antidiabetes ────────────────────────────────────────────
            ['nama' => 'Metformin 500mg',            'kode' => 'OBT-031', 'kategori' => $katDiabetes,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 120],
            ['nama' => 'Glibenclamide 5mg',          'kode' => 'OBT-032', 'kategori' => $katDiabetes,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 100],
            ['nama' => 'Glimepiride 2mg',            'kode' => 'OBT-033', 'kategori' => $katDiabetes,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 365, 'stok' => 85],
            ['nama' => 'Acarbose 50mg',              'kode' => 'OBT-034', 'kategori' => $katDiabetes,     'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 300, 'stok' => 65],
            ['nama' => 'Insulin Novorapid 100IU',    'kode' => 'OBT-035', 'kategori' => $katDiabetes,     'jenis' => $jenInjeksi, 'satuan' => $satAmpul,  'kadaluarsa' => 180, 'stok' => 30],

            // ─── Dermatologi ─────────────────────────────────────────────
            ['nama' => 'Hidrokortison Krim 1%',      'kode' => 'OBT-036', 'kategori' => $katDermatologi,  'jenis' => $jenSalep,   'satuan' => $satTube,   'kadaluarsa' => 365, 'stok' => 50],
            ['nama' => 'Miconazole Krim 2%',         'kode' => 'OBT-037', 'kategori' => $katDermatologi,  'jenis' => $jenSalep,   'satuan' => $satTube,   'kadaluarsa' => 400, 'stok' => 45],
            ['nama' => 'Betamethasone Krim',         'kode' => 'OBT-038', 'kategori' => $katDermatologi,  'jenis' => $jenSalep,   'satuan' => $satTube,   'kadaluarsa' => 300, 'stok' => 40],
            ['nama' => 'Salicyl Talk',               'kode' => 'OBT-039', 'kategori' => $katDermatologi,  'jenis' => $jenSalep,   'satuan' => $satPcs,    'kadaluarsa' => 730, 'stok' => 60],
            ['nama' => 'Gentamicin Salep 0.1%',      'kode' => 'OBT-040', 'kategori' => $katDermatologi,  'jenis' => $jenSalep,   'satuan' => $satTube,   'kadaluarsa' => 365, 'stok' => 35],

            // ─── Tetes Mata ──────────────────────────────────────────────
            ['nama' => 'Chloramphenicol Tetes Mata', 'kode' => 'OBT-041', 'kategori' => $katMata,         'jenis' => $jenTetes,   'satuan' => $satPcs,    'kadaluarsa' => 365, 'stok' => 55],
            ['nama' => 'Cendo Lyteers Tetes Mata',   'kode' => 'OBT-042', 'kategori' => $katMata,         'jenis' => $jenTetes,   'satuan' => $satPcs,    'kadaluarsa' => 400, 'stok' => 40],
            ['nama' => 'Saline Tetes Mata',          'kode' => 'OBT-043', 'kategori' => $katMata,         'jenis' => $jenTetes,   'satuan' => $satPcs,    'kadaluarsa' => 300, 'stok' => 30],

            // ─── Herbal ──────────────────────────────────────────────────
            ['nama' => 'Tolak Angin Cair',           'kode' => 'OBT-044', 'kategori' => $katHerbal,       'jenis' => $jenSirup,   'satuan' => $satPcs,    'kadaluarsa' => 540, 'stok' => 80],
            ['nama' => 'Antangin JRG',               'kode' => 'OBT-045', 'kategori' => $katHerbal,       'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 500, 'stok' => 70],
            ['nama' => 'Kiranti Sehat Datang Bulan', 'kode' => 'OBT-046', 'kategori' => $katHerbal,       'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 365, 'stok' => 50],
            ['nama' => 'Enkasari Tablet',            'kode' => 'OBT-047', 'kategori' => $katHerbal,       'jenis' => $jenTablet,  'satuan' => $satStrip,  'kadaluarsa' => 400, 'stok' => 60],
            ['nama' => 'Curcuma Plus Sirup',         'kode' => 'OBT-048', 'kategori' => $katHerbal,       'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 365, 'stok' => 45],
            ['nama' => 'Stimuno Sirup',              'kode' => 'OBT-049', 'kategori' => $katHerbal,       'jenis' => $jenSirup,   'satuan' => $satBotol,  'kadaluarsa' => 25,  'stok' => 25],  // near expired
            ['nama' => 'Diapet NR Kapsul',           'kode' => 'OBT-050', 'kategori' => $katHerbal,       'jenis' => $jenKapsul,  'satuan' => $satStrip,  'kadaluarsa' => 450, 'stok' => 55],
        ];

        foreach ($obatList as $data) {
            // Skip jika kode sudah ada
            if (Obat::where('kode', $data['kode'])->exists()) continue;

            $obat = Obat::create([
                'nama'          => $data['nama'],
                'kode'          => $data['kode'],
                'kategori_id'   => $data['kategori']->id,
                'jenis_id'      => $data['jenis']->id,
                'satuan_id'     => $data['satuan']->id,
                'tgl_kadaluarsa'=> now()->addDays($data['kadaluarsa'])->format('Y-m-d'),
            ]);

            // Buat transaksi masuk awal untuk stok
            Transaksi::create([
                'obat_id'    => $obat->id,
                'jenis'      => 'masuk',
                'jumlah'     => $data['stok'],
                'tanggal'    => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'keterangan' => 'Stok awal pengadaan',
            ]);
        }

        $this->command->info('✅ 50 data obat berhasil ditambahkan!');
    }
}
