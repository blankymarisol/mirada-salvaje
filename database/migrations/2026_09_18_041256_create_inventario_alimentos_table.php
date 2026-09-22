<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_alimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_alimento', 100);
            $table->string('tipo', 50)->nullable();
            $table->string('unidad_medida', 20)->default('kg');
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_alimentos');
    }
};