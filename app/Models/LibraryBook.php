<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'stock',
    ];

    // Relations
    public function bookBorrows()
    {
        return $this->hasMany(BookBorrow::class, 'book_id');
    }
}
