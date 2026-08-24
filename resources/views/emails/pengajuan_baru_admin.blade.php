<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengajuan Kegiatan Baru - Plat-BK</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #1e293b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7f6; width: 100%;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                
                <!-- Main Card Container -->
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
                            
                            <!-- Alert Tag without Emojis -->
                            <div style="background-color: #eff6ff; border-left: 4px solid #004f9e; border-radius: 0 8px 8px 0; padding: 14px 18px; margin-bottom: 24px; border-top: 1px solid #dbeafe; border-right: 1px solid #dbeafe; border-bottom: 1px solid #dbeafe;">
                                <strong style="color: #003366; font-size: 14px; display: block; margin-bottom: 4px;">Pemberitahuan Pengajuan Baru</strong>
                                <span style="color: #334155; font-size: 13px; line-height: 1.5;">
                                    Pengajuan kegiatan edukasi baru telah dikirimkan oleh pemohon dan menunggu proses verifikasi oleh Administrator.
                                </span>
                            </div>

                            <!-- Greeting -->
                            <p style="font-size: 15px; line-height: 1.6; color: #334155; margin-top: 0;">
                                Halo <strong>Tim Administrator Plat-BK</strong>,<br>
                                Berikut rincian data pengajuan kegiatan edukasi yang baru saja diterima melalui platform:
                            </p>

                            <!-- Section 1: Data Instansi & Pemohon -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-top: 20px; overflow: hidden;">
                                <tr>
                                    <td style="background-color: #f1f5f9; padding: 12px 18px; border-bottom: 1px solid #e2e8f0;">
                                        <strong style="color: #003366; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Informasi Instansi & Pemohon (PIC)
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 18px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 13px; line-height: 1.6;">
                                            <tr>
                                                <td width="38%" style="color: #64748b; padding: 4px 0; vertical-align: top;">Tipe Pengajuan</td>
                                                <td width="62%" style="color: #0f172a; font-weight: 700; padding: 4px 0;">
                                                    <span style="display: inline-block; background-color: #003366; color: #ffffff; padding: 2px 10px; border-radius: 6px; font-size: 11px;">
                                                        {{ $pengajuan->jenis_pengajuan === 'dikunjungi' ? 'BI Mengunjungi Instansi' : 'Mengunjungi Bank Indonesia' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Nama Instansi</td>
                                                <td style="color: #0f172a; font-weight: 600; padding: 4px 0;">{{ $pengajuan->nama_instansi }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Jenis Instansi</td>
                                                <td style="color: #334155; padding: 4px 0;">{{ $pengajuan->jenis_instansi }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Alamat Lengkap</td>
                                                <td style="color: #334155; padding: 4px 0;">{{ $pengajuan->alamat_instansi }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Penanggung Jawab (PIC)</td>
                                                <td style="color: #0f172a; font-weight: 600; padding: 4px 0;">
                                                    {{ $pengajuan->nama_pic }} @if($pengajuan->jabatan_pic)({{ $pengajuan->jabatan_pic }})@endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Email PIC</td>
                                                <td style="color: #004f9e; font-weight: 600; padding: 4px 0;">
                                                    <a href="mailto:{{ $pengajuan->email_pic }}" style="color: #004f9e; text-decoration: none;">{{ $pengajuan->email_pic }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">No. Telepon / WhatsApp</td>
                                                <td style="color: #0f172a; font-weight: 600; padding: 4px 0;">{{ $pengajuan->no_telp_pic }}</td>
                                            </tr>
                                            @if($pengajuan->user)
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Akun Terdaftar</td>
                                                <td style="color: #64748b; padding: 4px 0; font-size: 12px;">
                                                    {{ $pengajuan->user->name }} ({{ $pengajuan->user->email }})
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section 2: Detail Kegiatan -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-top: 18px; overflow: hidden;">
                                <tr>
                                    <td style="background-color: #f1f5f9; padding: 12px 18px; border-bottom: 1px solid #e2e8f0;">
                                        <strong style="color: #003366; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Detail Rencana Kegiatan
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 18px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 13px; line-height: 1.6;">
                                            <tr>
                                                <td width="38%" style="color: #64748b; padding: 4px 0; vertical-align: top;">Nama / Tema Kegiatan</td>
                                                <td width="62%" style="color: #0f172a; font-weight: 700; padding: 4px 0;">{{ $pengajuan->tema_kegiatan ?: '-' }}</td>
                                            </tr>
                                            @if($pengajuan->tujuan_kegiatan)
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Tujuan Kegiatan</td>
                                                <td style="color: #334155; padding: 4px 0;">{{ $pengajuan->tujuan_kegiatan }}</td>
                                            </tr>
                                            @endif
                                            @if($pengajuan->deskripsi_kegiatan)
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Deskripsi Kegiatan</td>
                                                <td style="color: #334155; padding: 4px 0;">{{ $pengajuan->deskripsi_kegiatan }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Estimasi Peserta</td>
                                                <td style="color: #0f172a; font-weight: 600; padding: 4px 0;">{{ $pengajuan->jumlah_peserta ? $pengajuan->jumlah_peserta . ' Orang' : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Tanggal Rencana</td>
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
                                                <td style="color: #64748b; padding: 4px 0; vertical-align: top;">Lokasi Pelaksanaan</td>
                                                <td style="color: #334155; padding: 4px 0;">{{ $pengajuan->lokasi_kegiatan ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Lampiran Info Box -->
                            @if($pengajuan->dokumen_proposal)
                            <div style="margin-top: 18px; padding: 14px 18px; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px;">
                                <span style="font-size: 13px; color: #065f46;">
                                    <strong>Lampiran Proposal:</strong> Berkas proposal kegiatan dari pemohon telah dilampirkan pada email ini.
                                </span>
                            </div>
                            @endif

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 32px; margin-bottom: 10px;">
                                <a href="{{ config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')) }}/admin/kunjungan" 
                                   style="display: inline-block; background-color: #003366; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(0, 51, 102, 0.25);">
                                    Kelola Pengajuan di Admin Panel &rarr;
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
                                Email notifikasi otomatis dari sistem Plat-BK.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
