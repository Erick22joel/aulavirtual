<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->cascadeOnDelete();

            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->text('instrucciones')->nullable();

            $table->enum('tipo', ['diagnostica', 'final']);

            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->unique(['curso_id', 'tipo'], 'encuestas_curso_tipo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};
