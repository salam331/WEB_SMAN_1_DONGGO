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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($subject) {
            try {
                $classes = ClassRoom::all();
                $teachers = Teacher::all();

                if ($classes->isEmpty() || $teachers->isEmpty()) {
                    return;
                }

                foreach ($classes as $class) {
                    foreach ($teachers as $teacher) {
                        SubjectTeacher::firstOrCreate(
                            [
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher->id,
                                'class_id' => $class->id,
                            ]
                        );
                    }
                }

                foreach ($classes as $class) {
                    foreach ($teachers as $teacher) {
                        Material::firstOrCreate(
                            [
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher->id,
                                'class_id' => $class->id,
                            ],
                            [
                                'title' => 'Materi ' . $subject->name . ' - ' . $class->name,
                                'description' => 'Materi otomatis untuk mata pelajaran ' . $subject->name,
                                'is_published' => false,
                            ]
                        );
                    }
                }

                $examTypes = ['PTS', 'PAS'];
                foreach ($classes as $class) {
                    foreach ($examTypes as $examType) {
                        $subjectTeacher = SubjectTeacher::where('subject_id', $subject->id)
                            ->where('class_id', $class->id)
                            ->first();

                        Exam::firstOrCreate(
                            [
                                'class_id' => $class->id,
                                'subject_id' => $subject->id,
                                'name' => $examType,
                            ],
                            [
                                'teacher_id' => $subjectTeacher ? $subjectTeacher->teacher_id : null,
                                'start_date' => now()->addDays(rand(1, 30)),
                                'end_date' => now()->addDays(rand(31, 60)),
                                'total_score' => 100,
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error auto-creating subject relations: ' . $e->getMessage());
            }
        });
    }

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
