<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_public',
        'posted_by',
        'start_date',
        'end_date',
        'pinned',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'pinned' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relations
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
