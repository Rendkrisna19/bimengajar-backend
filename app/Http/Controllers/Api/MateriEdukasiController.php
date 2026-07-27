<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MateriEdukasiController extends Controller
{
    public function index(Request $request)
    {
        $query = MateriEdukasi::with('kategori')->latest();

        // Filter by Kategori ID
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_materi_id', $request->kategori_id);
        }

        // Filter by Jenis Konten (Array of strings or single string)
        if ($request->has('jenis_konten') && $request->jenis_konten != '') {
            $jenis = is_array($request->jenis_konten) ? $request->jenis_konten : explode(',', $request->jenis_konten);
            $query->whereIn('jenis_konten', $jenis);
        }

        // Search by Judul
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $materis = $query->paginate(12);

        return response()->json([
            'message' => 'Berhasil mengambil data materi edukasi',
            'data' => $materis
        ]);
    }

    public function show($slug)
    {
        $materi = MateriEdukasi::with('kategori')->where('slug', $slug)->firstOrFail();
        
        return response()->json([
            'message' => 'Detail materi',
            'data' => $materi
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_materi_id' => 'required|exists:kategori_materis,id',
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'jenis_konten' => 'required|in:Artikel,Infografis,Video,E-Book,Presentasi',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_upload' => 'nullable|file|mimes:pdf,mp4,jpeg,png|max:10240',
            'link_eksternal' => 'nullable|string',
            'konten_teks' => 'nullable|string'
        ]);

        $data = $request->except(['thumbnail', 'file_upload']);
        $data['slug'] = Str::slug($request->judul) . '-' . uniqid();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('materi/thumbnails', 'public');
        }

        if ($request->hasFile('file_upload')) {
            $data['file_path'] = $request->file('file_upload')->store('materi/files', 'public');
        }

        $materi = MateriEdukasi::create($data);

        return response()->json([
            'message' => 'Materi berhasil ditambahkan',
            'data' => $materi
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_materi_id' => 'required|exists:kategori_materis,id',
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'jenis_konten' => 'required|in:Artikel,Infografis,Video,E-Book,Presentasi',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_upload' => 'nullable|file|mimes:pdf,mp4,jpeg,png|max:10240',
            'link_eksternal' => 'nullable|string',
            'konten_teks' => 'nullable|string'
        ]);

        $materi = MateriEdukasi::findOrFail($id);
        $data = $request->except(['thumbnail', 'file_upload']);
        
        if ($request->judul !== $materi->judul) {
            $data['slug'] = Str::slug($request->judul) . '-' . uniqid();
        }

        if ($request->hasFile('thumbnail')) {
            if ($materi->thumbnail) {
                Storage::disk('public')->delete($materi->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('materi/thumbnails', 'public');
        }

        if ($request->hasFile('file_upload')) {
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $data['file_path'] = $request->file('file_upload')->store('materi/files', 'public');
        }

        $materi->update($data);

        return response()->json([
            'message' => 'Materi berhasil diupdate',
            'data' => $materi
        ]);
    }

    public function destroy($id)
    {
        $materi = MateriEdukasi::findOrFail($id);
        
        if ($materi->thumbnail) {
            Storage::disk('public')->delete($materi->thumbnail);
        }
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();

        return response()->json([
            'message' => 'Materi berhasil dihapus'
        ]);
    }
}
