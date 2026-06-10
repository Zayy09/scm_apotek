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
        'tgl_kadaluarsa'
    ];

    /**
     * Stok dihitung otomatis dari barang masuk dikurangi barang keluar.
     * Kolom 'stok' di database tidak dipakai lagi untuk input manual.
     */
    public function getStokAttribute(): int
    {
        $masuk  = $this->transaksi->where('jenis', 'masuk')->sum('jumlah');
        $keluar = $this->transaksi->where('jenis', 'keluar')->sum('jumlah');
        return max(0, $masuk - $keluar);
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
}