<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Mendefinisikan relasi: Satu Paket bisa memiliki banyak Pemesanan.
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}