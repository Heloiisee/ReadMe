<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/read', [BookController::class, 'read'])->name('books.read');
Route::get('/books/{book}/chapter/{index}', [BookController::class, 'chapter'])->name('books.chapter');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
