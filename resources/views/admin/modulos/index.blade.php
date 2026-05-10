<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Módulos de Cursos
                </h2>
                <p class="text-sm text-gray-500">
                    Organiza los cursos en secciones o unidades de aprendizaje.
                </p>
            </div>

            <button
                type="button"
                onclick="abrirModalCrear()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Nuevo módulo
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
                            <th class="px-6 py-3">Curso</th>
                            <th class="px-6 py-3">Módulo</th>
                            <th class="px-6 py-3">Orden</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($modulos as $modulo)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                {{ $modulo->id }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">
                                    {{ $modulo->curso->titulo ?? 'Sin curso' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">
                                    {{ $modulo->titulo }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ Str::limit($modulo->descripcion, 70) }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                {{ $modulo->orden }}
                            </td>

                            <td class="px-6 py-4">
                                @if($modulo->estado)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    Activo
                                </span>
                                @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    Inactivo
                                </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="abrirModalVer(
                                                '{{ addslashes($modulo->curso->titulo ?? 'Sin curso') }}',
                                                '{{ addslashes($modulo->titulo) }}',
                                                '{{ addslashes($modulo->descripcion ?? 'Sin descripción registrada.') }}',
                                                '{{ $modulo->orden }}',
                                                '{{ $modulo->estado ? 'Activo' : 'Inactivo' }}'
                                            )"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Ver
                                    </button>

                                    <button
                                        type="button"
                                        onclick="abrirModalEditar(
                                                '{{ route('admin.modulos.update', $modulo) }}',
                                                '{{ $modulo->curso_id }}',
                                                '{{ addslashes($modulo->titulo) }}',
                                                '{{ addslashes($modulo->descripcion ?? '') }}',
                                                '{{ $modulo->orden }}',
                                                '{{ $modulo->estado }}'
                                            )"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                        Editar
                                    </button>

                                    <button
                                        type="button"
                                        onclick="abrirModalEliminar(
                                                '{{ route('admin.modulos.destroy', $modulo) }}',
                                                '{{ addslashes($modulo->titulo) }}'
                                            )"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No hay módulos registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $modulos->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR --}}
    <div id="modalCrearModulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Nuevo módulo</h3>

            <form action="{{ route('admin.modulos.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Curso
                    </label>
                    <select name="curso_id" class="w-full border-gray-300 rounded-lg" required>
                        <option value="">Selecciona un curso</option>
                        @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">
                            {{ $curso->titulo }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Título del módulo
                    </label>
                    <input type="text" name="titulo" class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción
                    </label>
                    <textarea name="descripcion" rows="3" class="w-full border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Orden
                    </label>
                    <input type="number" name="orden" min="1" value="1" class="w-full border-gray-300 rounded-lg" required>
                    <p class="text-xs text-gray-500 mt-1">
                        El orden no debe repetirse dentro del mismo curso.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select name="estado" class="w-full border-gray-300 rounded-lg" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2 bg-gray-500 text-white rounded">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                        Guardar módulo
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL VER --}}
    <div id="modalVerModulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Detalle del módulo</h3>

            <p class="mb-2"><strong>Curso:</strong> <span id="verCurso"></span></p>
            <p class="mb-2"><strong>Módulo:</strong> <span id="verTitulo"></span></p>
            <p class="mb-2"><strong>Descripción:</strong> <span id="verDescripcion"></span></p>
            <p class="mb-2"><strong>Orden:</strong> <span id="verOrden"></span></p>
            <p class="mb-4"><strong>Estado:</strong> <span id="verEstado"></span></p>

            <div class="flex justify-end">
                <button type="button" onclick="cerrarModalVer()" class="px-4 py-2 bg-gray-600 text-white rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR --}}
    <div id="modalEditarModulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Editar módulo</h3>

            <form id="formEditarModulo" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Curso
                    </label>
                    <select id="editarCurso" name="curso_id" class="w-full border-gray-300 rounded-lg" required>
                        @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">
                            {{ $curso->titulo }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Título del módulo
                    </label>
                    <input id="editarTitulo" type="text" name="titulo" class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción
                    </label>
                    <textarea id="editarDescripcion" name="descripcion" rows="3" class="w-full border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Orden
                    </label>
                    <input id="editarOrden" type="number" name="orden" min="1" class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select id="editarEstado" name="estado" class="w-full border-gray-300 rounded-lg" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
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
    <div id="modalEliminarModulo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">
                Confirmar eliminación
            </h3>

            <p class="text-gray-700 mb-6">
                ¿Seguro que deseas eliminar el módulo <strong id="eliminarTitulo"></strong>?
            </p>

            <form id="formEliminarModulo" method="POST">
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
            document.getElementById('modalCrearModulo').classList.remove('hidden');
        }

        function cerrarModalCrear() {
            document.getElementById('modalCrearModulo').classList.add('hidden');
        }

        function abrirModalVer(curso, titulo, descripcion, orden, estado) {
            document.getElementById('verCurso').textContent = curso;
            document.getElementById('verTitulo').textContent = titulo;
            document.getElementById('verDescripcion').textContent = descripcion;
            document.getElementById('verOrden').textContent = orden;
            document.getElementById('verEstado').textContent = estado;

            document.getElementById('modalVerModulo').classList.remove('hidden');
        }

        function cerrarModalVer() {
            document.getElementById('modalVerModulo').classList.add('hidden');
        }

        function abrirModalEditar(url, cursoId, titulo, descripcion, orden, estado) {
            document.getElementById('formEditarModulo').action = url;
            document.getElementById('editarCurso').value = cursoId;
            document.getElementById('editarTitulo').value = titulo;
            document.getElementById('editarDescripcion').value = descripcion;
            document.getElementById('editarOrden').value = orden;
            document.getElementById('editarEstado').value = estado;

            document.getElementById('modalEditarModulo').classList.remove('hidden');
        }

        function cerrarModalEditar() {
            document.getElementById('modalEditarModulo').classList.add('hidden');
        }

        function abrirModalEliminar(url, titulo) {
            document.getElementById('formEliminarModulo').action = url;
            document.getElementById('eliminarTitulo').textContent = titulo;

            document.getElementById('modalEliminarModulo').classList.remove('hidden');
        }

        function cerrarModalEliminar() {
            document.getElementById('modalEliminarModulo').classList.add('hidden');
        }
    </script>
</x-app-layout>