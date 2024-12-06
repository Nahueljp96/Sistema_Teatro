<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('entradas', function (Blueprint $table) {
        $table->string('telefono')->nullable();
        $table->string('comprador_email')->change(); // Si necesitas cambiar el tipo de este campo
        $table->string('nombre_comprador')->nullable();
        $table->enum('estado_pago', ['pendiente', 'pagado', 'fallido'])->default('pendiente');
    });
}

public function down(): void
{
    Schema::table('entradas', function (Blueprint $table) {
        $table->dropColumn('telefono');
        $table->dropColumn('nombre_comprador');
        $table->dropColumn('estado_pago');
    });
}

};
