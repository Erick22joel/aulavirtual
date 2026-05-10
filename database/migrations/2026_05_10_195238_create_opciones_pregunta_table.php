<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opciones_pregunta', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pregunta_id')
                ->constrained('preguntas')
                ->cascadeOnDelete();

            $table->string('texto');
            $table->decimal('valor', 8, 2)->nullable();

            $table->boolean('es_correcta')
                ->nullable();

            $table->unsignedInteger('orden')->default(1);

            $table->timestamps();

            $table->unique(['pregunta_id', 'orden'], 'opciones_pregunta_orden_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opciones_pregunta');
    }
};
