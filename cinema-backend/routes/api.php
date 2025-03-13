<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sessions
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/sessions/{id}', [SessionController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Tickets
    Route::post('/tickets/purchase', [TicketController::class, 'purchase']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::get('/tickets/check/{email}', [TicketController::class, 'checkByEmail']);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        // Sessions management
        Route::post('/admin/sessions', [SessionController::class, 'store']);
        Route::put('/admin/sessions/{id}', [SessionController::class, 'update']);
        Route::delete('/admin/sessions/{id}', [SessionController::class, 'destroy']);
        
        // Reports
        Route::get('/admin/reports/occupancy/{date}', [SessionController::class, 'occupancyReport']);
        Route::get('/admin/reports/revenue/{date}', [SessionController::class, 'revenueReport']);
        
        // Movie search (OMDB)
        Route::get('/admin/movies/search', [SessionController::class, 'searchMovies']);
    });
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});