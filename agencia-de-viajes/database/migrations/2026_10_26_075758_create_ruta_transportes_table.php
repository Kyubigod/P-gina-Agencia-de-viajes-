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
    Schema::create('rutas_transporte', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tipo_transporte_id')->constrained('tipos_transporte')->restrictOnDelete();
        $table->foreignId('ciudad_origen_id')->constrained('ciudades')->restrictOnDelete();
        $table->foreignId('ciudad_destino_id')->constrained('ciudades')->restrictOnDelete();
        $table->decimal('precio_trayecto', 10, 2);
        $table->dateTime('fecha_salida');
        $table->decimal('duracion_horas', 4, 1);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('rutas_transporte');
}
};
