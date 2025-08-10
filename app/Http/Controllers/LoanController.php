<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function reserve(Book $book)
    {
        $user = auth()->user();


        //validamos que hayan copias disponibles
        if ($book->available_copies < 1) {
            return response()->json([
                'message' => 'No hay copias disponibles'
            ], 422);

        }
        //Vereficamos si el usuario no tiene ya una reserva activa
        $existingLoan = Loan::where("user_id", $user->id)
            ->where("book_id", $book->id)
            ->whereIn('status', ['reservado', 'prestado'])
            ->first();

        if ($existingLoan) {
            return response()->json([
                'message' => 'Ya tienes una reserva activa'
            ], 422);
        }
        //Crear la reserva
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'reservado',
        ]);

        //reducimos las copias disponibles
        $book->decrement('available_copies');
        return back()->with('success', 'Libro reservado exitosamente');
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'reservado') {
            return response()->json(['error' => 'Este préstamo no se puede aprobar.'], 400);
        }

        $loan->update([
            'status' => 'prestado',
            'fecha_prestamo' => now(),
            'fecha_vencimiento' => now()->addDays(7),
        ]);

    }

    public function adminLoans()
    {
        $loans = Loan::with('book', 'user')->where('status', 'reservado')->get();

        return Inertia::render('Admin/AdminLoans', [
            'loans' => $loans
        ]);
    }

}
