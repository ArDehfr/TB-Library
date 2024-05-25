<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'user_id', 'day_rent', 'day_return', 'status'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Use 'user_id' as the foreign key
    }

    public function returned()
    {
        return $this->hasOne(Returned::class);
    }

}
