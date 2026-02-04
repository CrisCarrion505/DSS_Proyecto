<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Course extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'max_students',
        'teacher_id',
        'is_active'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments', 'course_id', 'user_id')
        ->withPivot(['status', 'enrolled_at'])
        ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCourseId($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'course_id');
    }

    public function activeExam(): HasOne
    {
        // Último examen activo del curso (si hay varios activos por error, toma el más reciente)
        return $this->hasOne(Exam::class, 'course_id')
            ->where('is_active', true)
            ->latestOfMany();
    }
    public function take(\App\Models\Course $course)
    {
        $user = auth()->user();

        if (!$user->courses()->whereKey($course->id)->exists()) {
            abort(403, 'No estás inscrito en este curso.');
        }

        $exam = \App\Models\Exam::where('course_id', $course->id)
            ->where('is_active', true)
            ->latest()
            ->first();

        return view('prueba', compact('course', 'exam'));
    }



}
