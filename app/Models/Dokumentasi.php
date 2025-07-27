<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Ini akan membuat foto_url muncul otomatis di response JSON
    protected $appends = ['foto_url'];

    // accessor untuk menambahkan field foto_url di response
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>
                isset($attributes['fotoUrl']) && !str_starts_with($attributes['fotoUrl'], 'http')
                    ? URL::to($attributes['fotoUrl'])
                    : $attributes['fotoUrl'],
        );
    }

    // accessor tambahan agar foto_url muncul dengan nama snake_case (optional)
    public function getFotoUrlAttribute(): string|null
    {
        $path = $this->attributes['fotoUrl'] ?? null;
        return $path && !str_starts_with($path, 'http')
            ? URL::to($path)
            : $path;
    }
}