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
        Schema::create('control_accesos', function (Blueprint $table) {
            $table->id();
            $table->string('operacion');
            $table->unsignedBigInteger('empresas_id');
            $table->unsignedBigInteger('empresa_clientes_id');
            $table->unsignedBigInteger('productos_id')->nullable();
            $table->unsignedBigInteger('insumos_id')->nullable();
            $table->unsignedBigInteger('chofers_id');
            $table->string('ci'); 
            $table->string('celular');
            $table->string('domicilio');
            $table->string('colorCamion');
            $table->string('ejesCamion');
            $table->string('chapaCamion');
            $table->string('colorSemi');
            $table->string('ejesSemi');
            $table->string('chapaSemi');
            $table->unsignedBigInteger('transportadoras_id');
            $table->date('fecha_ingreso');
            $table->time('hora_ingreso');
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('control_accesos');
    }
};
