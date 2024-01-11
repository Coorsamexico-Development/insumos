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
        Schema::create('corte_mensual_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorias_producto_id')->constrained('categorias_productos');
            $table->dateTime('fecha'); //fehca y hr
            $table->integer('cantidad_inicial');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_mensual_historicos');
    }
};
