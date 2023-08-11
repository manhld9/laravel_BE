<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_question_id',
        'answer_id'
    ];

    public function exercise_question() {
        return $this->belongsTo(ExerciseQuestion::class);
    }

    public function answer() {
        return $this->belongsTo(Answer::class);
    }
}
