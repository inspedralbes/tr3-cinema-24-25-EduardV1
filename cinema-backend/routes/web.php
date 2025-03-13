<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SessionController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('sessions', SessionController::class);
    Route::get('movies/search', [SessionController::class, 'searchMovies'])->name('movies.search');
});

require __DIR__.'/auth.php';