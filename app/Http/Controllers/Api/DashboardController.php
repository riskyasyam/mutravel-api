<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Pemesanan;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mengambil data statistik untuk halaman dashboard utama.
     */
    public function stats()
    {
        $jamaahCount = Jamaah::count();
        $pemesananCount = Pemesanan::count();
        $totalTabungan = Tabungan::sum('jumlahSetoran');

        return response()->json([
            'jamaahCount' => $jamaahCount,
            'pemesananCount' => $pemesananCount,
            'totalTabungan' => (int) $totalTabungan,
        ]);
    }
}