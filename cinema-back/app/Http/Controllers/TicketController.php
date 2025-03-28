<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\MovieSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{

    //buytickets
    public function buyTickets(Request $request)
    {
        try{

            // dd($request->all());
            Log::info('Dades rebudes a buyTickets:', $request->all());
            
            $request->validate([
                'movie_session_id' => 'required|integer|exists:movie_sessions,id',
                'seats' => 'required|array',
                'seats.*' => 'required|string |exists:tickets,position',
            ]);
            
            $tickets = Ticket::whereIn('position', $request->seats)
            ->where('movie_session_id', $request->movie_session_id)
            ->where('available', 1)
            ->get();
            
            // dd($tickets);
            Log::info('Entrades trobades:', $tickets->toArray());
            
            if ($tickets->count() !== count($request->seats)) {
                Log::warning('Algunes entrades no estan disponibles.');
                return response()->json([
                    'message' => 'Algunes entrades no estàn disponibles'
                ], 400);
            }
            
            Ticket::whereIn('position', $request->seats)
            ->where('movie_session_id', $request->movie_session_id)
            ->update([
                'available' => 0,
            ]);
            
            Log::info('Entrades actualitzades.');
            
            return response()->json([
                'message' => 'Entrades comprades correctament!'
            ], 200);
        }
        catch(\Exception $e){
            Log::error('Error a buyTickets:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error en processar la reserva',
                'error' => $e->getMessage(),
            ], 500);    
        }
    }

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
            'movie_session_id' => 'required|exists:movie_session,id',
            'positions' => 'required|array',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            //'type' => 'required|in:normal,vip',
        ]);


        $createdTickets = [];
        //$price = $request->type === 'vip' ? 8 : 6;

        foreach ($request->positions as $position) {
            $type = str_starts_with(strtoupper($position), 'F') ? 'vip' : 'normal';
            $price = $type === 'vip' ? 8.00 : 6.00;

            $ticket = Ticket::create([
                'movie_id' => $request->movie_id,
                'positions' => $position,
                'available' => false,
                'type' => $type,
                'price' => $price,
                'customer_name' => $request->customer['name'],
                'customer_email' => $request->customer['email'],
            ]);

            $createdTickets[] = $ticket;
        }

        //return response()->json($ticket, 201);
        return response()->json(['message' => 'Reserva completada'], 201);
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
