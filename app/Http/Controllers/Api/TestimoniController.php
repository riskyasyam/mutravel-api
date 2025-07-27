<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimoni = Testimoni::orderBy('created_at', 'desc')->get();
        return response()->json($testimoni);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'namaJamaah' => 'required|string|max:255',
            'deskripsiTestimoni' => 'required|string',
            'fotoUrl' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('fotoUrl')) {
            $path = $request->file('fotoUrl')->store('uploads/testimoni', 'public');
            $validatedData['fotoUrl'] = '/storage/' . $path;
        }

        $testimoni = Testimoni::create($validatedData);

        return response()->json($testimoni, 201);
    }

    public function show(Testimoni $testimoni)
    {
        return response()->json($testimoni);
    }

    public function update(Request $request, Testimoni $testimoni)
    {
        $validatedData = $request->validate([
            'namaJamaah' => 'sometimes|required|string|max:255',
            'deskripsiTestimoni' => 'sometimes|required|string',
            'fotoUrl' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('fotoUrl')) {
            if ($testimoni->fotoUrl) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $testimoni->fotoUrl));
            }

            $path = $request->file('fotoUrl')->store('uploads/testimoni', 'public');
            $validatedData['fotoUrl'] = '/storage/' . $path;
        }

        $testimoni->update($validatedData);
        return response()->json($testimoni);
    }

    public function destroy(Testimoni $testimoni)
    {
        if ($testimoni->fotoUrl) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $testimoni->fotoUrl));
        }

        $testimoni->delete();
        return response()->json(['message' => 'Testimoni berhasil dihapus.'], 200);
    }
}