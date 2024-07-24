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
        Schema::create('recepcion_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_clientes_id');
            $table->unsignedBigInteger('empresas_id');
            $table->unsignedBigInteger('insumos_id');
       
            $table->string('numeroRemision');
            $table->unsignedBigInteger('chofers_id');
            $table->string('ci'); 
            $table->string('celular');
            $table->string('domicilio');
            $table->integer('pesoBruto')->nullable();
            $table->integer('pesoTara')->nullable();
            $table->integer('pesoNeto')->nullable();
            $table->string('chapaCamion');
            $table->string('chapaSemi');
            
            $table->date('fecha_ingreso');
            $table->time('hora_ingreso');

            $table->date('fecha_ingresoB')->nullable();
            $table->time('hora_ingresoB')->nullable();

            $table->boolean('is_approved')->default(false);

            $table->date('fecha_salidaB')->nullable();
            $table->time('hora_salidaB')->nullable();

            $table->date('fecha_salida')->nullable();
            $table->time('hora_salida')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepcion_insumos');
    }
};
