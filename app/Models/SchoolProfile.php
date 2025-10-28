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
        'vision',
        'mission',
        'logo_path',
        'map_embed',
        'total_students',
        'total_teachers',
        'total_programs',
        'total_achievements',
    ];
}
