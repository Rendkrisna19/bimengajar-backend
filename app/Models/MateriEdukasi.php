<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriEdukasi extends Model
{
    protected $fillable = [
        'kategori_materi_id', 'judul', 'slug', 'deskripsi_singkat', 
        'jenis_konten', 'thumbnail', 'file_path', 'link_eksternal', 'konten_teks'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMateri::class, 'kategori_materi_id');
    }
}
