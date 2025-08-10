<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Loan;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index() {
    $user = Auth::user();

    // Variables que vamos a pasar a la vista
    $totalLibros = null;
    $librosPrestados = null;
    $reservasPendientes = null;
    $historial = [];

    if ($user->role === 'admin') {
        // Datos solo para admin
        $totalLibros = Book::count();
        $librosPrestados = Loan::where('status', 'prestado')->count();
        $reservasPendientes = Loan::where('status', 'reservado')->count();

    } else {
        // Datos solo para estudiante
        $historial = Loan::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['prestado', 'reservado'])
            ->get();
    }

    return Inertia::render('Dashboard', [
        'totalLibros' => $totalLibros,
        'librosPrestados' => $librosPrestados,
        'reservasPendientes' => $reservasPendientes,
        'historial' => $historial,
        'role' => $user->role,
    ]);
}
}
