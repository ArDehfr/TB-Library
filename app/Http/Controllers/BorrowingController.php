<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Returned;
use App\Models\Payment;

class BorrowingController extends Controller
{
    public function create()
    {
        $books = Book::where('book_quantities', '>', 0)->get();
        return view('user.user-lib', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_rent' => 'required|date',
            'book' => 'required|exists:books,book_id',
        ]);

        Borrowing::create([
            'book_id' => $request->book,
            'user_id' => auth()->id(),
            'day_rent' => $request->day_rent,
            'status' => 'pending',
        ]);

        return redirect()->route('home');
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

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json(['status' => 'rejected']);
    }

    public function approve(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update(['status' => 'borrowed']);

        return response()->json(['status' => 'borrowed']);
    }

    public function return(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'report' => 'required|string',
            'returnStatus' => 'required|string|in:good,late,damaged,lost',
        ]);

        // Find the borrowing record
        $borrowing = Borrowing::findOrFail($id);

        // Update the borrowing record
        $borrowing->return_report = $request->report;
        $borrowing->status_return = $request->returnStatus;
        $borrowing->status = 'returned';
        $borrowing->save();

        // Create a record in the Returned table
        Returned::create([
            'borrowing_id' => $borrowing->id,
            'returned_at' => now(),
            'return_report' => $request->report,
            'status_return' => $request->returnStatus,
        ]);

        // Calculate the payment amount based on the return status
        $rentPrice = $borrowing->book->rent_price;
        switch ($request->returnStatus) {
            case 'late':
                $paymentAmount = $rentPrice * 2;
                break;
            case 'damaged':
                $paymentAmount = $rentPrice * 5;
                break;
            case 'lost':
                $paymentAmount = $rentPrice * 10;
                break;
            default:
                $paymentAmount = $rentPrice;
                break;
        }

        // Create a record in the Payment table
        Payment::create([
            'user_id' => $borrowing->user_id, // Get the user ID from the borrowing record
            'borrowing_id' => $borrowing->id,
            'amount' => $paymentAmount,
            'paid_at' => now(),
        ]);

        return response()->json(['status' => 'returned']);
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

