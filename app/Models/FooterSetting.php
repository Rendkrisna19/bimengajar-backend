<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $table = 'footer_settings';

    protected $fillable = [
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
    ];
}
