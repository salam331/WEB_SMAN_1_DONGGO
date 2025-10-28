<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coach_id',
    ];

    // Relations
    public function coach()
    {
        return $this->belongsTo(Teacher::class, 'coach_id');
    }

    public function members()
    {
        return $this->hasMany(ExtracurricularMember::class);
    }
}
