<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie; // Assegura't de tenir el model 'Movie' generat

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::create([
            'title' => 'Inception',
            'description' => 'Un thriller de ciència ficció on un lladre especialitzat en robar secrets mitjançant els somnis es veu involucrat en una missió per implantar una idea en la ment d\'una persona.',
            'duration' => 148, // en minuts
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/inception-550767399-mmed.jpg',
            'director' => 'Christopher Nolan',
            'cast' => 'Leonardo DiCaprio · Joseph Gordon-Levitt · Ellen Page · Tom Hardy · Cillian Murphy · Marion Cotillard',
            'time' => '22:00h',
            'score' => 8.8,
            'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
        ]);

        Movie::create([
            'title' => 'The Dark Knight',
            'description' => 'Batman es veu obligat a enfrontar-se al Joker, un criminal que amenaça amb submergir Gotham City en el caos.',
            'duration' => 152,
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/the_dark_knight-538872438-mmed.jpg',
            'director' => 'Christopher Nolan',
            'cast' => 'Christian Bale · Heath Ledger · Aaron Eckhart · Maggie Gyllenhaal · Michael Caine · Gary Oldman',
            'time' => '20:30h',
            'score' => 9.0,
            'trailer_url' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
        ]);

        Movie::create([
            'title' => 'Interstellar',
            'description' => 'Un grup de científics emprèn un viatge a través d\'un forat de cuc per intentar salvar la humanitat de la seva desaparició a causa d\'una catàstrofe ambiental.',
            'duration' => 169,
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/interstellar-591612423-mmed.jpg',
            'director' => 'Christopher Nolan',
            'cast' => 'Matthew McConaughey · Anne Hathaway · Jessica Chastain · Michael Caine · Matt Damon',
            'time' => '23:00h',
            'score' => 8.6,
            'trailer_url' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
        ]);

        Movie::create([
            'title' => 'The Matrix',
            'description' => 'Un programador de software descobreix que la realitat que coneix és una simulació creada per màquines per controlar la humanitat.',
            'duration' => 136,
            'rating' => 'R',
            'poster_url' => 'https://pics.filmaffinity.com/the_matrix-877664157-mmed.jpg',
            'director' => 'The Wachowskis',
            'cast' => 'Keanu Reeves · Laurence Fishburne · Carrie-Anne Moss · Hugo Weaving · Joe Pantoliano',
            'time' => '19:30h',
            'score' => 8.7,
            'trailer_url' => 'https://www.youtube.com/watch?v=vKQi3bBA1y8',
        ]);

        Movie::create([
            'title' => 'Guardians of the Galaxy',
            'description' => 'Un grup de criminals intergalàctics es veu obligat a treballar junts per defensar l\'univers d\'una amenaça mortal.',
            'duration' => 121,
            'rating' => 'PG-13',
            'poster_url' => 'https://pics.filmaffinity.com/guardians_of_the_galaxy-692933049-mmed.jpg',
            'director' => 'James Gunn',
            'cast' => 'Chris Pratt · Zoe Saldana · Dave Bautista · Vin Diesel · Bradley Cooper',
            'time' => '21:30h',
            'score' => 8.0,
            'trailer_url' => 'https://www.youtube.com/watch?v=d96cjJhvlMA',
        ]);
        
        // Afegir més pel·lícules segons sigui necessari
    }
}
