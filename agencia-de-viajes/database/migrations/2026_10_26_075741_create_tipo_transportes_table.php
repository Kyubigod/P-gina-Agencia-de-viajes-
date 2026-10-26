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
    Schema::create('tipos_transporte', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_tipo')->unique();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('tipos_transporte');
}
};
