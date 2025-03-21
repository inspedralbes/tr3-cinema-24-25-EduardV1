<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('movie_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade'); // Foreign key
            $table->dateTime('start_time'); // Hora de la sessió
            $table->integer('total_seats')->default(120); // Nombre de butaques
            $table->integer('vip_seats')->default(10); // Nombre de butaques VIP
            $table->timestamps(); // Timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_sessions');
    }
};
