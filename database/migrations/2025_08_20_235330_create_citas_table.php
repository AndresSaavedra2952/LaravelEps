<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('medico_id');
            $table->date('fecha'); // ← AGREGAR ESTA LÍNEA
            $table->time('hora'); // ← AGREGAR ESTA LÍNEA
            $table->text('motivo');
            $table->enum('estado', ['programada', 'confirmada', 'completada', 'cancelada'])->default('programada');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->foreign('medico_id')->references('id')->on('medicos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};