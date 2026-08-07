<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EdukasiLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EdukasiLocationController extends Controller
{
    public function index(Request $request)
    {
        $query = EdukasiLocation::query();

        // Filter by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
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
        // Validate sort key to prevent SQL injection or errors
        $allowedSortKeys = ['name', 'category', 'year', 'created_at'];
        if (in_array($sortKey, $allowedSortKeys)) {
            $query->orderBy($sortKey, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $locations = $query->paginate($perPage);

        // Fetch category counts for the frontend dashboard
        $counts = [
            'SD' => EdukasiLocation::where('category', 'SD')->count(),
            'SMP' => EdukasiLocation::where('category', 'SMP')->count(),
            'SMA_SMK' => EdukasiLocation::where('category', 'SMA/SMK')->count(),
            'PT' => EdukasiLocation::where('category', 'Perguruan Tinggi')->count(),
            'Komunitas' => EdukasiLocation::where('category', 'Komunitas')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $locations,
            'summary' => $counts
        ]);
    }

    /**
     * Search Live Kemdikbud (External API)
     *
     * Endpoint ini digunakan untuk mencari data sekolah secara live dari API Vercel (sumber open data Kemdikbud).
     * Hasil pencarian dibatasi HANYA untuk sekolah yang berada di Pulau Sumatera.
     * Digunakan oleh fitur autocomplete di frontend saat pengguna mengetikkan nama sekolah pada form tambah lokasi peta.
     *
     * @param Request $request
     * @queryParam search string required Kata kunci nama sekolah yang ingin dicari (minimal 3 karakter disarankan). Example: SMAN 1
     * @response array{status: string, data: array<int, array{name: string, category: string, province: string, address: string, latitude: string, longitude: string}>}
     */
    public function searchExternal(Request $request)
    {
        $search = $request->input('search');
        $selectedProvince = $request->input('province'); // e.g. "Sumatera Utara"

        if (!$search) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        try {
            $url = 'https://api-sekolah-indonesia.vercel.app/sekolah/s?sekolah=' . urlencode($search) . '&perPage=200';
            
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            
            if ($response->successful() && isset($response->json()['dataSekolah'])) {
                $externalData = $response->json()['dataSekolah'];
                
                // Allowed provinces (Sumatera)
                $sumateraProvinces = [
                    'Prov. Aceh', 'Prov. Sumatera Utara', 'Prov. Sumatera Barat', 
                    'Prov. Riau', 'Prov. Jambi', 'Prov. Sumatera Selatan', 
                    'Prov. Bengkulu', 'Prov. Lampung', 'Prov. Kepulauan Bangka Belitung', 
                    'Prov. Kepulauan Riau'
                ];

                $mappedData = [];
                foreach ($externalData as $item) {
                    $provinsi = trim($item['propinsi'] ?? '');
                    
                    // If user selected a specific province, force filter it
                    if ($selectedProvince && $selectedProvince !== 'Semua') {
                        // The external API returns province with "Prov. " prefix. e.g "Prov. Sumatera Utara"
                        if (!str_contains(strtolower($provinsi), strtolower($selectedProvince))) {
                            continue;
                        }
                    } else {
                        // Otherwise, fallback to restricting it to only Sumatera
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
                    'data' => array_slice($mappedData, 0, 10) // Return max 10 results for autocomplete
                ]);
            }

            return response()->json(['status' => 'success', 'data' => []]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('External API search failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'data' => [], 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get Total External Schools (Sumatera Only Approximation or Global)
     *
     * Endpoint ini digunakan untuk melihat total jumlah data sekolah yang tersedia di API eksternal Vercel.
     *
     * @response array{status: string, total: int}
     */
    public function getExternalCount()
    {
        try {
            // Kita bisa mengambil 1 perPage saja karena API Vercel memberikan field 'total_data' di response metadata-nya
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
