<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Mitra::select('id', 'logo', 'singkatan', 'nama_lengkap', 'kategori', 'lokasi', 'no_wa', 'deskripsi', 'is_active', 'status_persetujuan', 'created_at');

        $user = $request->user('sanctum');
        // If public request, only show active & accepted
        if (!$user || $user->role !== 'admin') {
            $query->where('is_active', 1)->where('status_persetujuan', 'diterima');
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('singkatan', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Sort descending
        $query->orderBy('id', 'desc');

        if ($request->has('all')) {
            return response()->json([
                'status' => 'success',
                'data' => $query->get()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->paginate(6)
        ]);
    }

    public function show($id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['status' => 'error', 'message' => 'Mitra not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $mitra]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'singkatan' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        }

        $data = $request->only(['singkatan', 'nama_lengkap', 'kategori', 'lokasi', 'deskripsi', 'no_wa']);
        
        $user = $request->user('sanctum');
        if ($user && $user->role === 'admin') {
            $data['status_persetujuan'] = 'diterima';
            $data['is_active'] = true;
        } else {
            $data['status_persetujuan'] = 'menunggu';
            $data['is_active'] = false;
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('mitra', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $mitra = Mitra::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Mitra submitted successfully',
            'data' => $mitra
        ]);
    }

    public function update(Request $request, $id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['status' => 'error', 'message' => 'Mitra not found'], 404);
        }

        $data = $request->except(['logo']);

        if ($request->hasFile('logo')) {
            if ($mitra->logo && strpos($mitra->logo, '/storage/') === 0) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $mitra->logo));
            }
            $path = $request->file('logo')->store('mitra', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $mitra->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Mitra updated successfully',
            'data' => $mitra
        ]);
    }

    public function destroy($id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['status' => 'error', 'message' => 'Mitra not found'], 404);
        }

        if ($mitra->logo && strpos($mitra->logo, '/storage/') === 0) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $mitra->logo));
        }

        $mitra->delete();

        return response()->json(['status' => 'success', 'message' => 'Mitra deleted successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['status' => 'error', 'message' => 'Mitra not found'], 404);
        }

        if ($request->has('status_persetujuan')) {
            $mitra->status_persetujuan = $request->status_persetujuan;
        }
        if ($request->exists('is_active')) {
            $mitra->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }
        $mitra->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Mitra status updated',
            'data' => $mitra
        ]);
    }
}
