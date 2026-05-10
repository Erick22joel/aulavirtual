<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfiles_usuarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('carrera_id')
                ->nullable()
                ->constrained('carreras')
                ->nullOnDelete();

            $table->foreignId('grupo_id')
                ->nullable()
                ->constrained('grupos')
                ->nullOnDelete();

            $table->string('matricula')->nullable()->unique();
            $table->string('telefono', 20)->nullable();
            $table->unsignedTinyInteger('edad')->nullable();

            $table->enum('sexo', [
                'masculino',
                'femenino',
                'otro',
                'prefiero_no_decirlo'
            ])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfiles_usuarios');
    }
};
