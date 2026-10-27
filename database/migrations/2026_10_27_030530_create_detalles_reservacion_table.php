<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_reservacion', function (Blueprint $table) {
            $table->id('id_detalle');

            // Apunta a 'id_reservacion' en reservaciones
            $table->foreignId('id_reservacion')
                  ->constrained('reservaciones', 'id_reservacion')
                  ->cascadeOnDelete();

            // Apunta a 'id_paquete' en paquetes_turisticos
            $table->foreignId('id_paquete')
                  ->nullable()
                  ->constrained('paquetes_turisticos', 'id_paquete')
                  ->nullOnDelete();

            // Apunta a 'id' en la tabla habitaciones
            $table->foreignId('id_habitacion')
                  ->nullable()
                  ->constrained('habitaciones', 'id')
                  ->nullOnDelete();

            // Apunta a 'id' en la tabla rutas_transporte
            $table->foreignId('id_ruta')
                  ->nullable()
                  ->constrained('rutas_transporte', 'id')
                  ->nullOnDelete();

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_reservacion');
    }
};
