<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong'
    ]);
});

Route::post('/register', [UserController::class, 'register'])->name('auth.register');
Route::post('/login', [UserController::class, 'login'])->middleware('throttle:10,1')->name('auth.login');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');

    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::match(['put','patch'], '/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});