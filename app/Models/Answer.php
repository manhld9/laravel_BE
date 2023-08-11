<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'correct'
    ];

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function exercise_answers() {
        return $this->hasMany(ExerciseAnswer::class);
    }
}
