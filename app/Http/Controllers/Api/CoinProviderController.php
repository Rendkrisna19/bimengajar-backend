<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinProvider;
use Illuminate\Http\Request;

class CoinProviderController extends Controller
{
    /**
     * List all active providers, optionally filtered by radius.
     * Query params: lat, lng, radius (km), all (admin)
     */
    public function index(Request $request)
    {
        $query = CoinProvider::query();

        if (!$request->has('all')) {
            $query->where('is_active', true);
        }

        // Radius filtering using Haversine formula
        if ($request->filled('lat') && $request->filled('lng') && $request->filled('radius')) {
            $lat    = (float) $request->lat;
            $lng    = (float) $request->lng;
            $radius = (float) $request->radius;

            // Bounding box approximation to speed up query
            $latRadius = $radius / 111.045;
            $lngRadius = $radius / (111.045 * cos(deg2rad($lat)));

            $query->whereBetween('latitude', [$lat - $latRadius, $lat + $latRadius])
                  ->whereBetween('longitude', [$lng - $lngRadius, $lng + $lngRadius])
                  ->selectRaw("coin_providers.*, 
                      (6371 * acos(
                          cos(radians(?)) * cos(radians(latitude)) 
                          * cos(radians(longitude) - radians(?)) 
                          + sin(radians(?)) * sin(radians(latitude))
                      )) AS distance", [$lat, $lng, $lat])
                  ->havingRaw("distance < ?", [$radius])
                  ->orderByRaw("distance ASC");
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get(),
        ]);
    }

    /**
     * Register a new coin provider (public).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'user_type'         => 'required|in:perorangan,umkm,instansi',
            'whatsapp'          => 'required|string|max:20',
            'address'           => 'required|string',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'total_coins'       => 'required|integer|min:0',
            'denominations'     => 'required|array|min:1',
            'denominations.*'   => 'in:100,200,500,1000',
            'operational_hours' => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
        ]);

        $provider = CoinProvider::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Lokasi berhasil didaftarkan!',
            'data'    => $provider,
        ], 201);
    }

    /**
     * Show single provider.
     */
    public function show($id)
    {
        $provider = CoinProvider::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $provider]);
    }

    /**
     * Update (admin only).
     */
    public function update(Request $request, $id)
    {
        $provider = CoinProvider::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'user_type'         => 'required|in:perorangan,umkm,instansi',
            'whatsapp'          => 'required|string|max:20',
            'address'           => 'required|string',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'total_coins'       => 'required|integer|min:0',
            'denominations'     => 'required|array|min:1',
            'denominations.*'   => 'in:100,200,500,1000',
            'operational_hours' => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
            'is_active'         => 'boolean',
        ]);

        $provider->update($validated);

        return response()->json(['status' => 'success', 'data' => $provider]);
    }

    /**
     * Toggle active status (admin only).
     */
    public function toggle($id)
    {
        $provider = CoinProvider::findOrFail($id);
        $provider->is_active = !$provider->is_active;
        $provider->save();

        return response()->json([
            'status'    => 'success',
            'is_active' => $provider->is_active,
        ]);
    }

    /**
     * Delete (admin only).
     */
    public function destroy($id)
    {
        CoinProvider::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data dihapus.']);
    }
}
