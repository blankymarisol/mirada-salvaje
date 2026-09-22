<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dietas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animales')->cascadeOnDelete();
            // Nota: sin ->constrained() todavía porque la tabla inventario_alimentos
            // se crea en un paso posterior. La columna queda lista para usarse igual.
            $table->foreignId('inventario_alimento_id');
            $table->string('nombre', 100);
            $table->decimal('cantidad_racion', 8, 2);
            $table->unsignedTinyInteger('frecuencia_diaria')->default(1);
            $table->text('observaciones')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dietas');
    }
};