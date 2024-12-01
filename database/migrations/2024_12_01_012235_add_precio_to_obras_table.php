<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecioToObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->decimal('precio', 8, 2)->after('asientos_disponibles'); // Agrega el campo precio
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->dropColumn('precio'); // Elimina el campo precio en caso de revertir la migración
        });
    }
}
