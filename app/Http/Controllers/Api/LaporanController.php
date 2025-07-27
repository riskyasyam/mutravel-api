<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class LaporanController extends Controller
{
    public function pemesanan(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        if (!$from || !$to) {
            return response()->json(['message' => 'Tanggal dari dan sampai harus diisi.'], 400);
        }

        $data = Pemesanan::with(['jamaah:id,namaLengkap', 'paket:id,namaPaket'])
            ->whereBetween('tanggalPemesanan', [$from, $to])
            ->orderBy('tanggalPemesanan', 'desc')
            ->get();

        return response()->json($data);
    }
}