<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Crear roles base del sistema
        |--------------------------------------------------------------------------
        */

        DB::table('roles')->updateOrInsert(
            ['slug' => 'hiperadmin'],
            [
                'nombre' => 'HiperAdmin',
                'descripcion' => 'Usuario con control total del sistema: gestiona usuarios, administradores, cursos, encuestas, resultados, gráficas y configuración general.',
                'estado' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['slug' => 'admin'],
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Usuario encargado de crear, modificar y administrar cursos, contenido multimedia, encuestas, usuarios normales y gráficas de evaluación.',
                'estado' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['slug' => 'usuario'],
            [
                'nombre' => 'Usuario normal',
                'descripcion' => 'Usuario que accede a los cursos, responde la encuesta diagnóstica inicial, visualiza el contenido multimedia y contesta la encuesta final de evaluación.',
                'estado' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Crear usuario HiperAdmin principal
        |--------------------------------------------------------------------------
        */

        $hiperAdminRoleId = DB::table('roles')
            ->where('slug', 'hiperadmin')
            ->value('id');

        DB::table('users')->updateOrInsert(
            ['email' => 'erick.carbajal@tesvb.edu.mx'],
            [
                'role_id' => $hiperAdminRoleId,
                'name' => 'Erick Joel Carbajal Zarza',
                'password' => Hash::make('Adicciones.20262026_tec'),
                'estado' => 'activo',
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'erick.carbajal@tesvb.edu.mx')
            ->delete();

        DB::table('roles')
            ->whereIn('slug', ['hiperadmin', 'admin', 'usuario'])
            ->delete();
    }
};
