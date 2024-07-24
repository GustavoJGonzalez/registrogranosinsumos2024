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
        Schema::create('recepcions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresas_id');
            $table->unsignedBigInteger('zafras_id')->nullable();
            $table->unsignedBigInteger('productos_id');
            $table->unsignedBigInteger('parcelas_id')->nullable();
            $table->unsignedBigInteger('silos_id')->nullable();
            $table->unsignedBigInteger('chofers_id');
            $table->unsignedBigInteger('transportadoras_id');
            $table->string('ci'); 
            $table->string('celular');
            $table->string('domicilio');
            $table->integer('pesoBruto')->nullable();
            $table->integer('pesoTara')->nullable();
            $table->integer('pesoNeto')->nullable();
            $table->string('chapaCamion');
            $table->string('chapaSemi');
            $table->decimal('humedad')->nullable();
            $table->decimal('impureza')->nullable();

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
        Schema::dropIfExists('recepcions');
    }
};
