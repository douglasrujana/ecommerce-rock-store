<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cancions', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('producto_id')->constrained('productos');
            $table->text('letra_original')->nullable();
            $table->text('letra_traducida')->nullable();
            $table->enum('nivel_ingles', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])->nullable();
            $table->json('vocabulario_clave')->nullable(); // Palabras importantes
            $table->text('analisis_musical')->nullable();
            $table->string('duracion')->nullable(); // "3:45"
            $table->integer('track_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancions');
    }
};