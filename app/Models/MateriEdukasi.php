<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriEdukasi extends Model
{
    protected $fillable = [
        'kategori_materi_id', 'judul', 'slug', 'deskripsi_singkat', 
        'jenis_konten', 'thumbnail', 'images', 'file_path', 'link_eksternal', 'link_youtube', 'link_drive', 'konten_teks'
    ];

    protected $casts = [
        'images' => 'array',
        'link_youtube' => 'array',
        'link_drive' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMateri::class, 'kategori_materi_id');
    }
}
