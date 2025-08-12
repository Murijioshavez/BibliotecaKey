<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    public function create()
    {
        return Inertia::render('Books/Admin/Create');
    }

    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'available_copies' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cover' => 'required|image|max:2048', // max 2MB
        ]);

        // Subir imagen
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover_path'] = '/storage/' . $path;
        }

        // Crear libro
        Book::create($validated);

        // Redirigir al listado con mensaje éxito
        return redirect()->route('books.index')->with('success', 'Libro creado correctamente');
    }

    public function editBook(Book $book)
    {
        return Inertia::render('Books/Admin/Edit', ['book' => $book]);
    }

public function updateBook(Request $request, Book $book): RedirectResponse
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
        'available_copies' => 'required|integer|min:0',
        'category' => 'required|string|max:100',
        'description' => 'nullable|string',
        'cover' => 'nullable|image|max:2048',
    ]);

    // Si hay nueva portada
    if ($request->hasFile('cover')) {
        if ($book->cover_path && Storage::disk('public')->exists(str_replace('/storage/', '', $book->cover_path))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $book->cover_path));
        }
        $path = $request->file('cover')->store('covers', 'public');
        $validated['cover_path'] = '/storage/' . $path;
    }

    $book->fill($validated);

    if ($book->isDirty()) {
        $book->save();
        return redirect()->route('books.index')->with('success', 'Libro actualizado correctamente.');
    }

    return redirect()->route('books.index')->with('info', 'No se detectaron cambios en el libro.');
}

    public function destroyBook(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'El libro se ha eliminado exitosamente');
    }

    public function returnsView()
{
    // Cargar solo préstamos aprobados y no devueltos
    $loans = Loan::with('book', 'user')
        ->whereIn('status', ['prestado', 'vencido'])
        ->whereNull('returned_at')
        ->get();

    return Inertia::render('Admin/Returns', [
        'loans' => $loans
    ]);
}

public function markAsReturned(Loan $loan)
{
    $loan->update([
        'status' => 'devuelto',
        'returned_at' => now()
    ]);

    return redirect()->route('admin.returns')->with('success', 'Préstamo marcado como devuelto.');
}
public function history()
{
    $loans = Loan::with('book', 'user')->orderBy('created_at', 'desc')->paginate(15);
    return Inertia::render('Admin/History', [
        'loans' => $loans
    ]);
}

}
