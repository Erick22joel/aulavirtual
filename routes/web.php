<?php

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriaAdiccionController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\ModuloController;


Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Redirección general después del login
|--------------------------------------------------------------------------
| Breeze manda al usuario a /dashboard.
| Aquí detectamos su rol y lo enviamos a su panel correspondiente.
*/
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Panel HiperAdmin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:hiperadmin'])
    ->prefix('hiperadmin')
    ->name('hiperadmin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('hiperadmin.dashboard');
        })->name('dashboard');
    });



/*
|--------------------------------------------------------------------------
| Panel Administrador
|--------------------------------------------------------------------------
| El HiperAdmin también puede entrar al panel de administrador.
*/

Route::middleware(['auth', 'verified', 'role:admin,hiperadmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('categorias-adicciones', CategoriaAdiccionController::class)
            ->parameters(['categorias-adicciones' => 'categoria'])
            ->except(['create', 'edit', 'show']);

        Route::resource('cursos', CursoController::class)
            ->parameters(['cursos' => 'curso'])
            ->except(['create', 'edit', 'show']);

        Route::resource('modulos', ModuloController::class)
            ->parameters(['modulos' => 'modulo'])
            ->except(['create', 'edit', 'show']);
    });

/*
|--------------------------------------------------------------------------
| Panel Usuario Normal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:usuario'])
    ->prefix('usuario')
    ->name('usuario.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('usuario.dashboard');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Perfil de usuario Breeze
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
