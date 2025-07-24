<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JamaahController extends Controller
{
    /**
     * Menampilkan semua data jamaah dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $jamaahs = Jamaah::when($query, function ($q, $query) {
            return $q->where('namaLengkap', 'like', "%{$query}%")
                     ->orWhere('nomorKtp', 'like', "%{$query}%")
                     ->orWhere('nomorTelepon', 'like', "%{$query}%");
        })->orderBy('namaLengkap', 'asc')->get();

        return response()->json($jamaahs);
    }

    /**
     * Menyimpan data jamaah baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'namaLengkap' => 'required|string|max:255',
            'nomorKtp' => 'required|string|unique:jamaahs,nomorKtp',
            'nomorPaspor' => 'nullable|string|unique:jamaahs,nomorPaspor',
            'tempatLahir' => 'required|string',
            'tanggalLahir' => 'required|date',
            'jenisKelamin' => 'required|string',
            'alamat' => 'required|string',
            'nomorTelepon' => 'required|string',
            'email' => 'nullable|email',
            'pekerjaan' => 'nullable|string',
            'scanKtp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scanPaspor' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('scanKtp')) {
            $path = $request->file('scanKtp')->store('public/uploads/dokumen');
            $validatedData['scanKtpUrl'] = Storage::url($path);
        }

        if ($request->hasFile('scanPaspor')) {
            $path = $request->file('scanPaspor')->store('public/uploads/dokumen');
            $validatedData['scanPasporUrl'] = Storage::url($path);
        }

        unset($validatedData['scanKtp'], $validatedData['scanPaspor']);

        $jamaah = Jamaah::create($validatedData);
        return response()->json($jamaah, 201);
    }

    /**
     * Menampilkan detail satu jamaah.
     */
    public function show(Jamaah $jamaah)
    {
        return response()->json($jamaah);
    }

    /**
     * Mengupdate data jamaah.
     */
    public function update(Request $request, Jamaah $jamaah)
    {
        $validatedData = $request->validate([
            'namaLengkap' => 'sometimes|required|string|max:255',
            'nomorKtp' => ['sometimes', 'required', 'string', Rule::unique('jamaahs')->ignore($jamaah->id)],
            'nomorPaspor' => ['nullable', 'string', Rule::unique('jamaahs')->ignore($jamaah->id)],
            'tempatLahir' => 'sometimes|required|string',
            'tanggalLahir' => 'sometimes|required|date',
            'jenisKelamin' => 'sometimes|required|string',
            'alamat' => 'sometimes|required|string',
            'nomorTelepon' => 'sometimes|required|string',
            'email' => 'nullable|email',
            'pekerjaan' => 'nullable|string',
            'scanKtp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scanPaspor' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('scanKtp')) {
            if ($jamaah->scanKtpUrl) Storage::delete(str_replace('/storage', 'public', $jamaah->scanKtpUrl));
            $path = $request->file('scanKtp')->store('public/uploads/dokumen');
            $validatedData['scanKtpUrl'] = Storage::url($path);
        }

        if ($request->hasFile('scanPaspor')) {
            if ($jamaah->scanPasporUrl) Storage::delete(str_replace('/storage', 'public', $jamaah->scanPasporUrl));
            $path = $request->file('scanPaspor')->store('public/uploads/dokumen');
            $validatedData['scanPasporUrl'] = Storage::url($path);
        }
        
        unset($validatedData['scanKtp'], $validatedData['scanPaspor']);

        $jamaah->update($validatedData);
        return response()->json($jamaah);
    }

    /**
     * Menghapus data jamaah.
     */
    public function destroy(Jamaah $jamaah)
    {
        if ($jamaah->scanKtpUrl) {
            Storage::delete(str_replace('/storage', 'public', $jamaah->scanKtpUrl));
        }
        if ($jamaah->scanPasporUrl) {
            Storage::delete(str_replace('/storage', 'public', $jamaah->scanPasporUrl));
        }
        
        // Di masa depan, tambahkan pengecekan relasi ke pemesanan/tabungan sebelum menghapus
        $jamaah->delete();
        return response()->json(null, 204);
    }
}