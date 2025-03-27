<?php

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;  
use Database\Seeders\MovieSeeder;
use Database\Seeders\MovieSessionSeeder;
use Database\Seeders\TicketSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            MovieSeeder::class,
            MovieSessionSeeder::class,
            TicketSeeder::class,
        ]);

        $this->command->info('Base de datos actualizada correctamente!');
    }
}

