<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie; // Assegura't de tenir el model 'Movie' generat

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::create([
            'title' => 'Avengers: Endgame',
            'description' => 'El final èpic de la saga dels Avengers.',
            'duration' => 180, // en minuts
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/avengers_endgame-135478227-mmed.jpg',
            'director' => 'Anthony Russo, Joe Russo',
            'cast' => ' Robert Downey Jr. · Chris Evans · Mark Ruffalo · Chris Hemsworth · Scarlett Johansson · Jeremy Renner · Don Cheadle',
            'time' => '20:00h',
            'score' => 8.5,
            'trailer_url' => 'https://www.youtube.com/watch?v=UQ3bqYKnyhM',
        ]);

        Movie::create([
            'title' => 'Spider-Man: No Way Home',
            'description' => 'Llançament de Spider-Man en un univers alternatiu.',
            'duration' => 150,
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/spider_man_no_way_home-642739124-mmed.jpg',
            'director' => 'Jon Watts',
            'cast' => 'Tom Holland · Peter Parker ; Zendaya · MJ ; Benedict Cumberbatch · Doctor Strange',
            'time' => '21:00h',
            'score' => 8.4,
            'trailer_url' => 'https://www.youtube.com/watch?v=SkmRT3M4Vx4',
        ]);
        
        // Afegir més pel·lícules segons sigui necessari
        $this->command->info('✔️ Pelis afegides correctament!');
    }
}
