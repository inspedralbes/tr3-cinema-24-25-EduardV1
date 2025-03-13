<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'seat',
        'price',
        'is_vip',
        'name',
        'surname',
        'email',
        'phone'
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Before saving a ticket, set the VIP status and price based on the seat
        static::creating(function ($ticket) {
            $session = Session::findOrFail($ticket->session_id);
            $ticket->is_vip = $session->isVipSeat($ticket->seat);
            $ticket->price = $session->getTicketPrice($ticket->is_vip);
        });
    }
}