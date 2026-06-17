<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $fillable = ['nama', 'stok_min'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'satuan_id');
    }
}
