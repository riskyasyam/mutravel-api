<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    /**
     * Menampilkan semua data dokumentasi.
     */
    public function index()
    {
        $dokumentasi = Dokumentasi::orderBy('created_at', 'desc')->get();
        return response()->json($dokumentasi);
    }

    /**
     * Menyimpan data dokumentasi baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // max 10MB
            'deskripsi' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/uploads/dokumentasi');
            $validatedData['fotoUrl'] = Storage::url($path);
        }

        unset($validatedData['foto']);

        $dokumentasi = Dokumentasi::create($validatedData);
        return response()->json($dokumentasi, 201);
    }

    /**
     * Menampilkan detail satu dokumentasi.
     */
    public function show(Dokumentasi $dokumentasi)
    {
        return response()->json($dokumentasi);
    }

    /**
     * Mengupdate data dokumentasi.
     */
    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $validatedData = $request->validate([
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            if ($dokumentasi->fotoUrl) {
                Storage::delete(str_replace('/storage', 'public', $dokumentasi->fotoUrl));
            }
            $path = $request->file('foto')->store('public/uploads/dokumentasi');
            $validatedData['fotoUrl'] = Storage::url($path);
        }

        unset($validatedData['foto']);
        
        $dokumentasi->update($validatedData);
        return response()->json($dokumentasi);
    }

    /**
     * Menghapus data dokumentasi.
     */
    public function destroy(Dokumentasi $dokumentasi)
    {
        if ($dokumentasi->fotoUrl) {
            Storage::delete(str_replace('/storage', 'public', $dokumentasi->fotoUrl));
        }
        $dokumentasi->delete();
        return response()->json(null, 204);
    }
}