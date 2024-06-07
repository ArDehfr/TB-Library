<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($bookId)
    {
        $book = Book::findOrFail($bookId);
        Auth::user()->favorites()->attach($bookId);

        return response()->json(['message' => 'Book added to favorites']);
    }

    public function destroy($bookId)
    {
        $book = Book::findOrFail($bookId);
        Auth::user()->favorites()->detach($bookId);

        return response()->json(['message' => 'Book removed from favorites']);
    }

    public function toggleFavorite(Request $request, $bookId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('book_id', $bookId)
                            ->first();

        if ($favorite) {
            $favorite->status = $favorite->status === 'active' ? 'inactive' : 'active';
            $favorite->save();
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'book_id' => $bookId,
                'status' => 'active',
            ]);
        }

        return response()->json(['message' => 'Favorite status updated successfully']);
    }

    // public function showFavorites()
    // {
    //     $favorites = Auth::user()->favorites()->with('book')->get()->pluck('book');
    //     return view('user.user-fav', compact('favorites'));
    // }

}
