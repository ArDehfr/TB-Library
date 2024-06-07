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
        'rent_price',
    ];

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'book_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id', 'book_id');
    }

}
