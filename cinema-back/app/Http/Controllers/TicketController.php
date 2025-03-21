<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\MovieSession;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Mostrar tots els tiquets.
     */
    public function index()
    {
        $tickets = Ticket::with('session')->get();
        return response()->json($tickets);
    }

    /**
     * Crear un nou tiquet.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:movie_sessions,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
            'seat_number' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'session_id' => $request->session_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'seat_number' => $request->seat_number,
        ]);

        return response()->json($ticket, 201);
    }

    /**
     * Mostrar un tiquet específic.
     */
    public function show($id)
    {
        $ticket = Ticket::with('session')->findOrFail($id);
        return response()->json($ticket);
    }

    /**
     * Actualitzar un tiquet existent.
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'session_id' => 'required|exists:movie_sessions,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
            'seat_number' => 'required|string',
        ]);

        $ticket->update([
            'session_id' => $request->session_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'seat_number' => $request->seat_number,
        ]);

        return response()->json($ticket);
    }

    /**
     * Esborrar un tiquet.
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json(null, 204);
    }
}
