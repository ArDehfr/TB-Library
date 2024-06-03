<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_name' => 'required|string|max:255',
            'categories' => 'required|string|max:255',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'writer' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer',
            'synopsis' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book = new Book();
        $book->fill($request->all());

        if ($request->hasFile('book_cover')) {
            $file = $request->file('book_cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('fotobuku', $filename);
            $book->book_cover = $filename;
        }

        $book->save();

        return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $book_id)
    {
        $validator = Validator::make($request->all(), [
            'book_name' => 'required|string|max:255',
            'categories' => 'required|string|max:255',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'writer' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer',
            'synopsis' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book = Book::findOrFail($book_id);
        $book->fill($request->all());

        if ($request->hasFile('book_cover')) {
            $file = $request->file('book_cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('fotobuku', $filename);
            $book->book_cover = $filename;
        }

        $book->save();

        return response()->json(['message' => 'Book updated successfully', 'book' => $book]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($book_id)
    {
        $book = Book::findOrFail($book_id);
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    }
}
