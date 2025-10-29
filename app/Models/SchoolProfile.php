<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'headmaster_name',
        'accreditation',
        'vision',
        'mission',
        'logo_path',
        'map_embed',
        'total_students',
        'total_teachers',
        'total_programs',
        'total_achievements',
        'hero_title',
        'hero_description',
        'school_description',
        'features',
        'statistics',
    ];

    protected $casts = [
        'features' => 'array',
        'statistics' => 'array',
    ];
}
