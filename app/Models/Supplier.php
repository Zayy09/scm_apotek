<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['nama', 'no_telepon', 'email', 'alamat'];

    /**
     * Obat yang dibeli dari supplier ini.
     */
    public function obat()
    {
        return $this->hasMany(Obat::class, 'supplier_id');
    }
}
