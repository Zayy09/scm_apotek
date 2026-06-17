<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObatBatch extends Model
{
    protected $table = 'obat_batches';

    protected $fillable = [
        'obat_id',
        'tgl_kadaluarsa',
        'stok',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
