<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriMateriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriMateri::orderBy('nama', 'asc')->get();
        return response()->json([
            'message' => 'Berhasil mengambil daftar kategori materi',
            'data' => $kategoris
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048'
        ]);

        $data = [
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama)
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('kategori_logo', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $kategori = KategoriMateri::create($data);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048'
        ]);
        
        $kategori = KategoriMateri::findOrFail($id);
        
        $data = [
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama)
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('kategori_logo', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $kategori->update($data);

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = KategoriMateri::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
