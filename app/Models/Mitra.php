<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'singkatan',
        'nama_lengkap',
        'kategori',
        'lokasi',
        'deskripsi',
        'no_wa',
        'status_persetujuan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
