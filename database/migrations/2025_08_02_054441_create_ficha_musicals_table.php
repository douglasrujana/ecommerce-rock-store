<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ficha_musicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('banda_id')->nullable()->constrained('bandas');
            $table->text('historia_album')->nullable();
            $table->text('proceso_grabacion')->nullable();
            $table->string('productor')->nullable();
            $table->string('estudio_grabacion')->nullable();
            $table->text('impacto_cultural')->nullable();
            $table->json('premios')->nullable(); // Grammy, etc.
            $table->decimal('ventas_millones', 5, 2)->nullable();
            $table->text('curiosidades')->nullable();
            $table->json('equipos_usados')->nullable(); // Instrumentos, amplificadores
            $table->boolean('es_premium')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ficha_musicals');
    }
};