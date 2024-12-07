<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->string('estado_pago')->change(); // Cambiar de ENUM a VARCHAR
        });
    }

    public function down()
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->enum('estado_pago', ['pendiente', 'pagado', 'fallido'])->change(); // Revertir el cambio
        });
    }

};
