<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->role) {
            abort(403, 'El usuario no tiene un rol asignado.');
        }

        return match ($user->role->slug) {
            'hiperadmin' => redirect()->route('hiperadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'usuario' => redirect()->route('usuario.dashboard'),
            default => abort(403, 'Rol no autorizado.'),
        };
    }
}
