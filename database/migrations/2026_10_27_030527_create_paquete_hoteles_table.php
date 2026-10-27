<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paquete_hoteles', function (Blueprint $table) {
            // Apunta explícitamente a 'id_paquete' en la tabla paquetes_turisticos
            $table->foreignId('id_paquete')
                  ->constrained('paquetes_turisticos', 'id_paquete')
                  ->cascadeOnDelete();

            // Apunta al 'id' por defecto de la tabla hoteles
            $table->foreignId('id_hotel')
                  ->constrained('hoteles', 'id')
                  ->cascadeOnDelete();

            // Llave primaria compuesta
            $table->primary(['id_paquete', 'id_hotel']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquete_hoteles');
    }
};
