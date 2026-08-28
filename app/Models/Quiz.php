<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'difficulty',
        'mode',
        'estimated_time_minutes',
        'icon',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'estimated_time_minutes' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')->orderBy('sort_order', 'asc');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }

    public function sessions()
    {
        return $this->hasMany(QuizSession::class, 'quiz_id');
    }
}
