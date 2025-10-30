<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'kkm',
        'material_id',
        'teacher_id',
    ];



    // Relations
    public function subjectTeachers()
    {
        return $this->hasMany(SubjectTeacher::class, 'subject_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'subject_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
