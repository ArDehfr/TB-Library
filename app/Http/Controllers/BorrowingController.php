<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Returned;

class BorrowingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'day_rent' => 'required|date',
            'day_return' => 'required|date|after_or_equal:day_rent',
            'book' => 'required|exists:books,book_id',
        ]);

        Borrowing::create([
            'book_id' => $request->book,
            'user_id' => auth()->id(),
            'day_rent' => $request->day_rent,
            'day_return' => $request->day_return,
            'status' => 'pending',
        ]);

        return redirect()->route('home.lib');
    }

    public function index()
    {
        $borrowings = Borrowing::with('book', 'user')->get();
        return view('list.crew', compact('borrowings'));
    }

    public function approval()
    {
        $borrowings = Borrowing::with('book', 'user')->where('status', 'pending')->get();
        return view('user.user-download', compact('borrowings'));
    }

    public function approve(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->status = 'approved';
        $borrowing->save();

        return response()->json(['status' => 'approved']);
    }

    public function reject(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->status = 'rejected';
        $borrowing->save();

        return response()->json(['status' => 'rejected']);
    }

    public function return(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        Returned::create([
            'borrowing_id' => $borrowing->id,
            'returned_at' => now(),
        ]);

        $borrowing->update(['status' => 'returned']);

        return redirect()->back();
    }

    public function showBorrowings()
    {
        $borrowings = Borrowing::with('book', 'user')->where('status', '!=', 'returned')->get();
        return view('crew.list', compact('borrowings'));
    }

    public function showReturnedBooks()
    {
        $returns = Returned::with('borrowing.book', 'borrowing.user')->get();
        return view('crew.data', compact('returns'));
    }

}
