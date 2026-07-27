<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi';

    protected $fillable = [
        'nama_kegiatan',
        'kategori',
        'deskripsi',
        'tanggal_kegiatan',
        'posted_by',
        'images',
        'video_urls',
    ];

    protected $casts = [
        'images'          => 'array',
        'video_urls'      => 'array',
        'tanggal_kegiatan' => 'date',
    ];
}
