<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::orderBy('published_at', 'desc');
        
        if (!$request->has('all')) {
            $query->take(5);   
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $query->get()       
        ]);
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return response()->json([
            'status' => 'success',
            'data' => $article
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . time();
        
        $images = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('articles', 'public');
                $images[] = url('storage/' . $path);
            }
        }
        
        $validated['image'] = $images;
        
        $article = Article::create($validated);
        return response()->json(['status' => 'success', 'data' => $article]);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($article->title !== $validated['title']) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . time();
        }

        $images = $request->input('existing_images', []);
        
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('articles', 'public');
                $images[] = url('storage/' . $path);
            }
        }

        $validated['image'] = $images;

        $article->update($validated);
        return response()->json(['status' => 'success', 'data' => $article]);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(['status' => 'success', 'message' => 'Article deleted']);
    }
}
