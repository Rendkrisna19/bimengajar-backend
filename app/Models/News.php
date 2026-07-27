<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'image',
        'description',
        'content',
        'category',
        'published_at',
    ];

    protected $casts = [
        'image' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title) . '-' . time();
            }
            if (empty($news->published_at)) {
                $news->published_at = now();
            }
        });
    }
}
