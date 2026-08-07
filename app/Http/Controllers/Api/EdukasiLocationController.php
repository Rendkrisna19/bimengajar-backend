<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EdukasiLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class EdukasiLocationController extends Controller
{
    public function index(Request $request)
    {
        $query = EdukasiLocation::query();

        // Filter by name or address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Filter by year
        if ($request->filled('year') && $request->year !== 'Semua') {
            $query->where('year', $request->year);
        }

        // Filter by province
        if ($request->filled('province') && $request->province !== 'Semua') {
            $query->where('province', $request->province);
        }

        // Sorting
        $sortKey = $request->input('sort_key', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSortKeys = ['name', 'category', 'year', 'created_at'];
        if (in_array($sortKey, $allowedSortKeys)) {
            $query->orderBy($sortKey, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        // Pagination - Default to 10 items per page
        $perPage = (int) $request->input('per_page', 10);
        $locations = $query->paginate($perPage);

        // Optimized Cache for summary counts - single query cached for 5 mins
        $summary = Cache::remember('edukasi_summary_counts', 300, function() {
            $raw = EdukasiLocation::selectRaw('category, COUNT(*) as total')
                ->groupBy('category')
                ->pluck('total', 'category');
            return [
                'SD' => $raw['SD'] ?? 0,
                'SMP' => $raw['SMP'] ?? 0,
                'SMA_SMK' => $raw['SMA/SMK'] ?? 0,
                'PT' => $raw['Perguruan Tinggi'] ?? 0,
                'Komunitas' => $raw['Komunitas'] ?? 0,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $locations,
            'summary' => $summary
        ]);
    }

    public function searchExternal(Request $request)
    {
        $search = $request->input('search');
        $selectedProvince = $request->input('province');

        if (!$search) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        try {
            $url = 'https://api-sekolah-indonesia.vercel.app/sekolah/s?sekolah=' . urlencode($search) . '&perPage=200';
            
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            
            if ($response->successful() && isset($response->json()['dataSekolah'])) {
                $externalData = $response->json()['dataSekolah'];
                
                $sumateraProvinces = [
                    'Prov. Aceh', 'Prov. Sumatera Utara', 'Prov. Sumatera Barat', 
                    'Prov. Riau', 'Prov. Jambi', 'Prov. Sumatera Selatan', 
                    'Prov. Bengkulu', 'Prov. Lampung', 'Prov. Kepulauan Bangka Belitung', 
                    'Prov. Kepulauan Riau'
                ];

                $mappedData = [];
                foreach ($externalData as $item) {
                    $provinsi = trim($item['propinsi'] ?? '');
                    
                    if ($selectedProvince && $selectedProvince !== 'Semua') {
                        if (!str_contains(strtolower($provinsi), strtolower($selectedProvince))) {
                            continue;
                        }
                    } else {
                        if (!in_array($provinsi, $sumateraProvinces)) {
                            continue;
                        }
                    }

                    $mappedData[] = [
                        'name' => $item['sekolah'] ?? '',
                        'category' => $item['bentuk'] ?? 'SD',
                        'province' => $provinsi,
                        'address' => $item['alamat_jalan'] ?? '',
                        'latitude' => $item['lintang'] ?? '0',
                        'longitude' => $item['bujur'] ?? '0',
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'data' => array_slice($mappedData, 0, 10)
                ]);
            }

            return response()->json(['status' => 'success', 'data' => []]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('External API search failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
        }
    }

    public function getExternalCount()
    {
        try {
            $url = 'https://api-sekolah-indonesia.vercel.app/sekolah?page=1&perPage=1';
            
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            
            if ($response->successful() && isset($response->json()['total_data'])) {
                return response()->json([
                    'status' => 'success',
                    'total_data' => $response->json()['total_data']
                ]);
            }

            return response()->json(['status' => 'success', 'total_data' => 0]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'total_data' => 0, 'message' => $e->getMessage()]);
        }
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
            'photos' => []
        ]);

        Cache::forget('edukasi_summary_counts');

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
                    $photoPaths = $act['photos'] ?? [];
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

        Cache::forget('edukasi_summary_counts');

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
        Cache::forget('edukasi_summary_counts');

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil dihapus'
        ]);
    }
}
