<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EdukasiLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EdukasiLocationController extends Controller
{
    public function index()
    {
        $locations = EdukasiLocation::latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $locations
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:SD,SMP,SMA/SMK,Perguruan Tinggi,Komunitas',
            'year' => 'nullable|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $activities = [];
        if ($request->has('activities')) {
            $parsedActs = json_decode($request->activities, true);
            if (is_array($parsedActs)) {
                foreach ($parsedActs as $index => $act) {
                    $photoPaths = [];
                    // Handle photo uploads for this specific activity
                    if ($request->hasFile("activities_photos_{$index}")) {
                        foreach ($request->file("activities_photos_{$index}") as $photo) {
                            $photoPaths[] = '/storage/' . $photo->store('edukasi_photos', 'public');
                        }
                    }
                    $act['photos'] = $photoPaths;
                    $activities[] = $act;
                }
            }
        }

        $location = EdukasiLocation::create([
            'name' => $request->name,
            'category' => $request->category,
            'year' => $request->year,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'description' => $request->description,
            'activities' => $activities,
            'photos' => [] // Kept for backward compatibility or general photos
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil ditambahkan',
            'data' => $location
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $location = EdukasiLocation::find($id);
        if (!$location) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:SD,SMP,SMA/SMK,Perguruan Tinggi,Komunitas',
            'year' => 'nullable|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $activities = $location->activities ?? [];
        if ($request->has('activities')) {
            $parsedActs = json_decode($request->activities, true);
            if (is_array($parsedActs)) {
                $activities = [];
                foreach ($parsedActs as $index => $act) {
                    // Keep existing photos if any
                    $photoPaths = $act['photos'] ?? [];
                    
                    // Add new uploaded photos for this activity
                    if ($request->hasFile("activities_photos_{$index}")) {
                        foreach ($request->file("activities_photos_{$index}") as $photo) {
                            $photoPaths[] = '/storage/' . $photo->store('edukasi_photos', 'public');
                        }
                    }
                    $act['photos'] = $photoPaths;
                    $activities[] = $act;
                }
            }
        }

        $location->update([
            'name' => $request->name,
            'category' => $request->category,
            'year' => $request->year,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'description' => $request->description,
            'activities' => $activities,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil diperbarui',
            'data' => $location
        ]);
    }

    public function destroy($id)
    {
        $location = EdukasiLocation::find($id);
        if (!$location) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        if (!empty($location->photos)) {
            foreach ($location->photos as $photo) {
                $relativePath = str_replace('/storage/', '', $photo);
                Storage::disk('public')->delete($relativePath);
            }
        }

        $location->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil dihapus'
        ]);
    }
}
