<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Paket extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['foto_url']; // snake_case untuk auto JSON

    // ✅ Gunakan accessor konvensional
    public function getFotoUrlAttribute()
    {
        $path = $this->attributes['fotoUrl'] ?? null;

        if (!$path) return null;

        // Kembalikan absolute URL jika belum
        return str_starts_with($path, 'http') ? $path : URL::to($path);
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}