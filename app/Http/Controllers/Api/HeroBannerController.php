<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeroBannerController extends Controller
{
    public function index(Request $request)
    {
        $query = HeroBanner::query();

        if (!$request->boolean('all')) {
            $query->where('is_active', true);
        }

        $banners = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $banners
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle' => 'required|string',
            'subtitle_en' => 'nullable|string',
            'button_primary_text' => 'nullable|string|max:255',
            'button_primary_text_en' => 'nullable|string|max:255',
            'button_primary_url' => 'nullable|string|max:255',
            'button_secondary_text' => 'nullable|string|max:255',
            'button_secondary_text_en' => 'nullable|string|max:255',
            'button_secondary_url' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg,mp4,webm,ogg,mov,quicktime|max:51200',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $path = ImageUploadService::uploadAsWebp($request->file('image'), 'hero_banners', 85, 1920);
            $data['image'] = $path;
        }

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $data['order'] = $request->input('order', 0);

        $banner = HeroBanner::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Hero Banner berhasil ditambahkan',
            'data' => $banner
        ], 201);
    }

    public function show($id)
    {
        $banner = HeroBanner::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $banner
        ]);
    }

    public function update(Request $request, $id)
    {
        $banner = HeroBanner::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'button_primary_text' => 'nullable|string|max:255',
            'button_primary_text_en' => 'nullable|string|max:255',
            'button_primary_url' => 'nullable|string|max:255',
            'button_secondary_text' => 'nullable|string|max:255',
            'button_secondary_text_en' => 'nullable|string|max:255',
            'button_secondary_url' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,webp,svg,mp4,webm,ogg,mov,quicktime|max:51200',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($banner->image && !filter_var($banner->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($banner->image);
            }
            $path = ImageUploadService::uploadAsWebp($request->file('image'), 'hero_banners', 85, 1920);
            $data['image'] = $path;
        }

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        $banner->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Hero Banner berhasil diperbarui',
            'data' => $banner
        ]);
    }

    public function destroy($id)
    {
        $banner = HeroBanner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hero Banner tidak ditemukan'
            ], 404);
        }

        if ($banner->image && !filter_var($banner->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hero Banner berhasil dihapus'
        ]);
    }

    public function toggleActive($id)
    {
        $banner = HeroBanner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hero Banner tidak ditemukan'
            ], 404);
        }

        $banner->is_active = !$banner->is_active;
        $banner->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status aktif berhasil diubah',
            'data' => $banner
        ]);
    }
}
