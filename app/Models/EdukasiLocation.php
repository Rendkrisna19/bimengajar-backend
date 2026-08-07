<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdukasiLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'year',
        'latitude',
        'longitude',
        'address',
        'province',
        'description',
        'activities',
        'photos'
    ];

    protected $casts = [
        'activities' => 'array',
        'photos' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}
