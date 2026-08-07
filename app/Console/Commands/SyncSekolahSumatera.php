<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SekolahSyncService;

class SyncSekolahSumatera extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:sekolah-sumatera';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data sekolah Pulau Sumatera dari API resmi Kemendikdasmen';

    /**
     * Execute the console command.
     */
    public function handle(SekolahSyncService $syncService)
    {
        $this->info('Memulai proses sinkronisasi sekolah untuk Pulau Sumatera...');
        $this->line('Target API: ' . env('API_SEKOLAH_URL'));

        try {
            $totalSynced = $syncService->syncSumatera();
            
            if ($totalSynced > 0) {
                $this->info("Berhasil! {$totalSynced} data sekolah tersinkronisasi ke tabel edukasi_locations.");
            } else {
                $this->warn('Selesai, namun tidak ada data baru atau valid yang berhasil disinkronisasi.');
            }
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
        }
    }
}
