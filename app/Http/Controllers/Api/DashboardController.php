<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanEdukasi;
use App\Models\MateriEdukasi;
use App\Models\News;
use App\Models\PageView;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function trackVisit(Request $request)
    {
        try {
            PageView::create([
                'ip_address' => $request->ip(),
                'page_url' => $request->input('page', '/'),
                'visited_date' => Carbon::today()->toDateString(),
            ]);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $hari = $request->query('hari');
        $bulan = $request->query('bulan', date('m'));
        $tahun = $request->query('tahun', date('Y'));

        // Query Pengajuan Edukasi dengan Filter
        $qPengajuan = PengajuanEdukasi::query();
        if ($tahun) $qPengajuan->whereYear('created_at', $tahun);
        if ($bulan) $qPengajuan->whereMonth('created_at', $bulan);
        if ($hari) $qPengajuan->whereDay('created_at', $hari);
        $pengajuanCount = $qPengajuan->count();

        // Query Materi Edukasi dengan Filter
        $qMateri = MateriEdukasi::query();
        if ($tahun) $qMateri->whereYear('created_at', $tahun);
        if ($bulan) $qMateri->whereMonth('created_at', $bulan);
        if ($hari) $qMateri->whereDay('created_at', $hari);
        $materiCount = $qMateri->count();

        // Query Berita dengan Filter
        $qBerita = News::query();
        if ($tahun) $qBerita->whereYear('created_at', $tahun);
        if ($bulan) $qBerita->whereMonth('created_at', $bulan);
        if ($hari) $qBerita->whereDay('created_at', $hari);
        $beritaCount = $qBerita->count();

        // Kunjungan Web Real-Time 100% dari Database (Tabel page_views)
        $qPageView = PageView::query();
        if ($tahun) $qPageView->whereYear('visited_date', $tahun);
        if ($bulan) $qPageView->whereMonth('visited_date', $bulan);
        if ($hari) $qPageView->whereDay('visited_date', $hari);
        $totalVisits = $qPageView->count();

        // Tren Pengajuan Edukasi (per bulan)
        $trenPengajuan = [];
        for ($i = 1; $i <= 12; $i++) {
            $trenPengajuan[] = PengajuanEdukasi::whereYear('created_at', $tahun ?: date('Y'))
                                ->whereMonth('created_at', $i)
                                ->count();
        }

        // Kunjungan Web Per Hari (Bar Chart) - 100% Data Asli DB
        $selectedBulanInt = (int)($bulan ?: date('m'));
        $selectedTahunInt = (int)($tahun ?: date('Y'));
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedBulanInt, $selectedTahunInt);

        // Agregasi views asli dari database per tanggal
        $dailyDbViews = PageView::whereYear('visited_date', $selectedTahunInt)
            ->whereMonth('visited_date', $selectedBulanInt)
            ->selectRaw('DAY(visited_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

        $kunjunganHarian = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $realCount = isset($dailyDbViews[$d]) ? (int)$dailyDbViews[$d] : 0;

            $kunjunganHarian[] = [
                'day' => $d,
                'count' => $realCount,
                'real_count' => $realCount
            ];
        }

        // Proporsi Kategori Edukasi
        $proporsiMateri = \DB::table('materi_edukasis')
            ->join('kategori_materis', 'materi_edukasis.kategori_materi_id', '=', 'kategori_materis.id')
            ->select('kategori_materis.nama as name', \DB::raw('count(*) as value'))
            ->groupBy('kategori_materis.id', 'kategori_materis.nama')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'pengajuan_kunjungan' => $pengajuanCount,
                'konten_edukasi' => $materiCount,
                'berita_aktif' => $beritaCount,
                'kunjungan_web' => $totalVisits,
                'tren_pengajuan' => $trenPengajuan,
                'kunjungan_harian' => $kunjunganHarian,
                'proporsi_materi' => $proporsiMateri
            ]
        ]);
    }
}
