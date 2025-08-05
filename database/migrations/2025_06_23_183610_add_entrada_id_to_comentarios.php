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
        Schema::table('comentarios', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('entrada_id');
            //
            $table->foreign('entrada_id')->references('id')->on('entradas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            //
            $table->dropForeign('entrada_id');
            $table->dropColumn('entrada_id');
        });
    }
};
