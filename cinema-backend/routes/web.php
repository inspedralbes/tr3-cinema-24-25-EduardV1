<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SessionController;

Route::get('/', function () {
    return redirect()->route('movies.index');
});

// Rutas para películas
Route::resource('movies', MovieController::class);

// Rutas para sesiones
Route::resource('sessions', SessionController::class);
