<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SessionController extends Controller
{
    /**
     * Display a listing of sessions
     */
    public function index(Request $request)
    {
        $query = Session::with('movie')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('time');

        // Filter by date if provided
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $sessions = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $sessions
        ]);
    }

    /**
     * Store a new session
     */
    public function store(Request $request)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'is_special_day' => 'boolean',
            'enable_vip' => 'boolean',
            'movie' => 'required|array',
            'movie.title' => 'required|string|max:255',
            'movie.director' => 'required|string|max:255',
            'movie.year' => 'required|string|max:4',
            'movie.plot' => 'required|string',
            'movie.poster' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for conflicting sessions
        $existingSession = Session::where('date', $request->date)
            ->where('time', $request->time)
            ->exists();

        if ($existingSession) {
            return response()->json([
                'status' => 'error',
                'message' => 'A session already exists at this date and time'
            ], 422);
        }

        // Create or find movie
        $movie = Movie::firstOrCreate(
            ['title' => $request->movie['title']],
            [
                'director' => $request->movie['director'],
                'year' => $request->movie['year'],
                'plot' => $request->movie['plot'],
                'poster' => $request->movie['poster']
            ]
        );

        // Create session
        $session = Session::create([
            'date' => $request->date,
            'time' => $request->time,
            'movie_id' => $movie->id,
            'is_special_day' => $request->is_special_day ?? false,
            'enable_vip' => $request->enable_vip ?? true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Session created successfully',
            'data' => $session->load('movie')
        ], 201);
    }

    /**
     * Display the specified session
     */
    public function show($id)
    {
        $session = Session::with('movie')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $session
        ]);
    }

    /**
     * Update the specified session
     */
    public function update(Request $request, $id)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $session = Session::findOrFail($id);

        // Validate request
        $validator = Validator::make($request->all(), [
            'date' => 'date|after_or_equal:today',
            'time' => 'date_format:H:i',
            'is_special_day' => 'boolean',
            'enable_vip' => 'boolean',
            'movie' => 'array',
            'movie.title' => 'string|max:255',
            'movie.director' => 'string|max:255',
            'movie.year' => 'string|max:4',
            'movie.plot' => 'string',
            'movie.poster' => 'url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for conflicting sessions if date or time is being updated
        if (($request->has('date') || $request->has('time')) &&
            Session::where('id', '!=', $id)
                ->where('date', $request->date ?? $session->date)
                ->where('time', $request->time ?? $session->time)
                ->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'A session already exists at this date and time'
            ], 422);
        }

        // Update movie if provided
        if ($request->has('movie')) {
            $movie = Movie::updateOrCreate(
                ['title' => $request->movie['title']],
                [
                    'director' => $request->movie['director'],
                    'year' => $request->movie['year'],
                    'plot' => $request->movie['plot'],
                    'poster' => $request->movie['poster']
                ]
            );
            $session->movie_id = $movie->id;
        }

        // Update session
        $session->fill($request->only([
            'date',
            'time',
            'is_special_day',
            'enable_vip'
        ]));

        $session->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Session updated successfully',
            'data' => $session->load('movie')
        ]);
    }

    /**
     * Remove the specified session
     */
    public function destroy($id)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $session = Session::findOrFail($id);

        // Check if session has any tickets
        if ($session->tickets()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete session with existing tickets'
            ], 422);
        }

        $session->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Session deleted successfully'
        ]);
    }

    /**
     * Search movies using OMDB API
     */
    public function searchMovies(Request $request)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $response = Http::get('http://www.omdbapi.com/', [
                'apikey' => config('services.omdb.key'),
                's' => $request->query
            ]);

            if ($response->successful() && $response['Response'] === 'True') {
                return response()->json([
                    'status' => 'success',
                    'data' => $response['Search']
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

    /**
     * Get occupancy report for a specific date
     */
    public function occupancyReport($date)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $sessions = Session::with(['movie', 'tickets'])
            ->whereDate('date', $date)
            ->get();

        $report = $sessions->map(function ($session) {
            $totalSeats = 120; // 12 rows × 10 seats
            $occupiedSeats = $session->tickets->count();
            $occupancyRate = ($occupiedSeats / $totalSeats) * 100;

            return [
                'session' => $session->only(['id', 'date', 'time', 'is_special_day']),
                'movie' => $session->movie,
                'occupiedSeats' => $occupiedSeats,
                'totalSeats' => $totalSeats,
                'occupancyRate' => round($occupancyRate, 2)
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $report
        ]);
    }

    /**
     * Get revenue report for a specific date
     */
    public function revenueReport($date)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $sessions = Session::with(['movie', 'tickets'])
            ->whereDate('date', $date)
            ->get();

        $report = $sessions->map(function ($session) {
            $tickets = $session->tickets;
            
            $normalTickets = $tickets->where('is_vip', false)->count();
            $vipTickets = $tickets->where('is_vip', true)->count();
            
            $normalRevenue = $tickets->where('is_vip', false)->sum('price');
            $vipRevenue = $tickets->where('is_vip', true)->sum('price');
            $totalRevenue = $normalRevenue + $vipRevenue;

            return [
                'session' => $session->only(['id', 'date', 'time', 'is_special_day']),
                'movie' => $session->movie,
                'ticketsSold' => [
                    'normal' => $normalTickets,
                    'vip' => $vipTickets,
                    'total' => $normalTickets + $vipTickets
                ],
                'revenue' => [
                    'normal' => $normalRevenue,
                    'vip' => $vipRevenue,
                    'total' => $totalRevenue
                ]
            ];
        });

        $totalRevenue = $sessions->flatMap->tickets->sum('price');

        return response()->json([
            'status' => 'success',
            'data' => [
                'sessions' => $report,
                'totalRevenue' => $totalRevenue
            ]
        ]);
    }
}