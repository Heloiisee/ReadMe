<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques (visiteurs non connectés)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home'); // Page d’accueil publique

/*
|--------------------------------------------------------------------------
| Routes protégées (authentification requise)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Tableau de bord (optionnel)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bibliothèque et lecture de livres
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/read', [BookController::class, 'read'])->name('books.read');
    Route::get('/books/{book}/chapter/{index}', [BookController::class, 'chapter'])->name('books.chapter');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth (fichier généré par Breeze/Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
