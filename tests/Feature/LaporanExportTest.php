<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Obat;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_laporan_masuk_export(): void
    {
        $user = User::factory()->create();

        $obat = Obat::create([
            'nama' => 'Paracetamol',
            'kode' => 'OBT001',
            'kategori' => 'Analgesik',
            'jenis' => 'Tablet',
            'stok' => 50,
            'satuan' => 'Strip',
            'tgl_kadaluarsa' => '2027-12-31'
        ]);

        Transaksi::create([
            'obat_id' => $obat->id,
            'jenis' => 'masuk',
            'jumlah' => 20,
            'tanggal' => '2026-06-01',
            'keterangan' => 'Barang masuk dari supplier A'
        ]);

        $response = $this->actingAs($user)->get('/laporan/masuk/export');

        $response->assertStatus(200);
        
        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringStartsWith('attachment; filename="laporan_barang_masuk_', $contentDisposition);
        $this->assertStringEndsWith('.csv"', $contentDisposition);
        
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));

        $content = $response->streamedContent();
        
        $this->assertStringContainsString('sep=,', $content);
        $this->assertStringContainsString('No,"Kode Barang","Nama Barang","Tanggal Masuk",Satuan,"Jumlah Masuk",Keterangan', $content);
        $this->assertStringContainsString('OBT001', $content);
        $this->assertStringContainsString('Paracetamol', $content);
        $this->assertStringContainsString('2026-06-01', $content);
        $this->assertStringContainsString('Strip', $content);
        $this->assertStringContainsString('20', $content);
        $this->assertStringContainsString('Barang masuk dari supplier A', $content);
    }

    public function test_laporan_masuk_export_with_date_filter(): void
    {
        $user = User::factory()->create();

        $obat = Obat::create([
            'nama' => 'Paracetamol',
            'kode' => 'OBT001',
            'kategori' => 'Analgesik',
            'jenis' => 'Tablet',
            'stok' => 50,
            'satuan' => 'Strip',
            'tgl_kadaluarsa' => '2027-12-31'
        ]);

        // In range
        Transaksi::create([
            'obat_id' => $obat->id,
            'jenis' => 'masuk',
            'jumlah' => 20,
            'tanggal' => '2026-06-02',
            'keterangan' => 'Barang masuk A'
        ]);

        // Out of range
        Transaksi::create([
            'obat_id' => $obat->id,
            'jenis' => 'masuk',
            'jumlah' => 15,
            'tanggal' => '2026-06-10',
            'keterangan' => 'Barang masuk B'
        ]);

        $response = $this->actingAs($user)->get('/laporan/masuk/export?start_date=2026-06-01&end_date=2026-06-05');

        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('Barang masuk A', $content);
        $this->assertStringNotContainsString('Barang masuk B', $content);
    }

    public function test_laporan_keluar_export(): void
    {
        $user = User::factory()->create();

        $obat = Obat::create([
            'nama' => 'Amoxicillin',
            'kode' => 'OBT002',
            'kategori' => 'Antibiotik',
            'jenis' => 'Tablet',
            'stok' => 40,
            'satuan' => 'Strip',
            'tgl_kadaluarsa' => '2027-12-31'
        ]);

        Transaksi::create([
            'obat_id' => $obat->id,
            'jenis' => 'keluar',
            'jumlah' => 5,
            'tanggal' => '2026-06-03',
            'keterangan' => 'Penjualan'
        ]);

        $response = $this->actingAs($user)->get('/laporan/keluar/export');

        $response->assertStatus(200);
        
        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringStartsWith('attachment; filename="laporan_barang_keluar_', $contentDisposition);
        $this->assertStringEndsWith('.csv"', $contentDisposition);

        $content = $response->streamedContent();
        
        $this->assertStringContainsString('sep=,', $content);
        $this->assertStringContainsString('No,"Kode Barang","Nama Barang","Tanggal Keluar",Satuan,"Jumlah Keluar",Keterangan', $content);
        $this->assertStringContainsString('OBT002', $content);
        $this->assertStringContainsString('Amoxicillin', $content);
        $this->assertStringContainsString('2026-06-03', $content);
        $this->assertStringContainsString('Penjualan', $content);
    }
}
