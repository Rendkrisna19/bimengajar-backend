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

        $ulasan = Ulasan::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $ulasan
        ], 201);
    }
}
