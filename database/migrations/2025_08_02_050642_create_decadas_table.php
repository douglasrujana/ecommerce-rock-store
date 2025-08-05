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
        Schema::create('decadas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 10)->unique(); // '1960s', '1970s', etc.
            $table->integer('inicio'); // 1960
            $table->integer('fin'); // 1969
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decadas');
    }
};
