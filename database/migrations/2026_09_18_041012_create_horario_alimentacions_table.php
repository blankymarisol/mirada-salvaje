<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_alimentacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dieta_id')->constrained('dietas')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_programada');
            $table->decimal('cantidad', 8, 2)->nullable();
            $table->boolean('registrado')->default(false);
            $table->timestamp('registrado_en')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_alimentacion');
    }
};