<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'image',
        'description',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'image' => 'array',
    ];
}
