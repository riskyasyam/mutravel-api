<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaketController extends Controller
{
    /**
     * Menampilkan daftar 6 paket terdekat untuk landing page.
     */
    public function index()
    {
        $pakets = Paket::orderBy('tanggalKeberangkatan', 'asc')->take(6)->get();
        return response()->json($pakets);
    }

    /**
     * Menampilkan semua paket untuk dashboard.
     */
    public function getAllForDashboard()
    {
        $pakets = Paket::orderBy('created_at', 'desc')->get();
        return response()->json($pakets);
    }

    /**
     * Menyimpan data paket baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'namaPaket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer',
            'durasi' => 'required|integer',
            'tanggalKeberangkatan' => 'required|date',
            'hotelMadinah' => 'required|string',
            'hotelMakkah' => 'required|string',
            'pesawat' => 'required|string',
            'ratingHotelMakkah' => 'required|integer|min:1|max:5',
            'ratingHotelMadinah' => 'required|integer|min:1|max:5',
            'sisaKursi' => 'required|integer',
            'fotoPaket' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($request->hasFile('fotoPaket')) {
            $path = $request->file('fotoPaket')->store('uploads/pakets', 'public');
            $validatedData['fotoUrl'] = '/storage/' . $path;
        }

        unset($validatedData['fotoPaket']);
        $paket = Paket::create($validatedData);

        return response()->json($paket, 201);
    }

    /**
     * Menampilkan detail satu paket.
     */
    public function show(Paket $paket)
    {
        return response()->json($paket);
    }

    /**
     * Mengupdate data paket.
     */
    public function update(Request $request, Paket $paket)
    {
        $validatedData = $request->validate([
            'namaPaket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer',
            'durasi' => 'required|integer',
            'tanggalKeberangkatan' => 'required|date',
            'hotelMadinah' => 'required|string',
            'hotelMakkah' => 'required|string',
            'pesawat' => 'required|string',
            'ratingHotelMakkah' => 'required|integer|min:1|max:5',
            'ratingHotelMadinah' => 'required|integer|min:1|max:5',
            'sisaKursi' => 'required|integer',
            'fotoPaket' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($request->hasFile('fotoPaket')) {
            // Hapus file lama jika ada
            if ($paket->fotoUrl) {
                Storage::delete(str_replace('/storage', 'public', $paket->fotoUrl));
            }

            $path = $request->file('fotoPaket')->store('uploads/pakets', 'public');
            $validatedData['fotoUrl'] = '/storage/' . $path;
        }

        unset($validatedData['fotoPaket']);

        $paket->update($validatedData);
        return response()->json($paket, 200);
    }

    /**
     * Menghapus data paket.
     */
    public function destroy(Paket $paket)
    {
        if ($paket->fotoUrl) {
            Storage::delete(str_replace('/storage', 'public', $paket->fotoUrl));
        }

        $paket->delete();
        return response()->json(['message' => 'Paket berhasil dihapus.'], 200);
    }
}