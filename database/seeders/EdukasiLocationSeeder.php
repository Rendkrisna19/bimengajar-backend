<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EdukasiLocation;

class EdukasiLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'SMA Negeri 1 Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2025, 'latitude' => 2.9555, 'longitude' => 99.0655],
            ['name' => 'SMA Negeri 2 Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2026, 'latitude' => 2.9620, 'longitude' => 99.0600],
            ['name' => 'SMA Negeri 3 Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2025, 'latitude' => 2.9580, 'longitude' => 99.0710],
            ['name' => 'SMA Negeri 4 Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2026, 'latitude' => 2.9650, 'longitude' => 99.0550],
            ['name' => 'SMA Budi Mulia Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2025, 'latitude' => 2.9700, 'longitude' => 99.0500],
            ['name' => 'SMK Negeri 1 Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2026, 'latitude' => 2.9615, 'longitude' => 99.0515],
            ['name' => 'SMK Negeri 2 Pematang Siantar', 'category' => 'SMA/SMK', 'year' => 2025, 'latitude' => 2.9520, 'longitude' => 99.0680],
            
            ['name' => 'SMP Negeri 1 Pematang Siantar', 'category' => 'SMP', 'year' => 2025, 'latitude' => 2.9575, 'longitude' => 99.0630],
            ['name' => 'SMP Negeri 4 Pematang Siantar', 'category' => 'SMP', 'year' => 2026, 'latitude' => 2.9645, 'longitude' => 99.0580],
            ['name' => 'SMP Budi Mulia Pematang Siantar', 'category' => 'SMP', 'year' => 2025, 'latitude' => 2.9690, 'longitude' => 99.0510],
            ['name' => 'SMP Cinta Rakyat 1 Pematang Siantar', 'category' => 'SMP', 'year' => 2026, 'latitude' => 2.9540, 'longitude' => 99.0610],
            
            ['name' => 'SD Negeri 122332 Pematang Siantar', 'category' => 'SD', 'year' => 2025, 'latitude' => 2.9600, 'longitude' => 99.0660],
            ['name' => 'SD Cinta Rakyat 1 Pematang Siantar', 'category' => 'SD', 'year' => 2026, 'latitude' => 2.9545, 'longitude' => 99.0615],
            ['name' => 'SD Budi Mulia Pematang Siantar', 'category' => 'SD', 'year' => 2025, 'latitude' => 2.9685, 'longitude' => 99.0520],
            ['name' => 'SD Kalam Kudus Pematang Siantar', 'category' => 'SD', 'year' => 2026, 'latitude' => 2.9510, 'longitude' => 99.0690],
            
            ['name' => 'Universitas Simalungun (USI)', 'category' => 'Perguruan Tinggi', 'year' => 2025, 'latitude' => 2.9450, 'longitude' => 99.0750],
            ['name' => 'Universitas HKBP Nommensen Pematangsiantar', 'category' => 'Perguruan Tinggi', 'year' => 2026, 'latitude' => 2.9590, 'longitude' => 99.0620],
            ['name' => 'STIE Sultan Agung Pematang Siantar', 'category' => 'Perguruan Tinggi', 'year' => 2025, 'latitude' => 2.9660, 'longitude' => 99.0640],
            
            ['name' => 'Komunitas GenBI Siantar', 'category' => 'Komunitas', 'year' => 2026, 'latitude' => 2.9610, 'longitude' => 99.0625],
            ['name' => 'Komunitas Guru Penggerak Siantar', 'category' => 'Komunitas', 'year' => 2025, 'latitude' => 2.9560, 'longitude' => 99.0590],
        ];

        foreach ($locations as $loc) {
            EdukasiLocation::updateOrCreate(
                ['name' => $loc['name']],
                [
                    'category' => $loc['category'],
                    'year' => $loc['year'],
                    'latitude' => $loc['latitude'],
                    'longitude' => $loc['longitude'],
                    'address' => 'Kota Pematang Siantar',
                    'province' => 'Sumatera Utara',
                    'description' => 'Telah teredukasi dalam program BI Mengajar Pematang Siantar.',
                    'activities' => [],
                    'photos' => []
                ]
            );
        }
    }
}
