<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paquetes_turisticos', function (Blueprint $table) {
            $table->id('id_paquete');
            $table->string('nombre_paquete');
            $table->text('descripcion');
            $table->integer('duracion_dias');
            $table->decimal('precio_total',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquetes_turisticos');
    }
};
