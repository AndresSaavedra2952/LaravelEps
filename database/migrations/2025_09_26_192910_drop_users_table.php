<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Eliminar las tablas que ya existen
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }

    public function down()
    {
        // No necesitamos rollback
    }
};