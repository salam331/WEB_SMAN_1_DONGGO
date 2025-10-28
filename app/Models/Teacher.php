<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'jabatan',
        'kelas_wali_id',
        'bio',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeroom()
    {
        return $this->belongsTo(ClassRoom::class, 'kelas_wali_id');
    }

    public function homeroomClass()
    {
        return $this->belongsTo(ClassRoom::class, 'kelas_wali_id');
    }

    public function subjectTeachers()
    {
        return $this->hasMany(SubjectTeacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class, 'coach_id');
    }
}
