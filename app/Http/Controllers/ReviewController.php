<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function showBookReviews($bookId)
    {
        $book = Book::findOrFail($bookId);
        $userReview = Auth::user()->reviews()->where('book_id', $bookId)->first();
        $hasReturned = Auth::user()->borrowings()->where('book_id', $bookId)->where('status', 'returned')->exists();
        $otherReviews = Review::where('book_id', $bookId)->get();

        return view('book.review', compact('book', 'userReview', 'hasReturned', 'otherReviews'));

    }

    public function submitReview(Request $request, $bookId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Ensure the user has the "returned" status for this book
        $hasReturned = Borrowing::where('user_id', $user->id)
                                ->where('book_id', $bookId)
                                ->where('status', 'returned')
                                ->exists();

        if (!$hasReturned) {
            return redirect()->back()->with('error', 'You cannot review this book as you have not returned it.');
        }

        // Ensure the user hasn't already reviewed this book
        $existingReview = Review::where('user_id', $user->id)
                                ->where('book_id', $bookId)
                                ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this book.');
        }

        $review = new Review;
        $review->user_id = $user->id;
        $review->book_id = $bookId;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }
}
