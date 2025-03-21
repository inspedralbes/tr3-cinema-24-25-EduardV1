<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket; // Assegura't de tenir el model 'Ticket' generat

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Creem tiquets per la sessió 1 (Avengers: Endgame)
        Ticket::create([
            'movie_session_id' => 1, // Sessió 1 (Avengers: Endgame)
            'type' => 'normal',
            'user_name' => 'John Doe',
            'user_email' => 'johndoe@example.com',
        ]);

        // Creem tiquets per la sessió 2 (Spider-Man: No Way Home)
        Ticket::create([
            'movie_session_id' => 2, // Sessió 2 (Spider-Man: No Way Home)
            'type' => 'vip',
            'user_name' => 'Jane Doe',
            'user_email' => 'janedoe@example.com',
        ]);
        $this->command->info('✔️ Tickets insertats correctament!');
    }
}
