<?php

namespace App\Mail;

use App\Models\PengajuanEdukasi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    /**
     * Create a new message instance.
     */
    public function __construct(PengajuanEdukasi $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $status = strtolower($this->pengajuan->status ?? 'pending');
        $instansi = $this->pengajuan->nama_instansi ?: 'Instansi';

        switch ($status) {
            case 'disetujui':
            case 'konfirmasi':
                $subject = "[Plat-BK] Pengajuan Kegiatan Edukasi Disetujui ({$instansi})";
                break;
            case 'ditolak':
                $subject = "[Plat-BK] Pembaruan Status Pengajuan Edukasi ({$instansi})";
                break;
            case 'penjadwalan':
                $subject = "[Plat-BK] Pengajuan Edukasi Dalam Tahap Penjadwalan ({$instansi})";
                break;
            case 'verifikasi':
                $subject = "[Plat-BK] Pengajuan Edukasi Dalam Tahap Verifikasi ({$instansi})";
                break;
            case 'selesai':
                $subject = "[Plat-BK] Kegiatan Edukasi Telah Selesai ({$instansi})";
                break;
            default:
                $subject = "[Plat-BK] Pembaruan Status Pengajuan Edukasi ({$instansi})";
                break;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan_status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
