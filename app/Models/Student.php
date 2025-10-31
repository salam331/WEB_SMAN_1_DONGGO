<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ParentModel;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'rombel_id', // maps to class_id in forms
        'parent_id',
        'photos',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'rombel_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function bookBorrows()
    {
        return $this->hasMany(BookBorrow::class);
    }

    public function extracurricularMembers()
    {
        return $this->hasMany(ExtracurricularMember::class);
    }
}
