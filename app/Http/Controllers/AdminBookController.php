<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
}
