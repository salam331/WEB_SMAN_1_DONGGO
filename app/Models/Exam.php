<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'exam_date',
        'start_time',
        'duration',
        'total_questions',
        'passing_grade',
        'instructions',
        'total_score',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'exam_date' => 'date',
        'start_time' => 'datetime:H:i',
        'duration' => 'integer',
        'total_questions' => 'integer',
        'passing_grade' => 'integer',
        'total_score' => 'integer',
    ];

    // Relations
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function gradeItems()
    {
        return $this->hasMany(GradeItem::class);
    }
}
