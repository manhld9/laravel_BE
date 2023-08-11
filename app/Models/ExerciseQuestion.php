<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'question_id',
        'position',
        'value'
    ];

    public function exercise() {
        return $this->belongsTo(Exercise::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function exercise_answers() {
        return $this->hasMany(ExerciseAnswer::class);
    }
}
