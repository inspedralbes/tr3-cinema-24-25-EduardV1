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
        ]);

        Movie::create([
            'title' => 'Spider-Man: No Way Home',
            'description' => 'Llançament de Spider-Man en un univers alternatiu.',
            'duration' => 150,
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/spider_man_no_way_home-642739124-mmed.jpg',
        ]);
        
        // Afegir més pel·lícules segons sigui necessari
        $this->command->info('✔️ Pelis afegides correctament!');
    }
}
