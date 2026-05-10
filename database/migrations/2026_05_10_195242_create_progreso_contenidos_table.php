<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_contenidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscripcion_curso_id')
                ->constrained('inscripciones_cursos')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('contenido_id')
                ->constrained('contenidos')
                ->cascadeOnDelete();

            $table->enum('estado', [
                'pendiente',
                'visto',
                'completado'
            ])->default('pendiente');

            $table->decimal('porcentaje_visto', 5, 2)->default(0);

            $table->unsignedInteger('tiempo_segundos')->nullable();

            $table->timestamp('visto_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'contenido_id'], 'progreso_user_contenido_unique');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_contenidos');
    }
};
