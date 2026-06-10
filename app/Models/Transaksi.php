<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'obat_id',
        'jenis',
        'jumlah',
        'tanggal',
        'keterangan'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}