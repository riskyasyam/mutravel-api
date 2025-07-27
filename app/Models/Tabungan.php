<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Mendefinisikan relasi: Satu Tabungan dimiliki oleh satu Jamaah.
     */
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}