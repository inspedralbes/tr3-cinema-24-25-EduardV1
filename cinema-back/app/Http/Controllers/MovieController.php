<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Mostrar una llista de les pel·lícules.
     */
    public function index()
    {
        // $movies = Movie::all();
        // return response()->json($movies);

    
        return response()->json(Movie::all());
    
    }

    /**
     * Crear una nova pel·lícula.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
        ]);

        $movie = Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
        ]);

        return response()->json($movie, 201);
    }

    /**
     * Mostrar una pel·lícula específica.
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie);
    }

    /**
     * Actualitzar una pel·lícula existent.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
        ]);

        $movie->update([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
        ]);

        return response()->json($movie);
    }

    /**
     * Esborrar una pel·lícula.
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return response()->json(null, 204);
    }
}
