<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'seat_number', 'customer_name','customer_email', 'type', 'price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(MovieSession::class);
    }
}

