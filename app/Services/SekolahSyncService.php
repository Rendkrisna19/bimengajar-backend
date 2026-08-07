<?php

namespace App\Services;

use App\Models\EdukasiLocation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SekolahSyncService
{
    // Kode wilayah Dapodik untuk Pulau Sumatera
    protected array $sumateraProvinces = [
        '060000' => 'Aceh',
        '070000' => 'Sumatera Utara',
        '080000' => 'Sumatera Barat',
        '090000' => 'Riau',
        '100000' => 'Jambi',
        '110000' => 'Sumatera Selatan',
        '120000' => 'Lampung',
        '260000' => 'Bengkulu',
        '290000' => 'Bangka Belitung',
        '310000' => 'Kepulauan Riau'
    ];

    public function syncSumatera(): int
    {
        $apiUrl = env('API_SEKOLAH_URL');
        
        if (empty($apiUrl)) {
            throw new \Exception('API_SEKOLAH_URL belum diatur di file .env');
        }

        // $apiSecret = env('API_SEKOLAH_SECRET'); // Akses token dari env sesuai PRD

        $totalSynced = 0;

        foreach ($this->sumateraProvinces as $kodeWilayah => $namaProvinsi) {
            try {
                // Dapodik API menggunakan GET parameter untuk mst_kode_wilayah
                $response = Http::timeout(60)->get($apiUrl, [
                    'mst_kode_wilayah' => $kodeWilayah,
                    'bentuk_pendidikan_id' => 'all' 
                ]);

                if ($response->successful()) {
                    $schools = $response->json();
                    
                    // Terkadang list sekolah dibungkus di dalam key 'data' atau 'sekolahs'
                    if (isset($schools['data'])) {
                        $schools = $schools['data'];
                    } elseif (isset($schools['sekolahs'])) {
                        $schools = $schools['sekolahs'];
                    }

                    if (is_array($schools)) {
                        foreach ($schools as $school) {
                            // Abaikan jika data tidak memiliki koordinat
                            if (empty($school['lintang']) || empty($school['bujur'])) continue;

                            $namaSekolah = $school['nama'] ?? $school['nama_sekolah'] ?? '';
                            if (empty($namaSekolah)) continue;

                            $category = $this->mapBentukPendidikanToCategory($school['bentuk_pendidikan'] ?? $school['jenjang'] ?? '');

                            // Gunakan updateOrCreate untuk mencegah duplikasi (Cek berdasarkan nama)
                            EdukasiLocation::updateOrCreate(
                                ['name' => $namaSekolah],
                                [
                                    'category' => $category,
                                    'latitude' => $school['lintang'],
                                    'longitude' => $school['bujur'],
                                    'address' => $school['alamat_jalan'] ?? $namaProvinsi,
                                    'province' => $namaProvinsi,
                                    'year' => date('Y'),
                                    // Cegah penimpaan pada data activities jika sudah ada isinya (menggunakan DB::raw)
                                    'activities' => DB::raw('COALESCE(activities, "[]")') 
                                ]
                            );
                            $totalSynced++;
                        }
                    }
                } else {
                    Log::error("Gagal sinkronisasi provinsi {$namaProvinsi}: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Exception saat sinkronisasi {$namaProvinsi}: " . $e->getMessage());
            }
        }

        return $totalSynced;
    }

    private function mapBentukPendidikanToCategory(string $bentuk): string
    {
        $bentuk = strtoupper($bentuk);
        if (str_contains($bentuk, 'SD') || str_contains($bentuk, 'MI')) return 'SD';
        if (str_contains($bentuk, 'SMP') || str_contains($bentuk, 'MTS')) return 'SMP';
        if (str_contains($bentuk, 'SMA') || str_contains($bentuk, 'SMK') || str_contains($bentuk, 'MA')) return 'SMA/SMK';
        if (str_contains($bentuk, 'UNIV') || str_contains($bentuk, 'KAMPUS') || str_contains($bentuk, 'POLITEKNIK')) return 'Perguruan Tinggi';
        
        // Fallback default
        return 'Komunitas';
    }
}
