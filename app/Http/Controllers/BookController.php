<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        $favorites = Auth::user()->favorites->pluck('book_id')->toArray();
        return view('admin.book', ['books' => $books, 'favorites' => $favorites]);
    }

    public function indexUser()
    {
        $books = Book::all();
        $favorites = Auth::user()->favorites->pluck('book_id')->toArray();
        return view('home', ['books' => $books, 'favorites' => $favorites]);
    }

    public function indexCrew()
    {
        $books = Book::all();
        return view('crew.add', ['books' => $books]);
    }

    // In your controller
    public function showBooks()
    {
        $books = Book::all();
        return view('home.crew', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.add-book');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
            'categories' => 'required|string|max:255',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'writer' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer',
            'synopsis' => 'required|string',
        ]);

        $book = new Book();
        $book->book_name = $request->book_name;
        $book->categories = $request->categories;
        $book->writer = $request->writer;
        $book->publisher = $request->publisher;
        $book->year = $request->year;
        $book->synopsis = $request->synopsis;

        if ($request->hasFile('book_cover')) {
            $file = $request->file('book_cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('fotobuku', $filename);
            $book->book_cover = $filename;
        }

        $book->save();

        return redirect()->back()->with('success', 'Data Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $book = Book::find($id);
        $categories = Category::all(); // Assuming you have a Category model
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $book_id)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
            'categories' => 'required|string|max:255',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'writer' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer',
            'synopsis' => 'required|string',
        ]);

        $book = Book::findOrFail($book_id);
        $book->book_name = $request->book_name;
        $book->categories = $request->categories;
        $book->writer = $request->writer;
        $book->publisher = $request->publisher;
        $book->year = $request->year;
        $book->synopsis = $request->synopsis;

        if ($request->hasFile('book_cover')) {
            $file = $request->file('book_cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('fotobuku', $filename);
            $book->book_cover = $filename;
        }

        $book->save();

        return redirect()->route('admin.book')->with('success', 'Data Updated Successfully');
    }

    public function updateCrew(Request $request, $book_id)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
            'categories' => 'required|string|max:255',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'writer' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer',
            'synopsis' => 'required|string',
            'rent_price' => 'required|integer',
        ]);

        $book = Book::findOrFail($book_id);
        $book->book_name = $request->book_name;
        $book->categories = $request->categories;
        $book->writer = $request->writer;
        $book->publisher = $request->publisher;
        $book->year = $request->year;
        $book->synopsis = $request->synopsis;
        $book->rent_price = $request->rent_price;

        if ($request->hasFile('book_cover')) {
            $file = $request->file('book_cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('fotobuku', $filename);
            $book->book_cover = $filename;
        }

        $book->save();

        return redirect()->route('crew.add')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($book_id)
    {
        $book = Book::findOrFail($book_id);
        $book->delete();
        return redirect()->back()->with('success', 'Book deleted successfully');
    }

    public function read($bookId)
    {
        $book = Book::findOrFail($bookId);
        return view('user.read', compact('book'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $books = Book::where('book_name', 'LIKE', "%{$query}%")
                      ->orWhere('writer', 'LIKE', "%{$query}%")
                      ->get();

        return response()->json($books);
    }
}
