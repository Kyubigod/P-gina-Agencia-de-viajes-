<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id('id_reservacion'); 
            $table->foreignId('id_cliente')->constrained('clientes', 'id')->onDelete('cascade');
            $table->date('fecha_reservacion');
           $table->enum('estado', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');
            $table->decimal('monto_total', 10, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};