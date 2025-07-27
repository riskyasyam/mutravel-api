<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Jamaah;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    /**
     * Menampilkan rekapitulasi tabungan semua jamaah (dengan pencarian).
     */
    public function summary(Request $request)
    {
        $query = $request->input('query');

        // Ambil data jamaah dengan relasi tabungannya
        $jamaahs = Jamaah::with('tabungan')
            ->when($query, function ($q, $query) {
                return $q->where('namaLengkap', 'like', "%{$query}%");
            })
            ->orderBy('namaLengkap', 'asc')
            ->get();
            
        // Hitung total dan jumlah setoran menggunakan collection method di PHP
        // Ini memastikan semua jamaah ditampilkan, bahkan yang saldonya nol.
        $result = $jamaahs->map(function ($jamaah) {
            return [
                'id' => $jamaah->id,
                'namaLengkap' => $jamaah->namaLengkap,
                'jumlahSetoran' => $jamaah->tabungan->count(),
                'totalTabungan' => $jamaah->tabungan->sum('jumlahSetoran'),
            ];
        });

        return response()->json($result);
    }

    /**
     * Menampilkan riwayat tabungan untuk seorang jamaah.
     */
    public function index(Jamaah $jamaah)
    {
        $tabungans = $jamaah->tabungan()->orderBy('tanggalSetoran', 'desc')->get();
        return response()->json($tabungans);
    }

    /**
     * Menyimpan data setoran tabungan baru.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'jamaah_id' => 'required|exists:jamaahs,id',
        'jumlahSetoran' => 'required|numeric',
        'keterangan' => 'nullable|string|max:255',
    ]);

    $tabungan = Tabungan::create([
        'jamaah_id' => $validated['jamaah_id'],
        'jumlahSetoran' => $validated['jumlahSetoran'],
        'keterangan' => $validated['keterangan'],
    ]);

    return response()->json($tabungan, 201);
    }

    /**
     * Menampilkan detail satu setoran.
     */
    public function show(Tabungan $tabungan)
    {
        return response()->json($tabungan);
    }

    /**
     * Mengupdate data setoran.
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
     */
    public function destroy(Tabungan $tabungan)
    {
        $tabungan->delete();
        return response()->json(null, 204);
    }
}