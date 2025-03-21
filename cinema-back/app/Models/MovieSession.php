<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSession extends Model
{
    use HasFactory;

    // Taula associada al model
    protected $table = 'movie_sessions';

    // Atributs que es poden omplir de manera massiva
    protected $fillable = [
        'movie_id',
        'start_time',
        'total_seats',
        'vip_seats',
    ];

    // Relació amb el model Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    // Relació amb el model Ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'movie_session_id');
    }
}
