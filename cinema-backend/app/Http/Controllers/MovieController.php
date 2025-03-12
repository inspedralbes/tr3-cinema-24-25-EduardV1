<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return view('movies.index', compact('movies'));
    }

    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'duration' => 'required|integer|min:1',
            'genre' => 'required|max:100',
            'director' => 'required|max:255',
            'synopsis' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('movies', 'public');
            $validated['image'] = $path;
        }

        Movie::create($validated);

        return redirect()->route('movies.index')
            ->with('success', 'Película creada correctamente.');
    }

    public function edit(Movie $movie)
    {
        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'duration' => 'required|integer|min:1',
            'genre' => 'required|max:100',
            'director' => 'required|max:255',
            'synopsis' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($movie->image) {
                Storage::disk('public')->delete($movie->image);
            }
            $path = $request->file('image')->store('movies', 'public');
            $validated['image'] = $path;
        }

        $movie->update($validated);

        return redirect()->route('movies.index')
            ->with('success', 'Película actualizada correctamente.');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->image) {
            Storage::disk('public')->delete($movie->image);
        }
        
        $movie->delete();

        return redirect()->route('movies.index')
            ->with('success', 'Película eliminada correctamente.');
    }
}
