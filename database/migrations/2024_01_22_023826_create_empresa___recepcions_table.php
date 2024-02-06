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
        Schema::create('empresa___recepcions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recepcion_id');
            $table->unsignedBigInteger('empresa_id');
            // Otras columnas adicionales que puedan ser necesarias para esta relación

            $table->foreign('recepcion_id')->references('id')->on('recepcions')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

            // Establecer clave única compuesta para evitar duplicados
            $table->unique(['recepcion_id', 'empresa_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa___recepcions');
    }
};
