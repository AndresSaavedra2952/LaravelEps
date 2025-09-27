<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Agregar las columnas que faltan
        Schema::table('consultorios', function (Blueprint $table) {
            $table->string('nombre')->after('id');
            $table->string('ubicacion')->after('nombre');
            $table->string('telefono')->after('ubicacion');
            $table->boolean('activo')->default(true)->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('consultorios', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'ubicacion', 'telefono', 'activo']);
        });
    }
};