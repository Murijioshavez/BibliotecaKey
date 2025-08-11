<?php

use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/catalogo', function () {
    return Inertia::render('Catalogo');
})->middleware(['auth', 'verified'])->name('catalogo');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Aprobaciones de prestamos de libros
Route::middleware('admin')->group(function() {
    Route::put('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::delete('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::get('/admin/loans', [LoanController::class, 'adminLoans'])->name('admin.loans');

});


//crear nuevos libros
Route::middleware(['admin'])->group(function () {
    //Rutas para crear libros
    Route::get('/books/admin/create', [AdminBookController::class, 'create'])->name('books.admin.create');
    Route::post('/books/admin', [AdminBookController::class, 'store'])->name('books.admin.store');

    //Rutas para actualizar informacion o eliminar libros
    Route::get('/books/admin/{book}/edit', [AdminBookController::class, 'editBook']);
    Route::patch('/books/admin/{book}/edit', [AdminBookController::class, 'updateBook'])->name('books.admin.update');
    Route::delete('/books/admin/{book}', [AdminBookController::class, 'destroyBook'])->name('books.admin.destroy');

});

Route::middleware(['auth'])->group(function () {
    //catalogo completo
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    //Catalogo por letra especifica
    Route::get('/books/letter/{letter}', [BookController::class, 'byLetter'])->name('books.byLetter');

    //Detalle de un libro
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    //Reservacion de libro
    Route::post('/books/{book}/reserve',[LoanController::class,'reserve'])->name('books.reserve');
});

require __DIR__ . '/auth.php';
