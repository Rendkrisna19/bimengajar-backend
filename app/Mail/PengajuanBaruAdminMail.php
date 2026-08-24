<?php

namespace App\Mail;

use App\Models\PengajuanEdukasi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengajuanBaruAdminMail extends Mailable
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
        $instansi = $this->pengajuan->nama_instansi ?: 'Instansi';
        $tema = $this->pengajuan->tema_kegiatan ?: 'Pengajuan Baru';
        
        return new Envelope(
            subject: "[Plat-BK] Pengajuan Edukasi Baru: {$instansi} - {$tema}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan_baru_admin',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if (!empty($this->pengajuan->dokumen_proposal)) {
            // Check storage disk public
            if (Storage::disk('public')->exists($this->pengajuan->dokumen_proposal)) {
                $filePath = Storage::disk('public')->path($this->pengajuan->dokumen_proposal);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'pdf';
                $safeName = Str::slug($this->pengajuan->nama_instansi ?: 'pengajuan', '_');
                $attachmentName = "Proposal_{$safeName}.{$extension}";

                $attachments[] = Attachment::fromPath($filePath)->as($attachmentName);
            } elseif (file_exists(storage_path('app/public/' . $this->pengajuan->dokumen_proposal))) {
                $filePath = storage_path('app/public/' . $this->pengajuan->dokumen_proposal);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'pdf';
                $safeName = Str::slug($this->pengajuan->nama_instansi ?: 'pengajuan', '_');
                $attachmentName = "Proposal_{$safeName}.{$extension}";

                $attachments[] = Attachment::fromPath($filePath)->as($attachmentName);
            }
        }

        return $attachments;
    }
}
