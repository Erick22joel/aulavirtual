<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuloController extends Controller
{
    public function index()
    {
        $modulos = Modulo::with('curso')
            ->orderBy('curso_id')
            ->orderBy('orden')
            ->paginate(10);

        $cursos = Curso::orderBy('titulo')->get();

        return view('admin.modulos.index', compact('modulos', 'cursos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => ['required', 'exists:cursos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'orden' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('modulos')->where(function ($query) use ($request) {
                    return $query->where('curso_id', $request->curso_id);
                }),
            ],
            'estado' => ['required', 'boolean'],
        ], [
            'curso_id.required' => 'El curso es obligatorio.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            'titulo.required' => 'El título del módulo es obligatorio.',
            'orden.required' => 'El orden del módulo es obligatorio.',
            'orden.unique' => 'Ya existe un módulo con ese orden dentro del curso seleccionado.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        Modulo::create($validated);

        return redirect()
            ->route('admin.modulos.index')
            ->with('success', 'Módulo registrado correctamente.');
    }

    public function update(Request $request, Modulo $modulo)
    {
        $validated = $request->validate([
            'curso_id' => ['required', 'exists:cursos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'orden' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('modulos')->where(function ($query) use ($request) {
                    return $query->where('curso_id', $request->curso_id);
                })->ignore($modulo->id),
            ],
            'estado' => ['required', 'boolean'],
        ], [
            'curso_id.required' => 'El curso es obligatorio.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            'titulo.required' => 'El título del módulo es obligatorio.',
            'orden.required' => 'El orden del módulo es obligatorio.',
            'orden.unique' => 'Ya existe un módulo con ese orden dentro del curso seleccionado.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $modulo->update($validated);

        return redirect()
            ->route('admin.modulos.index')
            ->with('success', 'Módulo actualizado correctamente.');
    }

    public function destroy(Modulo $modulo)
    {
        $modulo->delete();

        return redirect()
            ->route('admin.modulos.index')
            ->with('success', 'Módulo eliminado correctamente.');
    }
}
