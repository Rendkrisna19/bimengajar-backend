<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Status Pengajuan Kegiatan - Plat-BK</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #1e293b;">
    @php
        $status = strtolower($pengajuan->status ?? 'pending');
        
        $config = [
            'disetujui' => [
                'title' => 'DISETUJUI',
                'badge_bg' => '#dcfce7',
                'badge_color' => '#15803d',
                'badge_border' => '#86efac',
                'message' => 'Selamat! Pengajuan kegiatan edukasi yang Anda ajukan telah disetujui oleh Bank Indonesia Pematangsiantar. Tim kami akan segera berkoordinasi lebih lanjut melalui kontak PIC terkait persiapan pelaksanaan.',
                'alert_bg' => '#f0fdf4',
                'alert_border' => '#22c55e',
                'alert_text' => '#166534',
            ],
            'konfirmasi' => [
                'title' => 'TERKONFIRMASI & DISETUJUI',
                'badge_bg' => '#dcfce7',
                'badge_color' => '#15803d',
                'badge_border' => '#86efac',
                'message' => 'Selamat! Pengajuan kegiatan edukasi Anda telah terkonfirmasi dan disetujui untuk dilaksanakan sesuai jadwal yang telah disepakati.',
                'alert_bg' => '#f0fdf4',
                'alert_border' => '#22c55e',
                'alert_text' => '#166534',
            ],
            'ditolak' => [
                'title' => 'BELUM DAPAT DISETUJUI',
                'badge_bg' => '#fee2e2',
                'badge_color' => '#b91c1c',
                'badge_border' => '#fca5a5',
                'message' => 'Mohon maaf, saat ini pengajuan kegiatan edukasi Anda belum dapat disetujui. Silakan periksa catatan dari Administrator di bawah ini untuk informasi lebih lanjut atau Anda dapat mengajukan kembali di waktu berikutnya.',
                'alert_bg' => '#fef2f2',
                'alert_border' => '#ef4444',
                'alert_text' => '#991b1b',
            ],
            'penjadwalan' => [
                'title' => 'DALAM TAHAP PENJADWALAN',
                'badge_bg' => '#f3e8ff',
                'badge_color' => '#7e22ce',
                'badge_border' => '#d8b4fe',
                'message' => 'Pengajuan Anda telah lolos tahap awal dan saat ini sedang dalam proses penyelarasan jadwal bersama narasumber/fasilitator Bank Indonesia.',
                'alert_bg' => '#faf5ff',
                'alert_border' => '#a855f7',
                'alert_text' => '#6b21a8',
            ],
            'verifikasi' => [
                'title' => 'DALAM TAHAP VERIFIKASI',
                'badge_bg' => '#dbeafe',
                'badge_color' => '#1d4ed8',
                'badge_border' => '#93c5fd',
                'message' => 'Pengajuan kegiatan edukasi Anda saat ini sedang diperiksa dan diverifikasi oleh tim Administrator Bank Indonesia Pematangsiantar.',
                'alert_bg' => '#eff6ff',
                'alert_border' => '#3b82f6',
                'alert_text' => '#1e40af',
            ],
            'selesai' => [
                'title' => 'KEGIATAN SELESAI',
                'badge_bg' => '#ccfbf1',
                'badge_color' => '#0f766e',
                'badge_border' => '#5eead4',
                'message' => 'Kegiatan edukasi bersama Bank Indonesia telah selesai dilaksanakan. Terima kasih atas partisipasi aktif dan kerja sama dari instansi Anda.',
                'alert_bg' => '#f0fdfa',
                'alert_border' => '#14b8a6',
                'alert_text' => '#115e59',
            ],
            'pending' => [
                'title' => 'MENUNGGU PENINJAUAN',
                'badge_bg' => '#fef3c7',
                'badge_color' => '#b45309',
                'badge_border' => '#fcd34d',
                'message' => 'Pengajuan kegiatan edukasi Anda telah berhasil tercatat di sistem kami dan sedang menunggu antrean peninjauan oleh tim edukasi.',
                'alert_bg' => '#fffbeb',
                'alert_border' => '#f59e0b',
                'alert_text' => '#92400e',
            ],
        ];

        $current = $config[$status] ?? $config['pending'];
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7f6; width: 100%;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                
                <!-- Main Container Card -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 620px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; text-align: left;">
                    
                    <!-- Header Section with logo.png -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #003366 0%, #004f9e 100%); padding: 28px 30px; text-align: center; border-bottom: 4px solid #fbbf24;">
                            @if(file_exists(public_path('images/logo.png')))
                                <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Plat-BK" style="max-height: 52px; max-width: 220px; margin-bottom: 8px; display: inline-block;">
                            @else
                                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase;">
                                    PLAT-BK
                                </h1>
                            @endif
                            <p style="color: #cbd5e1; margin: 4px 0 0 0; font-size: 13px; font-weight: 500; letter-spacing: 0.3px;">
                                Platform Edukasi Bank Indonesia Pematangsiantar
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 30px;">
                            
                            <!-- Salutation -->
                            <p style="font-size: 15px; line-height: 1.6; color: #334155; margin-top: 0;">
                                Halo <strong>{{ $pengajuan->nama_pic }}</strong> ({{ $pengajuan->nama_instansi }}),
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 24px;">
                                Status pengajuan kegiatan edukasi yang Anda daftarkan telah diperbarui oleh Administrator.
                            </p>

                            <!-- Clean Status Box without Emoji -->
                            <div style="background-color: {{ $current['alert_bg'] }}; border: 1px solid {{ $current['alert_border'] }}; border-radius: 12px; padding: 22px 20px; text-align: center; margin-bottom: 24px;">
                                <div style="margin-bottom: 12px;">
                                    <span style="display: inline-block; background-color: {{ $current['badge_bg'] }}; color: {{ $current['badge_color'] }}; border: 1px solid {{ $current['badge_border'] }}; padding: 7px 18px; border-radius: 9999px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px;">
                                        STATUS: {{ $current['title'] }}
                                    </span>
                                </div>
                                <p style="font-size: 13px; line-height: 1.6; color: {{ $current['alert_text'] }}; margin: 0; font-weight: 500;">
                                    {{ $current['message'] }}
                                </p>
                            </div>

                            <!-- Catatan Admin Box (if exists) -->
                            @if(!empty($pengajuan->catatan_admin))
                            <div style="background-color: #f8fafc; border-left: 4px solid #003366; border-radius: 0 10px 10px 0; padding: 14px 18px; margin-bottom: 24px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                                <strong style="color: #003366; font-size: 13px; display: block; margin-bottom: 4px;">
                                    Catatan dari Administrator:
                                </strong>
                                <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #334155; font-style: italic;">
                                    "{{ $pengajuan->catatan_admin }}"
                                </p>
                            </div>
                            @endif

                            <!-- Activity Details Summary -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-top: 10px;">
                                <tr>
                                    <td style="background-color: #f1f5f9; padding: 12px 18px; border-bottom: 1px solid #e2e8f0;">
                                        <strong style="color: #003366; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Ringkasan Kegiatan Edukasi
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 18px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 13px; line-height: 1.6;">
                                            <tr>
                                                <td width="38%" style="color: #64748b; padding: 4px 0; vertical-align: top;">Nama Instansi</td>
                                                <td width="62%" style="color: #0f172a; font-weight: 700; padding: 4px 0;">{{ $pengajuan->nama_instansi }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Tipe Pengajuan</td>
                                                <td style="color: #334155; font-weight: 600; padding: 4px 0;">
                                                    {{ $pengajuan->jenis_pengajuan === 'dikunjungi' ? 'BI Mengunjungi Instansi' : 'Mengunjungi Bank Indonesia' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Tema Kegiatan</td>
                                                <td style="color: #0f172a; font-weight: 600; padding: 4px 0;">{{ $pengajuan->tema_kegiatan ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Tanggal Pelaksanaan</td>
                                                <td style="color: #0f172a; font-weight: 600; padding: 4px 0;">
                                                    {{ $pengajuan->tanggal_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_kegiatan)->isoFormat('dddd, D MMMM Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Waktu & Durasi</td>
                                                <td style="color: #334155; padding: 4px 0;">
                                                    {{ $pengajuan->waktu_mulai ?: '-' }} - {{ $pengajuan->waktu_selesai ?: '-' }}
                                                    @if($pengajuan->durasi) ({{ $pengajuan->durasi }}) @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Kota / Kabupaten</td>
                                                <td style="color: #003366; font-weight: 700; padding: 4px 0;">
                                                    {{ $pengajuan->kota_kabupaten ?: '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Lokasi Kegiatan</td>
                                                <td style="color: #334155; padding: 4px 0;">{{ $pengajuan->lokasi_kegiatan ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                                <a href="{{ config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')) }}/user/dashboard/riwayat" 
                                   style="display: inline-block; background-color: #003366; color: #ffffff; text-decoration: none; padding: 13px 26px; border-radius: 10px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 10px rgba(0, 51, 102, 0.25);">
                                    Lihat Status di Platform &rarr;
                                </a>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 22px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;">
                            <p style="margin: 0 0 6px 0; font-weight: 600; color: #475569;">
                                &copy; {{ date('Y') }} Plat-BK. Hak Cipta Dilindungi.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94a3b8;">
                                Email notifikasi otomatis dari sistem Plat-BK. Mohon tidak membalas ke alamat email ini.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
