<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    /**
     * GET /api/dokumentasi
     * Public: paginated list with optional filter by kategori
     */
    public function index(Request $request)
    {
        $query = Dokumentasi::orderBy('tanggal_kegiatan', 'desc');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->boolean('all')) {
            return response()->json(['status' => 'success', 'data' => $query->get()]);
        }

        $perPage = (int) $request->get('per_page', 9);
        $data = $query->paginate($perPage);

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    /**
     * GET /api/dokumentasi/{id}
     */
    public function show($id)
    {
        $dok = Dokumentasi::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $dok]);
    }

    /**
     * POST /api/dokumentasi  (admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'   => 'required|string|max:255',
            'kategori'        => 'required|string|max:100',
            'deskripsi'       => 'nullable|string',
            'tanggal_kegiatan'=> 'required|date',
            'posted_by'       => 'nullable|string|max:150',
            'images.*'        => 'nullable|image|max:5120',
            'video_urls'      => 'nullable|string',  // JSON string dari frontend
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('dokumentasi', 'public');
                $imagePaths[] = Storage::url($path);
            }
        }

        $videoUrls = [];
        if ($request->filled('video_urls')) {
            $videoUrls = json_decode($request->video_urls, true) ?? [];
        }

        $dok = Dokumentasi::create([
            'nama_kegiatan'    => $request->nama_kegiatan,
            'kategori'         => $request->kategori,
            'deskripsi'        => $request->deskripsi,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'posted_by'        => $request->posted_by ?? 'Admin BI',
            'images'           => $imagePaths,
            'video_urls'       => $videoUrls,
        ]);

        return response()->json(['status' => 'success', 'data' => $dok], 201);
    }

    /**
     * POST /api/dokumentasi/{id}  (admin, for form-data update)
     */
    public function update(Request $request, $id)
    {
        $dok = Dokumentasi::findOrFail($id);

        $request->validate([
            'nama_kegiatan'   => 'sometimes|required|string|max:255',
            'kategori'        => 'nullable|string|max:100',
            'tanggal_kegiatan'=> 'nullable|date',
            'images.*'        => 'nullable|image|max:5120',
            'video_urls'      => 'nullable|string',
            'remove_images'   => 'nullable|string', // JSON array of indices to remove
        ]);

        // Handle image removal
        $imagePaths = $dok->images ?? [];
        if ($request->filled('remove_images')) {
            $toRemove = json_decode($request->remove_images, true) ?? [];
            foreach ($toRemove as $idx) {
                if (isset($imagePaths[$idx])) {
                    $urlPath = parse_url($imagePaths[$idx], PHP_URL_PATH) ?? $imagePaths[$idx];
                    $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');
                    Storage::disk('public')->delete($relativePath);
                }
            }
            $imagePaths = array_values(array_filter($imagePaths, fn($k) => !in_array($k, $toRemove), ARRAY_FILTER_USE_KEY));
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('dokumentasi', 'public');
                $imagePaths[] = Storage::url($path);
            }
        }

        // Handle video URLs
        $videoUrls = $dok->video_urls ?? [];
        if ($request->filled('video_urls')) {
            $videoUrls = json_decode($request->video_urls, true) ?? [];
        }

        $dok->update([
            'nama_kegiatan'    => $request->nama_kegiatan ?? $dok->nama_kegiatan,
            'kategori'         => $request->kategori ?? $dok->kategori,
            'deskripsi'        => $request->deskripsi ?? $dok->deskripsi,
            'tanggal_kegiatan' => $request->tanggal_kegiatan ?? $dok->tanggal_kegiatan,
            'posted_by'        => $request->posted_by ?? $dok->posted_by,
            'images'           => $imagePaths,
            'video_urls'       => $videoUrls,
        ]);

        return response()->json(['status' => 'success', 'data' => $dok->fresh()]);
    }

    /**
     * DELETE /api/dokumentasi/{id}  (admin only)
     */
    public function destroy($id)
    {
        $dok = Dokumentasi::findOrFail($id);

        // Delete stored images
        if ($dok->images) {
            foreach ($dok->images as $url) {
                $urlPath = parse_url($url, PHP_URL_PATH) ?? $url;
                $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');
                Storage::disk('public')->delete($relativePath);
            }
        }

        $dok->delete();

        return response()->json(['status' => 'success', 'message' => 'Dokumentasi berhasil dihapus']);
    }
}
