<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'type',
        'max_score',
    ];

    // Relations
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
