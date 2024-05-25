<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [
        'book_name',
        'categories',
        'book_cover',
        'writer',
        'publisher',
        'year',
        'synopsis',
    ];
}
