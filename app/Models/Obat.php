<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama',
        'kode',
        'kategori_id',
        'jenis_id',
        'satuan_id',
        'supplier_id',
        'tgl_kadaluarsa',
    ];

    /**
     * Stok dihitung otomatis dari obat masuk dikurangi obat keluar.
     */
    public function getStokAttribute(): int
    {
        $masuk  = $this->transaksi->where('jenis', 'masuk')->sum('jumlah');
        $keluar = $this->transaksi->whereIn('jenis', ['keluar', 'kadaluarsa'])->sum('jumlah');
        return max(0, $masuk - $keluar);
    }

    /**
     * Ambil stok minimum dari satuan yang terhubung.
     */
    public function getStokMinAttribute(): int
    {
        return $this->satuan ? ($this->satuan->stok_min ?? 0) : 0;
    }

    /**
     * Cek apakah stok obat sudah menipis (stok <= stok_min).
     */
    public function getStokMenipisAttribute(): bool
    {
        $stokMin = $this->stok_min;
        return $stokMin > 0 && $this->stok <= $stokMin;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'obat_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(Transaksi::class, 'obat_id')->where('jenis', 'masuk');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(Transaksi::class, 'obat_id')->where('jenis', 'keluar');
    }

    public function batches()
    {
        return $this->hasMany(ObatBatch::class, 'obat_id');
    }
}