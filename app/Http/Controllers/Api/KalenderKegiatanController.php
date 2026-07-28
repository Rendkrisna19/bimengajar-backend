<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KalenderKegiatan;
use Illuminate\Http\Request;

class KalenderKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KalenderKegiatan::query();

        // Optional filtering by month and year
        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('tanggal_mulai', $request->bulan)
                  ->whereYear('tanggal_mulai', $request->tahun);
        }

        $kegiatan = $query->orderBy('tanggal_mulai', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Kalender Kegiatan',
            'data'    => $kegiatan
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string',
            'status' => 'required|in:Terlaksana,Belum Dilaksanakan',
            'jenis_kegiatan' => 'nullable|string',
        ]);

        $kegiatan = KalenderKegiatan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil ditambahkan',
            'data'    => $kegiatan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kegiatan = KalenderKegiatan::find($id);

        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kegiatan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Kegiatan',
            'data'    => $kegiatan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kegiatan = KalenderKegiatan::find($id);

        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kegiatan tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string',
            'status' => 'required|in:Terlaksana,Belum Dilaksanakan',
            'jenis_kegiatan' => 'nullable|string',
        ]);

        $kegiatan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil diupdate',
            'data'    => $kegiatan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = KalenderKegiatan::find($id);

        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Kegiatan tidak ditemukan',
            ], 404);
        }

        $kegiatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil dihapus',
        ], 200);
    }
}
