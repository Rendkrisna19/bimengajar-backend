<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembaruan Status Pengajuan Kegiatan</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #171717; line-height: 1.6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        .header { background-color: #0054a7; padding: 30px 20px; text-align: center; color: #ffffff; border-bottom: 4px solid #fbbf24; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px; }
        .content { padding: 30px 40px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 9999px; font-weight: bold; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .status-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-verifikasi { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .status-penjadwalan { background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
        .status-konfirmasi { background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .status-selesai { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-ditolak { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .details-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .details-box p { margin: 8px 0; font-size: 14px; color: #374151; }
        .details-box strong { color: #111827; display: inline-block; width: 140px; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; background-color: #fbbf24; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; margin-top: 20px; text-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: background-color 0.3s; }
        .btn:hover { background-color: #f59e0b; }
        .note { margin-top: 25px; padding: 15px; border-left: 4px solid #ef4444; background-color: #fef2f2; color: #7f1d1d; border-radius: 0 8px 8px 0; }
        .note-title { font-weight: bold; margin-bottom: 5px; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BI Mengajar Siantar</h1>
        </div>
        <div class="content">
            <p style="font-size: 16px; color: #1f2937;">Halo, <strong>{{ $pengajuan->nama_pic }}</strong>,</p>
            <p style="color: #4b5563;">Status pengajuan kegiatan edukasi Anda (<strong>{{ $pengajuan->tema_kegiatan ?: 'Tanpa Tema' }}</strong>) telah diperbarui.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                @php
                    $statusClass = 'status-' . strtolower($pengajuan->status);
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    Status Saat Ini: {{ strtoupper($pengajuan->status) }}
                </span>
            </div>

            @if($pengajuan->catatan_admin)
            <div class="note">
                <div class="note-title">Catatan dari Admin:</div>
                {{ $pengajuan->catatan_admin }}
            </div>
            @endif

            <div class="details-box">
                <p><strong>Nama Instansi:</strong> {{ $pengajuan->nama_instansi }}</p>
                <p><strong>Kategori:</strong> {{ $pengajuan->jenis_pengajuan === 'mengunjungi' ? 'Mengunjungi BI' : 'Dikunjungi BI' }}</p>
                <p><strong>Tanggal:</strong> {{ $pengajuan->tanggal_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_kegiatan)->format('d F Y') : '-' }}</p>
                <p><strong>Waktu:</strong> {{ $pengajuan->waktu_mulai ?: '-' }} - {{ $pengajuan->waktu_selesai ?: '-' }}</p>
                <p><strong>Lokasi:</strong> {{ $pengajuan->lokasi_kegiatan ?: '-' }}</p>
            </div>

            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ config('app.frontend_url', 'http://localhost:3000') }}" class="btn">Masuk ke Platform</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bank Indonesia Pematang Siantar. Hak Cipta Dilindungi.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas ke alamat email ini.</p>
        </div>
    </div>
</body>
</html>
