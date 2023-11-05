<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(BookController::class)->group(function () {
    Route::get('/books', 'index')->name('books.index');
    Route::get('/books/create', 'create')->name('books.create');
    Route::post('/books', 'store')->name('books.store');
    // Route::post('/posts2', 'store2')->name('books.store2');
    Route::get('/books/edit/{id}', 'edit')->name('books.edit');
    Route::post('/books/update', 'update')->name('books.update');
    Route::get('/books/destroy/{id}', 'destroy')->name('books.destroy');
    // Route::get('/books/show/{id}', 'show')->name('books.show');
});


Route::controller(CategoryController::class)->group(function () {
    Route::post('/categories', 'store')->name('categories.store');
    Route::post('/categories/update', 'update')->name('categories.update');
    Route::get('/categories/destroy/{id}', 'destroy')->name('categories.destroy');
});



Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
