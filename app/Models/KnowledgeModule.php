<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeModule extends Model
{
    protected $fillable = [
        'course_id',
        'teacher_id',
        'topic',
        'pedagogy_model',
        'title',
        'summary',
        'content',
        'activities',
        'key_concepts',
        'estimated_minutes',
        'is_active',
    ];

    protected $casts = [
        'activities' => 'array',
        'key_concepts' => 'array',
        'is_active' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function sessions()
    {
        return $this->hasMany(KnowledgeSession::class);
    }
}
