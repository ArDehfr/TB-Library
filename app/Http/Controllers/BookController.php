<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return view('admin.book', ['books' => $books]);
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

        return redirect()->route('admin.book')->with('success', 'Data Added Successfully');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($book_id)
    {
        $book = Book::findOrFail($book_id);
        $book->delete();
        return redirect()->route('admin.book')->with('success', 'Book deleted successfully');
    }

}
