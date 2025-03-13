<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use Illuminate\Support\Facades\File;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/data/movies.json'));
        $movies = json_decode($json, true)['movies'];

        foreach ($movies as $movieData) {
            Movie::create($movieData);
        }
    }
}