<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_session_id')->constrained('movie_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('seat', 3); // Format: A1, B10, etc.
            $table->decimal('price', 8, 2);
            $table->boolean('is_vip')->default(false);
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('phone');
            $table->timestamps();

            // Ensure unique seat per session
            $table->unique(['movie_session_id', 'seat']);
            
            // Add indexes for common queries
            $table->index('email');
            $table->index(['movie_session_id', 'is_vip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};