<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    use HasFactory;

    protected $table = 'hero_banners';

    protected $fillable = [
        'title',
        'title_en',
        'subtitle',
        'subtitle_en',
        'button_primary_text',
        'button_primary_text_en',
        'button_primary_url',
        'button_secondary_text',
        'button_secondary_text_en',
        'button_secondary_url',
        'image',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return url('storage/' . ltrim($this->image, '/'));
    }
}
