<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Mendefinisikan relasi: Satu Pemesanan dimiliki oleh satu Jamaah.
     */
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    /**
     * Mendefinisikan relasi: Satu Pemesanan dimiliki oleh satu Paket.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
}