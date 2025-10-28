<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'path',
        'category',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];
}
