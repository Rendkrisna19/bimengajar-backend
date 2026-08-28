<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'pin_code',
        'status',
        'current_question_index',
        'participants',
    ];

    protected $casts = [
        'participants' => 'array',
        'current_question_index' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
