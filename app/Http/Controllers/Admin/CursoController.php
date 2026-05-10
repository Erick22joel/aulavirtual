<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaAdiccion;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with(['categoria', 'creador'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categorias = CategoriaAdiccion::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.cursos.index', compact('cursos', 'categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_adiccion_id' => ['required', 'exists:categorias_adicciones,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'objetivo' => ['nullable', 'string'],
            'imagen_portada' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'estado' => ['required', 'in:borrador,publicado,inactivo'],
        ]);

        if ($request->hasFile('imagen_portada')) {
            $validated['imagen_portada'] = $request->file('imagen_portada')->store('cursos', 'public');
        }

        $validated['creado_por'] = auth()->id();
        $validated['slug'] = $this->generarSlugUnico($validated['titulo']);
        $validated['publicado_at'] = $validated['estado'] === 'publicado' ? now() : null;

        Curso::create($validated);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso registrado correctamente.');
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'categoria_adiccion_id' => ['required', 'exists:categorias_adicciones,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'objetivo' => ['nullable', 'string'],
            'imagen_portada' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'estado' => ['required', 'in:borrador,publicado,inactivo'],
        ]);

        if ($request->hasFile('imagen_portada')) {
            if ($curso->imagen_portada && Storage::disk('public')->exists($curso->imagen_portada)) {
                Storage::disk('public')->delete($curso->imagen_portada);
            }

            $validated['imagen_portada'] = $request->file('imagen_portada')->store('cursos', 'public');
        }

        $validated['slug'] = $this->generarSlugUnico($validated['titulo'], $curso->id);

        if ($validated['estado'] === 'publicado' && !$curso->publicado_at) {
            $validated['publicado_at'] = now();
        }

        if ($validated['estado'] !== 'publicado') {
            $validated['publicado_at'] = null;
        }

        $curso->update($validated);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Curso $curso)
    {
        if ($curso->imagen_portada && Storage::disk('public')->exists($curso->imagen_portada)) {
            Storage::disk('public')->delete($curso->imagen_portada);
        }

        $curso->delete();

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso eliminado correctamente.');
    }

    private function generarSlugUnico(string $titulo, ?int $cursoId = null): string
    {
        $slugBase = Str::slug($titulo);
        $slug = $slugBase;
        $contador = 1;

        while (
            Curso::where('slug', $slug)
            ->when($cursoId, function ($query) use ($cursoId) {
                $query->where('id', '!=', $cursoId);
            })
            ->exists()
        ) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        return $slug;
    }
}
