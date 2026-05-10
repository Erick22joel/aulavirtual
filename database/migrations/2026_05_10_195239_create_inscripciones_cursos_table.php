<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones_cursos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->cascadeOnDelete();

            $table->enum('estado', [
                'inscrito',
                'en_progreso',
                'finalizado',
                'bloqueado'
            ])->default('inscrito');

            $table->decimal('progreso_porcentaje', 5, 2)->default(0);

            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'curso_id'], 'inscripciones_user_curso_unique');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones_cursos');
    }
};
