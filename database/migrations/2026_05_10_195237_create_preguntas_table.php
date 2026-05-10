<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('encuesta_id')
                ->constrained('encuestas')
                ->cascadeOnDelete();

            $table->text('texto');

            $table->enum('tipo', [
                'opcion_unica',
                'opcion_multiple',
                'escala',
                'abierta',
                'verdadero_falso'
            ])->default('opcion_unica');

            $table->decimal('puntaje_maximo', 8, 2)->nullable();

            $table->unsignedInteger('orden')->default(1);
            $table->boolean('requerida')->default(true);
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->unique(['encuesta_id', 'orden'], 'preguntas_encuesta_orden_unique');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
