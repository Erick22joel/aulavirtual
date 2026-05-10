<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_encuesta', function (Blueprint $table) {
            $table->id();

            $table->foreignId('intento_encuesta_id')
                ->constrained('intentos_encuesta')
                ->cascadeOnDelete();

            $table->foreignId('pregunta_id')
                ->constrained('preguntas')
                ->cascadeOnDelete();

            $table->foreignId('opcion_pregunta_id')
                ->nullable()
                ->constrained('opciones_pregunta')
                ->nullOnDelete();

            $table->text('respuesta_texto')->nullable();

            $table->decimal('valor_obtenido', 8, 2)->nullable();

            $table->timestamps();

            $table->index('pregunta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_encuesta');
    }
};
