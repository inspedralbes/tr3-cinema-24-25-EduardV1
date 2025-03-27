<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Obtenim totes les sessions ja creades (pel·lícules)
        $movie_sessions = DB::table('movie_sessions')->get(); // Això agafa tant la pel·lícula 1 com la 2

        // Recorrer totes les sessions i afegir 100 entrades per cada una
        foreach ($movie_sessions as $movie_session) {
            $letters = range('A', 'J'); // Lletres de la A a la J
            $numbers = range(1, 10); // Números del 1 al 10

            // Creem les 100 entrades per cada sessió (pel·lícula)
            foreach ($letters as $letter) {
                foreach ($numbers as $number) {
                    $position = $letter . $number;

                    // Determinem si l'entrada està disponible o ja està comprada
                    // Generem un número aleatori per decidir si l'entrada es compra o no
                    $available = true;
                    
                    // Simulem que el 20% de les entrades ja estan comprades
                    if (rand(1, 10) <= 2) { // Aproximadament el 30% de les entrades seran comprades
                        $available = false;
                    }

                    $type = str_starts_with($letter, 'F') ? 'vip' : 'normal';
                    $price = $type === 'vip' ? 8 : 6;

                    // Inserir l'entrada a la taula tickets
                    DB::table('tickets')->insert([
                        'position' => $position,
                        'available' => $available, // Indicar si està comprada o no
                        'movie_session_id' => $movie_session->id, // Associem l'entrada amb la sessió de la pel·lícula
                        'type' => $type,
                        'price' => $price,
                    ]);
                }
            }
        }

    }
}
