<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieSessionController;
use App\Http\Controllers\TicketController;
use App\Models\Movie;

// Rutes per a les pel·lícules
Route::prefix('movies')->group(function () {
    Route::get('/', [MovieController::class, 'index']);           // Obtenir totes les pel·lícules
    Route::post('/', [MovieController::class, 'store']);          // Crear una nova pel·lícula
    Route::get('{id}', [MovieController::class, 'show']);         // Obtenir una pel·lícula per ID
    Route::put('{id}', [MovieController::class, 'update']);       // Actualitzar una pel·lícula
    Route::delete('{id}', [MovieController::class, 'destroy']);   // Esborrar una pel·lícula
});


// Rutes per a les sessions de pel·lícules
Route::prefix('movie_sessions')->group(function () {
    Route::get('/', [MovieSessionController::class, 'index']);           // Obtenir totes les sessions
    Route::post('/', [MovieSessionController::class, 'store']);          // Crear una nova sessió
    Route::get('{id}', [MovieSessionController::class, 'show']);         // Obtenir una sessió per ID
    Route::put('{id}', [MovieSessionController::class, 'update']);       // Actualitzar una sessió
    Route::delete('{id}', [MovieSessionController::class, 'destroy']);   // Esborrar una sessió
});

// Rutes per a els tiquets
Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);           // Obtenir tots els tiquets
    Route::post('/', [TicketController::class, 'store']);          // Crear un nou tiquet
    Route::get('{id}', [TicketController::class, 'show']);         // Obtenir un tiquet per ID
    Route::put('{id}', [TicketController::class, 'update']);       // Actualitzar un tiquet
    Route::delete('{id}', [TicketController::class, 'destroy']);   // Esborrar un tiquet
});


route::get('/movie_sessions/{id}/tickets', [MovieSessionController::class, 'getTickets']);