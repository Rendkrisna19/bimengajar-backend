<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'quiz_title',
        'user_id',
        'nickname',
        'avatar',
        'mode',
        'score',
        'correct_answers',
        'total_questions',
        'pin_code',
    ];

    protected $casts = [
        'score' => 'integer',
        'correct_answers' => 'integer',
        'total_questions' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
