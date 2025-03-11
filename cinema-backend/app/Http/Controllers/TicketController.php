<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchaseConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the user's tickets
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with(['session.movie'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    /**
     * Show specific ticket details
     */
    public function show($id)
    {
        $ticket = Ticket::with(['session.movie'])
            ->where('id', $id)
            ->firstOrFail();

        // Check if user owns the ticket or is admin
        if ($ticket->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ticket
        ]);
    }

    /**
     * Purchase tickets for a session
     */
    public function purchase(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:sessions,id',
            'seats' => 'required|array|min:1|max:10',
            'seats.*' => 'required|string|regex:/^[A-L][1-9][0-9]?$/', // Format: A1, B10, etc.
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get session
            $session = Session::findOrFail($request->session_id);

            // Check if session is in the future
            if ($session->date < now()->format('Y-m-d')) {
                throw new \Exception('Cannot purchase tickets for past sessions');
            }

            // Check if user already has tickets for this session
            $existingTickets = Ticket::where('email', $request->email)
                ->where('session_id', $session->id)
                ->exists();

            if ($existingTickets) {
                throw new \Exception('You already have tickets for this session');
            }

            // Check seat availability
            $occupiedSeats = Ticket::where('session_id', $session->id)
                ->pluck('seat')
                ->toArray();

            foreach ($request->seats as $seat) {
                if (in_array($seat, $occupiedSeats)) {
                    throw new \Exception("Seat $seat is already taken");
                }
            }

            // Calculate prices
            $tickets = [];
            $totalAmount = 0;

            foreach ($request->seats as $seat) {
                $isVip = substr($seat, 0, 1) === 'F'; // Row F is VIP
                $price = $isVip ? 
                    ($session->is_special_day ? 6 : 8) : 
                    ($session->is_special_day ? 4 : 6);

                $tickets[] = [
                    'session_id' => $session->id,
                    'seat' => $seat,
                    'price' => $price,
                    'is_vip' => $isVip,
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $totalAmount += $price;
            }

            // Create tickets
            Ticket::insert($tickets);

            // Send confirmation email
            Mail::to($request->email)->send(new TicketPurchaseConfirmation([
                'session' => $session,
                'tickets' => $tickets,
                'totalAmount' => $totalAmount,
                'userName' => $request->name
            ]));

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tickets purchased successfully',
                'data' => [
                    'tickets' => $tickets,
                    'totalAmount' => $totalAmount
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Check tickets by email
     */
    public function checkByEmail($email)
    {
        $tickets = Ticket::with(['session.movie'])
            ->where('email', $email)
            ->whereHas('session', function ($query) {
                $query->where('date', '>=', now()->format('Y-m-d'));
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('session_id');

        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    /**
     * Get occupancy report for a session
     */
    public function getOccupancyReport($sessionId)
    {
        // Ensure user is admin
        if (!auth()->user()->is_admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $session = Session::with('movie')->findOrFail($sessionId);
        $tickets = Ticket::where('session_id', $sessionId)->get();

        $normalTickets = $tickets->where('is_vip', false)->count();
        $vipTickets = $tickets->where('is_vip', true)->count();
        
        $totalSeats = 120; // 12 rows × 10 seats
        $occupiedSeats = $tickets->count();
        $occupancyRate = ($occupiedSeats / $totalSeats) * 100;

        $normalRevenue = $tickets->where('is_vip', false)->sum('price');
        $vipRevenue = $tickets->where('is_vip', true)->sum('price');
        $totalRevenue = $normalRevenue + $vipRevenue;

        // Create seat map
        $seatMap = array_fill(0, 12, array_fill(0, 10, 0)); // 0 = available
        foreach ($tickets as $ticket) {
            $row = ord(substr($ticket->seat, 0, 1)) - ord('A');
            $seat = intval(substr($ticket->seat, 1)) - 1;
            $seatMap[$row][$seat] = 1; // 1 = occupied
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'session' => $session,
                'seatMap' => $seatMap,
                'normalTickets' => $normalTickets,
                'vipTickets' => $vipTickets,
                'normalRevenue' => $normalRevenue,
                'vipRevenue' => $vipRevenue,
                'totalRevenue' => $totalRevenue,
                'occupiedSeats' => $occupiedSeats,
                'occupancyRate' => round($occupancyRate, 2)
            ]
        ]);
    }
}