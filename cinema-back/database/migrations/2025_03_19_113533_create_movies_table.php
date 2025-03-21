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
        Schema::create('movies', function (Blueprint $table) {
            $table->id(); // ID automàtic
            $table->string('title');
            $table->text('description');
            $table->string('poster_url')->nullable();
            $table->integer('duration');
            $table->string('director');
            $table->string('cast');
            $table->string('time');
            $table->float('score');
            $table->string('rating');
            $table->string('trailer_url');
            $table->timestamps(); // timestamps (created_at, updated_at)
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }

};
