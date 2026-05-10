<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('carrera_id')
                ->constrained('carreras')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->unsignedTinyInteger('semestre')->nullable();

            $table->enum('turno', ['matutino', 'vespertino', 'mixto'])
                ->nullable();

            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->unique(['carrera_id', 'nombre', 'semestre'], 'grupos_carrera_nombre_semestre_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
