<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaAdiccion;
use Illuminate\Http\Request;

class CategoriaAdiccionController extends Controller
{
    public function index()
    {
        $categorias = CategoriaAdiccion::orderBy('id', 'desc')->paginate(10);

        return view('admin.categorias_adicciones.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias_adicciones,nombre'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', 'boolean'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        CategoriaAdiccion::create($validated);

        return redirect()
            ->route('admin.categorias-adicciones.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    public function update(Request $request, CategoriaAdiccion $categoria)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias_adicciones,nombre,' . $categoria->id],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', 'boolean'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $categoria->update($validated);

        return redirect()
            ->route('admin.categorias-adicciones.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(CategoriaAdiccion $categoria)
    {
        $categoria->delete();

        return redirect()
            ->route('admin.categorias-adicciones.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
