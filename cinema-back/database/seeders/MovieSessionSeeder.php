<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovieSession; // Assegura't de tenir el model 'MovieSession' generat
use Carbon\Carbon;

class MovieSessionSeeder extends Seeder
{
    public function run(): void
    {
        // Sessió per a Avengers: Endgame
        MovieSession::create([
            'movie_id' => 1, // Movie 'Avengers: Endgame'
            'start_time' => Carbon::now()->addDays(1)->setTime(18, 0), // Sessió de demà a les 18:00
            'total_seats' => 120,
            'vip_seats' => 10,
        ]);

        // Sessió per a Spider-Man: No Way Home
        MovieSession::create([
            'movie_id' => 2, // Movie 'Spider-Man: No Way Home'
            'start_time' => Carbon::now()->addDays(2)->setTime(20, 0), // Sessió d'aquí dos dies a les 20:00
            'total_seats' => 120,
            'vip_seats' => 10,
        ]);
    }
}
