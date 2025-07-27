<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PemesananController extends Controller
{
    /**
     * Menampilkan semua data pemesanan dengan relasi dan pencarian.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $pemesanans = Pemesanan::with(['jamaah', 'paket']) // Eager load relasi
            ->when($query, function ($q, $query) {
                return $q->whereHas('jamaah', function ($subQ) use ($query) {
                    $subQ->where('namaLengkap', 'like', "%{$query}%");
                })->orWhereHas('paket', function ($subQ) use ($query) {
                    $subQ->where('namaPaket', 'like', "%{$query}%");
                });
            })
            ->orderBy('tanggalPemesanan', 'desc')
            ->get();

        return response()->json($pemesanans);
    }

    /**
     * Menyimpan data pemesanan baru.
     */
    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'jamaahId' => 'required|exists:jamaahs,id',
        'paketId' => 'required|exists:pakets,id',
        'statusPembayaran' => 'required|string|max:255',
        'catatan' => 'nullable|string',
    ]);

    $pemesanan = Pemesanan::create([
        'jamaah_id' => $validatedData['jamaahId'],
        'paket_id' => $validatedData['paketId'],
        'statusPembayaran' => $validatedData['statusPembayaran'],
        'catatan' => $validatedData['catatan'] ?? null,
        'tanggalPemesanan' => now(),
    ]);
        return response()->json($pemesanan->load(['jamaah', 'paket']), 201);
    }

    /**
     * Menampilkan detail satu pemesanan.
     */
    public function show(Pemesanan $pemesanan)
    {
        return response()->json($pemesanan->load(['jamaah', 'paket']));
    }

    /**
     * Mengupdate data pemesanan.
     */
    public function update(Request $request, Pemesanan $pemesanan)
    {
    $validatedData = $request->validate([
        'jamaahId' => 'required|exists:jamaahs,id',
        'paketId' => 'required|exists:pakets,id',
        'statusPembayaran' => 'required|string|max:255',
        'catatan' => 'nullable|string',
    ]);

    $pemesanan = Pemesanan::create([
        'jamaah_id' => $validatedData['jamaahId'],
        'paket_id' => $validatedData['paketId'],
        'statusPembayaran' => $validatedData['statusPembayaran'],
        'catatan' => $validatedData['catatan'] ?? null,
        'tanggalPemesanan' => now(),
    ]);
        return response()->json($pemesanan->load(['jamaah', 'paket']));
    }

    /**
     * Menghapus data pemesanan.
     */
    public function destroy(Pemesanan $pemesanan)
    {
        $pemesanan->delete();
        return response()->json(null, 204);
    }
    public function recent()
    {
        $recentPemesanan = Pemesanan::with(['jamaah:id,namaLengkap', 'paket:id,namaPaket'])
            ->orderBy('tanggalPemesanan', 'desc')
            ->take(5)
            ->get();

        return response()->json($recentPemesanan);
    }
}