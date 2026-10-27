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
    Schema::create('hoteles', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_hotel');
        $table->string('direccion');
        $table->unsignedTinyInteger('estrellas');
        $table->foreignId('ciudad_id')->constrained('ciudades');
        $table->unique(['nombre_hotel', 'ciudad_id']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoteles');
    }
};
