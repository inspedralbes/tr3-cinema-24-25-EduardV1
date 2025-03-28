<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('tickets', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('movie_session_id')->constrained('movie_sessions')->onDelete('cascade');
        //     $table->enum('type', ['normal', 'vip']);
        //     $table->string('user_name');
        //     $table->string('user_email');
        //     $table->timestamps();
        // });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // ID de l'entrada
            $table->string('position'); // Posició de l'entrada
            $table->boolean('available')->default(true); // Indica si l'entrada està disponible
            $table->enum('type', ['normal', 'vip']);
            $table->string('price',8,2)->default(6);
            $table->foreignId('movie_session_id')->constrained('movie_sessions')->onDelete('cascade');  
            $table->timestamps(); // created_at i updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }


};
