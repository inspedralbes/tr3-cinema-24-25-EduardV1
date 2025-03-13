<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'date',
        'time',
        'is_special_day',
        'enable_vip'
    ];

    protected $casts = [
        'date' => 'date',
        'is_special_day' => 'boolean',
        'enable_vip' => 'boolean'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getAvailableSeats()
    {
        $occupiedSeats = $this->tickets()->pluck('seat')->toArray();
        $allSeats = [];
        
        // Generate all possible seats (12 rows × 10 seats)
        for ($row = 'A'; $row <= 'L'; $row++) {
            for ($seat = 1; $seat <= 10; $seat++) {
                $seatId = $row . $seat;
                if (!in_array($seatId, $occupiedSeats)) {
                    $allSeats[] = $seatId;
                }
            }
        }
        
        return $allSeats;
    }

    public function isVipSeat($seat)
    {
        return substr($seat, 0, 1) === 'F'; // Row F is VIP
    }

    public function getTicketPrice($isVip)
    {
        if ($isVip) {
            return $this->is_special_day ? 6 : 8;
        }
        return $this->is_special_day ? 4 : 6;
    }
}