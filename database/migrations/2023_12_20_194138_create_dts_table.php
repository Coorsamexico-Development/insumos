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
        Schema::create('dts', function (Blueprint $table) {
            $table->id();
            $table->string('referencia')->unique();
            $table->foreignId('cliente_id')->constrained('clientes');
            //$table->foreignId('salida_id')->constrained('salidas');
            $table->foreignId('stage_id')->constrained('stages');
            $table->foreignId('destino_id')->nullable()->constrained('destinos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dts');
    }
};
