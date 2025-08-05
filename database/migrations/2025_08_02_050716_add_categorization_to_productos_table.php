<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
            $table->foreignId('decada_id')->nullable()->constrained('decadas')->onDelete('set null');
            $table->foreignId('pais_id')->nullable()->constrained('pais')->onDelete('set null');
            $table->year('año')->nullable(); // Año específico del álbum
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['decada_id']);
            $table->dropForeign(['pais_id']);
            $table->dropColumn(['categoria_id', 'decada_id', 'pais_id', 'año']);
        });
    }
};