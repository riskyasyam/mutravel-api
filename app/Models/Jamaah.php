<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Mendefinisikan relasi: Satu Jamaah bisa memiliki banyak Pemesanan.
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    /**
     * Mendefinisikan relasi: Satu Jamaah bisa memiliki banyak Tabungan.
     */
    public function tabungan()
    {
        return $this->hasMany(Tabungan::class);
    }
}