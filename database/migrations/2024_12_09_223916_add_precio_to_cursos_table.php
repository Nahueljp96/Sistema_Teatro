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
    Schema::table('cursos', function (Blueprint $table) {
        $table->decimal('precio', 8, 2)->nullable();  // 8 dígitos en total, 2 después del punto decimal
        $table->string('imagen');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            //
        });
    }
};
