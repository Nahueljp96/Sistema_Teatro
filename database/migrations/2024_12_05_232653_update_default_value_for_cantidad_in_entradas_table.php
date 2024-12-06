<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->integer('cantidad')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            // Revertimos el cambio quitando el valor por defecto
            $table->integer('cantidad')->change();
        });
    }
};
