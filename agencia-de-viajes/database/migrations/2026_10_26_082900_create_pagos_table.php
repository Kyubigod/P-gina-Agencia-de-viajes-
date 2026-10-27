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
    Schema::create('pagos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reservacion_id')
            ->constrained('reservaciones', 'id_reservacion')
            ->restrictOnDelete();
        $table->enum('metodo_pago', ['Efectivo', 'Tarjeta', 'Transferencia']);
        $table->decimal('monto', 10, 2);
        $table->date('fecha_pago');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('pagos');
}
};
