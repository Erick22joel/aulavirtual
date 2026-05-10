<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestión de Cursos
                </h2>
                <p class="text-sm text-gray-500">
                    Administra los cursos de concientización sobre adicciones.
                </p>
            </div>

            <button
                type="button"
                onclick="abrirModalCrear()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Nuevo curso
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Portada</th>
                            <th class="px-6 py-3">Curso</th>
                            <th class="px-6 py-3">Categoría</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Creado por</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($cursos as $curso)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $curso->id }}</td>

                            <td class="px-6 py-4">
                                @if($curso->imagen_portada)
                                <img
                                    src="{{ asset('storage/' . $curso->imagen_portada) }}"
                                    alt="Portada"
                                    class="w-16 h-16 object-cover rounded-lg">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">
                                    Sin imagen
                                </div>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">
                                    {{ $curso->titulo }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $curso->slug }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                {{ $curso->categoria->nombre ?? 'Sin categoría' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($curso->estado === 'publicado')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    Publicado
                                </span>
                                @elseif($curso->estado === 'borrador')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                    Borrador
                                </span>
                                @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    Inactivo
                                </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                {{ $curso->creador->name ?? 'No registrado' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="abrirModalVer(
                                                '{{ addslashes($curso->titulo) }}',
                                                '{{ addslashes($curso->categoria->nombre ?? 'Sin categoría') }}',
                                                '{{ addslashes($curso->descripcion) }}',
                                                '{{ addslashes($curso->objetivo ?? 'Sin objetivo registrado') }}',
                                                '{{ $curso->estado }}'
                                            )"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Ver
                                    </button>

                                    <button
                                        type="button"
                                        onclick="abrirModalEditar(
                                                '{{ route('admin.cursos.update', $curso) }}',
                                                '{{ $curso->categoria_adiccion_id }}',
                                                '{{ addslashes($curso->titulo) }}',
                                                '{{ addslashes($curso->descripcion) }}',
                                                '{{ addslashes($curso->objetivo ?? '') }}',
                                                '{{ $curso->estado }}'
                                            )"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                        Editar
                                    </button>

                                    <button
                                        type="button"
                                        onclick="abrirModalEliminar(
                                                '{{ route('admin.cursos.destroy', $curso) }}',
                                                '{{ addslashes($curso->titulo) }}'
                                            )"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No hay cursos registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $cursos->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR --}}
    <div id="modalCrearCurso" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4">Nuevo curso</h3>

            <form action="{{ route('admin.cursos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="categoria_adiccion_id" class="w-full border-gray-300 rounded-lg" required>
                        <option value="">Selecciona una categoría</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="titulo" class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="4" class="w-full border-gray-300 rounded-lg" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
                    <textarea name="objetivo" rows="3" class="w-full border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen de portada</label>
                    <input type="file" name="imagen_portada" accept="image/*" class="w-full border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full border-gray-300 rounded-lg" required>
                        <option value="borrador">Borrador</option>
                        <option value="publicado">Publicado</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                        Guardar curso
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL VER --}}
    <div id="modalVerCurso" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Detalle del curso</h3>

            <p class="mb-2"><strong>Título:</strong> <span id="verTitulo"></span></p>
            <p class="mb-2"><strong>Categoría:</strong> <span id="verCategoria"></span></p>
            <p class="mb-2"><strong>Descripción:</strong> <span id="verDescripcion"></span></p>
            <p class="mb-2"><strong>Objetivo:</strong> <span id="verObjetivo"></span></p>
            <p class="mb-4"><strong>Estado:</strong> <span id="verEstado"></span></p>

            <div class="flex justify-end">
                <button type="button" onclick="cerrarModalVer()" class="px-4 py-2 bg-gray-600 text-white rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR --}}
    <div id="modalEditarCurso" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4">Editar curso</h3>

            <form id="formEditarCurso" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select id="editarCategoria" name="categoria_adiccion_id" class="w-full border-gray-300 rounded-lg" required>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input id="editarTitulo" type="text" name="titulo" class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="editarDescripcion" name="descripcion" rows="4" class="w-full border-gray-300 rounded-lg" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
                    <textarea id="editarObjetivo" name="objetivo" rows="3" class="w-full border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cambiar imagen de portada</label>
                    <input type="file" name="imagen_portada" accept="image/*" class="w-full border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="editarEstado" name="estado" class="w-full border-gray-300 rounded-lg" required>
                        <option value="borrador">Borrador</option>
                        <option value="publicado">Publicado</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ELIMINAR --}}
    <div id="modalEliminarCurso" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">Confirmar eliminación</h3>

            <p class="text-gray-700 mb-6">
                ¿Seguro que deseas eliminar el curso <strong id="eliminarTitulo"></strong>?
            </p>

            <form id="formEliminarCurso" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="cerrarModalEliminar()" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalCrear() {
            document.getElementById('modalCrearCurso').classList.remove('hidden');
        }

        function cerrarModalCrear() {
            document.getElementById('modalCrearCurso').classList.add('hidden');
        }

        function abrirModalVer(titulo, categoria, descripcion, objetivo, estado) {
            document.getElementById('verTitulo').textContent = titulo;
            document.getElementById('verCategoria').textContent = categoria;
            document.getElementById('verDescripcion').textContent = descripcion;
            document.getElementById('verObjetivo').textContent = objetivo;
            document.getElementById('verEstado').textContent = estado;

            document.getElementById('modalVerCurso').classList.remove('hidden');
        }

        function cerrarModalVer() {
            document.getElementById('modalVerCurso').classList.add('hidden');
        }

        function abrirModalEditar(url, categoriaId, titulo, descripcion, objetivo, estado) {
            document.getElementById('formEditarCurso').action = url;
            document.getElementById('editarCategoria').value = categoriaId;
            document.getElementById('editarTitulo').value = titulo;
            document.getElementById('editarDescripcion').value = descripcion;
            document.getElementById('editarObjetivo').value = objetivo;
            document.getElementById('editarEstado').value = estado;

            document.getElementById('modalEditarCurso').classList.remove('hidden');
        }

        function cerrarModalEditar() {
            document.getElementById('modalEditarCurso').classList.add('hidden');
        }

        function abrirModalEliminar(url, titulo) {
            document.getElementById('formEliminarCurso').action = url;
            document.getElementById('eliminarTitulo').textContent = titulo;

            document.getElementById('modalEliminarCurso').classList.remove('hidden');
        }

        function cerrarModalEliminar() {
            document.getElementById('modalEliminarCurso').classList.add('hidden');
        }
    </script>
</x-app-layout>