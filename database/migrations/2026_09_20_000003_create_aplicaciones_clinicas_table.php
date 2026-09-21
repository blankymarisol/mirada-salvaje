<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplicaciones_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animales')->cascadeOnDelete();
            $table->enum('tipo', ['medicamento', 'vacuna']);
            $table->foreignId('medicamento_id')->nullable()->constrained('medicamentos')->nullOnDelete();
            $table->foreignId('vacuna_id')->nullable()->constrained('vacunas')->nullOnDelete();
            $table->date('fecha_aplicacion');
            $table->string('dosis', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->date('proxima_dosis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicaciones_clinicas');
    }
};