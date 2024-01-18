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
        Schema::create('empresa_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresas_id');
            $table->unsignedBigInteger('users_id');
            // Otras columnas adicionales que puedan ser necesarias para esta relación

            $table->foreign('empresas_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            // Establecer clave única compuesta para evitar duplicados
            $table->unique(['empresas_id', 'users_id']);
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_user');
    }
};
