<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paquete_destinos', function (Blueprint $table) {
            $table->foreignId('id_paquete')->constrained('paquetes_turisticos', 'id_paquete')->cascadeOnDelete();
            $table->foreignId('id_ciudad')->constrained('ciudades', 'id')->cascadeOnDelete();
            $table->primary(['id_paquete', 'id_ciudad']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquete_destinos');
    }
};