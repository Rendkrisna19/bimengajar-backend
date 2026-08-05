<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::select('id', 'title', 'slug', 'author', 'image', 'description', 'category', 'published_at', 'created_at', 'updated_at')
            ->orderBy('published_at', 'desc');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $news = $request->has('all') ? $query->get() : $query->paginate(10);

        return response()->json(['status' => 'success', 'data' => $news]);
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return response()->json(['status' => 'success', 'data' => $news]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'content'  => 'nullable|string',
            'category' => 'nullable|in:berita,dokumentasi',
            'published_at' => 'nullable|date',
            'new_images.*' => 'nullable|image|max:5120',
        ]);

        $imagePaths = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('news', 'public');
                $imagePaths[] = config('app.url') . Storage::url($path);
            }
        }

        $news = News::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'author'      => $request->author ?? 'Admin BI',
            'description' => $request->description,
            'content'     => $request->content,
            'category'    => $request->category ?? 'berita',
            'image'       => $imagePaths,
            'published_at' => $request->published_at ?? now(),
        ]);

        return response()->json(['status' => 'success', 'data' => $news], 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title'    => 'sometimes|required|string|max:255',
            'author'   => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'content'  => 'nullable|string',
            'category' => 'nullable|in:berita,dokumentasi',
            'published_at' => 'nullable|date',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'new_images.*' => 'nullable|image|max:5120',
        ]);

        $imagePaths = $request->input('existing_images', []);
        
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('news', 'public');
                $imagePaths[] = config('app.url') . Storage::url($path);
            }
        }

        $news->update([
            'title'       => $request->title ?? $news->title,
            'slug'        => $request->title ? Str::slug($request->title) . '-' . time() : $news->slug,
            'author'      => $request->author ?? $news->author,
            'description' => $request->description ?? $news->description,
            'content'     => $request->content ?? $news->content,
            'category'    => $request->category ?? $news->category,
            'image'       => $imagePaths,
            'published_at' => $request->published_at ?? $news->published_at,
        ]);

        return response()->json(['status' => 'success', 'data' => $news]);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return response()->json(['status' => 'success', 'message' => 'Berita berhasil dihapus']);
    }
}
