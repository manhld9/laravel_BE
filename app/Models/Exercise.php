<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'submitted_at',
        'exam_id',
        'user_id'
    ];

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function exercise_questions() {
        return $this->hasMany(ExerciseQuestion::class);
    }
}
