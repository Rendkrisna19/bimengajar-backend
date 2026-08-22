<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index()
    {
        $ulasan = Ulasan::orderBy('created_at', 'desc')->take(50)->get();
        return response()->json([
            'status' => 'success',
            'data' => $ulasan
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['status'] = $request->input('status', 'disetujui');
        $ulasan = Ulasan::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $ulasan
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->status = $request->input('status', 'disetujui');
        $ulasan->save();

        return response()->json([
            'status' => 'success',
            'data' => $ulasan
        ]);
    }

    public function update(Request $request, $id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $ulasan
        ]);
    }

    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil dihapus'
        ]);
    }
}
