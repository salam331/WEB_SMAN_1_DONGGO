<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'material_id',
        'day',
        'start_time',
        'end_time',
        'room',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($schedule) {
            static::createOrUpdateSubjectTeacher($schedule);
        });

        static::updated(function ($schedule) {
            static::createOrUpdateSubjectTeacher($schedule);
        });
    }

    private static function createOrUpdateSubjectTeacher($schedule)
    {
        try {
            SubjectTeacher::firstOrCreate(
                [
                    'subject_id' => $schedule->subject_id,
                    'teacher_id' => $schedule->teacher_id,
                    'class_id' => $schedule->class_id,
                ]
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating SubjectTeacher from Schedule: ' . $e->getMessage());
        }
    }

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

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
