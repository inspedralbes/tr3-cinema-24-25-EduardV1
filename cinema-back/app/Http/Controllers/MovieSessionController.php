<?php

namespace App\Http\Controllers;

use App\Models\MovieSession;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieSessionController extends Controller
{
    /**
     * Mostrar totes les sessions de pel·lícules.
     */
    public function index()
    {
        $sessions = MovieSession::with('movie')->get();
        return response()->json($sessions);
    }

    /**
     * Crear una nova sessió de pel·lícula.
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'start_time' => 'required|date',
            'total_seats' => 'required|integer',
            'vip_seats' => 'required|integer',
        ]);

        $session = MovieSession::create([
            'movie_id' => $request->movie_id,
            'start_time' => $request->start_time,
            'total_seats' => $request->total_seats,
            'vip_seats' => $request->vip_seats,
        ]);

        return response()->json($session, 201);
    }

    /**
     * Mostrar una sessió específica.
     */
    public function show($id)
    {
        $session = MovieSession::with('movie')->findOrFail($id);
        return response()->json($session);
    }

    /**
     * Actualitzar una sessió de pel·lícula existent.
     */
    public function update(Request $request, $id)
    {
        $session = MovieSession::findOrFail($id);

        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'start_time' => 'required|date',
            'total_seats' => 'required|integer',
            'vip_seats' => 'required|integer',
        ]);

        $session->update([
            'movie_id' => $request->movie_id,
            'start_time' => $request->start_time,
            'total_seats' => $request->total_seats,
            'vip_seats' => $request->vip_seats,
        ]);

        return response()->json($session);
    }

    /**
     * Esborrar una sessió.
     */
    public function destroy($id)
    {
        $session = MovieSession::findOrFail($id);
        $session->delete();

        return response()->json(null, 204);
    }
}
