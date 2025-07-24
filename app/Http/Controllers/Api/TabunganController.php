<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Jamaah;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    /**
     * Menampilkan riwayat tabungan untuk seorang jamaah.
     * Route: GET /api/jamaahs/{jamaah}/tabungans
     */
    public function index(Jamaah $jamaah)
    {
        $tabungans = $jamaah->tabungan()->orderBy('tanggalSetoran', 'desc')->get();
        return response()->json($tabungans);
    }

    /**
     * Menyimpan data setoran tabungan baru.
     * Route: POST /api/tabungans
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jamaahId' => 'required|exists:jamaahs,id',
            'jumlahSetoran' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tabungan = Tabungan::create($validatedData);
        return response()->json($tabungan, 201);
    }

    /**
     * Menampilkan detail satu setoran.
     * Route: GET /api/tabungans/{tabungan}
     */
    public function show(Tabungan $tabungan)
    {
        return response()->json($tabungan);
    }

    /**
     * Mengupdate data setoran.
     * Route: PUT/PATCH /api/tabungans/{tabungan}
     */
    public function update(Request $request, Tabungan $tabungan)
    {
        $validatedData = $request->validate([
            'jumlahSetoran' => 'sometimes|required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tabungan->update($validatedData);
        return response()->json($tabungan);
    }

    /**
     * Menghapus data setoran.
     * Route: DELETE /api/tabungans/{tabungan}
     */
    public function destroy(Tabungan $tabungan)
    {
        $tabungan->delete();
        return response()->json(null, 204);
    }
}
