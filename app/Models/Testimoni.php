<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Testimoni extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['foto_url'];

    protected $hidden = ['fotoUrl']; // opsional, kalau kamu tidak ingin menampilkan nama field original

    /**
     * Accessor foto_url agar tampil full URL di response JSON.
     */
    public function getFotoUrlAttribute(): ?string
    {
        $path = $this->attributes['fotoUrl'] ?? null;
        return $path && !str_starts_with($path, 'http') ? URL::to($path) : $path;
    }
}