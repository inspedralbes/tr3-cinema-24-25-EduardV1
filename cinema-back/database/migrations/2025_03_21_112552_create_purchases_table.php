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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // ID de la compra
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Referència a tickets
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Referència a usuaris
            $table->timestamps(); // created_at i updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
