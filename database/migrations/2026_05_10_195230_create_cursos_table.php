<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_adiccion_id')
                ->constrained('categorias_adicciones')
                ->cascadeOnDelete();

            $table->foreignId('creado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descripcion');
            $table->text('objetivo')->nullable();
            $table->string('imagen_portada')->nullable();

            $table->enum('estado', ['borrador', 'publicado', 'inactivo'])
                ->default('borrador');

            $table->timestamp('publicado_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
