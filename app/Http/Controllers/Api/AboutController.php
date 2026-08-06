<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = Cache::remember('abouts_all', 3600, function () {
            return About::all()->keyBy('type');
        });
        return response()->json([
            'status' => 'success',
            'data' => $abouts
        ]);
    }

    public function show($type)
    {
        $about = About::where('type', $type)->first();
        if (!$about) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $about]);
    }

    public function update(Request $request, $type)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $about = About::firstOrCreate(
            ['type' => $type],
            ['title' => 'Default Title', 'content' => 'Default content']
        );

        $about->title = $request->title;
        $about->title_en = $request->title_en ?? $about->title_en;
        $about->content = $request->content;
        $about->content_en = $request->content_en ?? $about->content_en;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($about->image) {
                $oldPath = str_replace(url('storage/'), '', $about->image);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('abouts', 'public');
            $about->image = url('storage/' . $path);
        }

        $about->save();

        Cache::forget('abouts_all');

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate',
            'data' => $about
        ]);
    }
}
