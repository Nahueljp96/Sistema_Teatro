<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos_ventas', function (Blueprint $table) {
            $table->id('curso_venta_id'); // Primary key
            $table->unsignedBigInteger('curso_id'); // Relación con la tabla cursos
            $table->string('comprador_email');
            $table->integer('cantidad');
            $table->string('nombre_comprador');
            $table->string('estado_pago');
            $table->string('telefono')->nullable();
            $table->string('preference_id')->unique();
            $table->timestamps();

            // Clave foránea
            $table->foreign('curso_id')
                ->references('id')
                ->on('cursos')
                ->onDelete('cascade'); // Si se elimina un curso, también se eliminan sus ventas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursos_ventas');
    }
};
