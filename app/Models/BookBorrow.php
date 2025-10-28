<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookBorrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'student_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // Relations
    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
