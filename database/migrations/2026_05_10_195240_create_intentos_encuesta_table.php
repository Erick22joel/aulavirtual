<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intentos_encuesta', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscripcion_curso_id')
                ->constrained('inscripciones_cursos')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('encuesta_id')
                ->constrained('encuestas')
                ->cascadeOnDelete();

            $table->enum('tipo', ['diagnostica', 'final']);

            $table->decimal('puntaje_total', 8, 2)->nullable();
            $table->decimal('puntaje_maximo', 8, 2)->nullable();
            $table->decimal('porcentaje', 5, 2)->nullable();

            $table->enum('estado', ['iniciado', 'finalizado'])
                ->default('iniciado');

            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();

            $table->timestamps();

            $table->unique(
                ['inscripcion_curso_id', 'encuesta_id'],
                'intentos_inscripcion_encuesta_unique'
            );

            $table->index(['user_id', 'tipo']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intentos_encuesta');
    }
};
