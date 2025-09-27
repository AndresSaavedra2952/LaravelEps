<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefono');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable();
            $table->text('direccion')->nullable();
            $table->unsignedBigInteger('eps_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // ❌ QUITAR ESTA LÍNEA TEMPORALMENTE:
            // $table->foreign('eps_id')->references('id')->on('eps');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
};