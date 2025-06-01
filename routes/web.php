<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SettingsController;
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

    // Bibliothèque et lecture de livres
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/read', [BookController::class, 'read'])->name('books.read');
    Route::get('/books/{book}/chapter/{index}', [BookController::class, 'chapter'])->name('books.chapter');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Gestion du profil utilisateur
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::delete('/settings/account', [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.updateTheme');
});

/*
|--------------------------------------------------------------------------
| Auth (fichier généré par Breeze/Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
