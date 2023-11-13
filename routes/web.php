<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/book-info/{isbn}', [HomeController::class, 'detail'])->name('bookDetail');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors');
    Route::get('/authors/create', [AuthorController::class, 'create'])->name('authors.create');
    Route::post('/authors/create', [AuthorController::class, 'store'])->name('authors.store');
    Route::post('/authors/{id}/update-status', [AuthorController::class, 'updateStatus']);
    Route::post('/authors/{id}/delete', [AuthorController::class, 'destroy']);
    Route::get('/authors/{id}/edit', [AuthorController::class, 'edit'])->name('authors.edit');
    Route::patch('/authors/{id}/edit', [AuthorController::class, 'update'])->name('authors.update');

    Route::get('/genres', [GenreController::class, 'index'])->name('genres');
    Route::get('/genres/create', [GenreController::class, 'create'])->name('genres.create');
    Route::post('/genres/create', [GenreController::class, 'store'])->name('genres.store');
    Route::post('/genres/{id}/update-status', [GenreController::class, 'updateStatus']);
    Route::post('/genres/{id}/delete', [GenreController::class, 'destroy']);
    Route::get('/genres/{id}/edit', [GenreController::class, 'edit'])->name('genres.edit');
    Route::patch('/genres/{id}/edit', [GenreController::class, 'update'])->name('genres.update');

    Route::get('/publishers', [PublisherController::class, 'index'])->name('publishers');
    Route::get('/publishers/create', [PublisherController::class, 'create'])->name('publishers.create');
    Route::post('/publishers/create', [PublisherController::class, 'store'])->name('publishers.store');
    Route::post('/publishers/{id}/update-status', [PublisherController::class, 'updateStatus']);
    Route::post('/publishers/{id}/delete', [PublisherController::class, 'destroy']);
    Route::get('/publishers/{id}/edit', [PublisherController::class, 'edit'])->name('publishers.edit');
    Route::patch('/publishers/{id}/edit', [PublisherController::class, 'update'])->name('publishers.update');

    Route::get('/books', [BookController::class, 'index'])->name('books');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books/create', [BookController::class, 'store'])->name('books.store');
    Route::post('/books/{id}/update-status', [BookController::class, 'updateStatus']);
    Route::post('/books/{id}/delete', [BookController::class, 'destroy']);
    Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::post('/books/{id}/edit', [BookController::class, 'update'])->name('books.update');
    Route::get('/books/import-data', [BookController::class, 'importData'])->name('books.import');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
