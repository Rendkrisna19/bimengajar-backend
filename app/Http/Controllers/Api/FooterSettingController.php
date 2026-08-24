<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;

class FooterSettingController extends Controller
{
    /**
     * Get footer settings (Public & Admin API)
     */
    public function index()
    {
        $setting = FooterSetting::first();

        if (!$setting) {
            $setting = FooterSetting::create([
                'deskripsi' => 'Mewujudkan masyarakat yang Cinta, Bangga, dan Paham Rupiah melalui edukasi yang berkelanjutan.',
                'alamat' => "Jl. H. Adam Malik No. 1, Pematangsiantar, Sumatera Utara",
                'telepon' => '(0622) 22100',
                'email' => 'pematangsiantar@bi.go.id',
                'instagram_url' => 'https://instagram.com/bank_indonesia_pematangsiantar',
                'youtube_url' => 'https://youtube.com',
                'facebook_url' => 'https://facebook.com',
                'twitter_url' => 'https://x.com',
                'tiktok_url' => 'https://tiktok.com',
                'copyright_text' => 'Bank Indonesia Pematangsiantar. Hak Cipta Dilindungi.',
            ]);
        }

        return response()->json([
            'message' => 'Berhasil mengambil pengaturan footer',
            'data' => $setting
        ]);
    }

    /**
     * Update footer settings (Admin only)
     */
    public function update(Request $request)
    {
        $request->validate([
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'youtube_url' => 'nullable|string',
            'facebook_url' => 'nullable|string',
            'twitter_url' => 'nullable|string',
            'tiktok_url' => 'nullable|string',
            'copyright_text' => 'nullable|string',
        ]);

        $setting = FooterSetting::first();

        if (!$setting) {
            $setting = new FooterSetting();
        }

        $setting->fill($request->only([
            'deskripsi',
            'alamat',
            'telepon',
            'email',
            'instagram_url',
            'youtube_url',
            'facebook_url',
            'twitter_url',
            'tiktok_url',
            'copyright_text',
        ]));

        $setting->save();

        return response()->json([
            'message' => 'Pengaturan footer berhasil diperbarui',
            'data' => $setting
        ]);
    }
}
