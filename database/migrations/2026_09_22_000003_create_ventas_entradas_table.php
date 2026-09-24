<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas_entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_entrada_id')->constrained('tipos_entrada')->cascadeOnDelete();
            $table->foreignId('promocion_id')->nullable()->constrained('promociones')->nullOnDelete();
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 8, 2); // copiado del tipo de entrada al momento de la venta
            $table->decimal('total', 10, 2); // ya con descuento aplicado si corresponde
            $table->timestamp('fecha_venta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas_entradas');
    }
};
