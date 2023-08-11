<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'subtitle',
        'description',
        'limit_time',
        'level'
    ];

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function exercises() {
        return $this->hasMany(Exercise::class);
    }

    public function scopeCompleted($query, $user_id)
    {
        return $query->whereExists(function ($q) use ($user_id) {
            $q->select('exercises.id')->from('exercises')->whereRaw('exercises.exam_id = exams.id AND exercises.user_id = ?', [$user_id]);
        });
    }
}
