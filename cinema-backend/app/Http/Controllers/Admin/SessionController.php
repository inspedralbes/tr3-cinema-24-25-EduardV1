<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with('movie')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('admin.sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'is_special_day' => 'boolean',
            'enable_vip' => 'boolean',
            'movie.title' => 'required|string|max:255',
            'movie.director' => 'required|string|max:255',
            'movie.year' => 'required|string|max:4',
            'movie.plot' => 'required|string',
            'movie.poster' => 'required|url'
        ]);

        // Find or create movie
        $movie = Movie::firstOrCreate(
            ['title' => $validated['movie']['title']],
            [
                'director' => $validated['movie']['director'],
                'year' => $validated['movie']['year'],
                'plot' => $validated['movie']['plot'],
                'poster' => $validated['movie']['poster']
            ]
        );

        // Create session
        $session = Session::create([
            'movie_id' => $movie->id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'is_special_day' => $validated['is_special_day'] ?? false,
            'enable_vip' => $validated['enable_vip'] ?? true
        ]);

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session created successfully');
    }

    public function edit(Session $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    public function update(Request $request, Session $session)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'is_special_day' => 'boolean',
            'enable_vip' => 'boolean',
            'movie.title' => 'required|string|max:255',
            'movie.director' => 'required|string|max:255',
            'movie.year' => 'required|string|max:4',
            'movie.plot' => 'required|string',
            'movie.poster' => 'required|url'
        ]);

        // Find or create movie
        $movie = Movie::firstOrCreate(
            ['title' => $validated['movie']['title']],
            [
                'director' => $validated['movie']['director'],
                'year' => $validated['movie']['year'],
                'plot' => $validated['movie']['plot'],
                'poster' => $validated['movie']['poster']
            ]
        );

        // Update session
        $session->update([
            'movie_id' => $movie->id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'is_special_day' => $validated['is_special_day'] ?? false,
            'enable_vip' => $validated['enable_vip'] ?? true
        ]);

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session updated successfully');
    }

    public function destroy(Session $session)
    {
        if ($session->tickets()->exists()) {
            return back()->with('error', 'Cannot delete session with existing tickets');
        }

        $session->delete();

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session deleted successfully');
    }

    public function searchMovies(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3'
        ]);

        try {
            $response = Http::get('http://www.omdbapi.com/', [
                'apikey' => config('services.omdb.key'),
                's' => $request->query
            ]);

            if ($response->successful() && $response['Response'] === 'True') {
                // Get full details for each movie
                $detailedResults = collect($response['Search'])
                    ->take(5)
                    ->map(function ($movie) {
                        $details = Http::get('http://www.omdbapi.com/', [
                            'apikey' => config('services.omdb.key'),
                            'i' => $movie['imdbID']
                        ]);
                        return $details->json();
                    });

                return response()->json([
                    'status' => 'success',
                    'data' => $detailedResults
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No movies found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search movies'
            ], 500);
        }
    }
}