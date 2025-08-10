<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Book;
use Illuminate\Http\Request;
class BookController extends Controller
{
    //Catalogo para estudiantes
    public function index()
    {
        $books = Book::orderBy('title')->get();

        $groupedBooks = $books->groupBy(function($book){
            return strtoupper(substr($book->title, 0 ,1));
        });

        return Inertia::render('Books/Index', ['groupedBooks' => $groupedBooks]);
    }

    public function byLetter($letter)
    {
        $letter = strtoupper($letter);
        $books = Book::where('title', 'like', $letter.'%')
        ->orderby('title')
        ->paginate(20);

        return Inertia::render('Books/ByLetter', ['letter' => $letter, 'books' => $books]);
    }

    public function show(Book $book)
    {
        return Inertia::render('Books/Show', ['book' => $book]);
    }
}
