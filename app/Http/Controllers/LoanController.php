<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function reserve(Book $book)
    {
        $user = auth()->user();


        //validamos que hayan copias disponibles
        if ($book->available_copies < 1) {
            return redirect()->back()->with('error', 'No hay copias disponibles');

        }
        //Vereficamos si el usuario no tiene ya una reserva activa
        $existingLoan = Loan::where("user_id", $user->id)
            ->where("book_id", $book->id)
            ->whereIn('status', ['reservado', 'prestado'])
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'Ya tienes una reserva activa para este libro');
        }
        //Crear la reserva
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'reservado',
        ]);

        //reducimos las copias disponibles
        $book->decrement('available_copies');
        return redirect()->route('books.index')->with('success', 'Libro reservado exitosamente');
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'reservado') {
            return redirect()->back()->with('error', 'El prestamo no puede aprobarse');
        }

        $loan->update([
            'status' => 'prestado',
            'fecha_prestamo' => now(),
            'fecha_vencimiento' => now()->addDays(7),
        ]);
        return redirect()->back()->with('success', 'El prestamo fue aprobado');

    }
    public function reject(Loan $loan, Book $book)
    {
        if ($loan->status !== 'reservado') {
            return redirect()->back()->with('error', 'El préstamo no puede rechazarse');
        }

        $loan->delete();
        $book->increment('available_copies');
        return redirect()->back()->with('error', 'El prestamo fue rechazado');
    }

    public function adminLoans()
    {
        $loans = Loan::with('book', 'user')->where('status', 'reservado')->get();

        return Inertia::render('Admin/AdminLoans', [
            'loans' => $loans
        ]);
    }

}
