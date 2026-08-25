<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrePostTest extends Model
{
    use HasFactory;

    protected $table = 'pre_post_tests';

    protected $fillable = [
        'judul',
        'tipe',
        'deskripsi',
        'slides',
        'is_active',
    ];

    protected $casts = [
        'slides' => 'array',
        'is_active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(TestSubmission::class, 'test_id');
    }
}
