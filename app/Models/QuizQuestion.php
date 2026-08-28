<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'options',
        'time_limit_seconds',
        'explanation',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'time_limit_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
