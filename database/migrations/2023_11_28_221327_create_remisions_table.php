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
        Schema::create('remisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_clientes_id');
            $table->unsignedBigInteger('empresas_id');
            $table->unsignedBigInteger('zafras_id');
            $table->unsignedBigInteger('productos_id');
            $table->unsignedBigInteger('transportadoras_id');
            $table->string('chofer');
            $table->integer('pesoTara');
            $table->integer('pesoBruto');
            $table->integer('pesoNeto');
            $table->string('chapaCamion');
            $table->string('chapaSemi');
            $table->decimal('humedad');
            $table->decimal('impureza');
            $table->date('fecha_registro');
            $table->time('hora_registro');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remisions');
    }
};
