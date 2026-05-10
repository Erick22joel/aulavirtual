<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('modulo_id')
                ->constrained('modulos')
                ->cascadeOnDelete();

            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->enum('tipo', [
                'video',
                'pdf',
                'imagen',
                'texto',
                'enlace',
                'presentacion'
            ]);

            $table->longText('contenido_texto')->nullable();
            $table->string('url')->nullable();
            $table->string('archivo')->nullable();

            $table->unsignedInteger('duracion_estimada')->nullable();
            $table->unsignedInteger('orden')->default(1);

            $table->boolean('obligatorio')->default(true);
            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->unique(['modulo_id', 'orden'], 'contenidos_modulo_orden_unique');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenidos');
    }
};
