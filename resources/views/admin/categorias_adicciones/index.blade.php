<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorías de Adicciones
            </h2>

            <button
                onclick="document.getElementById('modalCrear').classList.remove('hidden')"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Nueva categoría
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
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Descripción</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categorias as $categoria)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $categoria->id }}</td>

                            <td class="px-6 py-4 font-semibold">
                                {{ $categoria->nombre }}
                            </td>

                            <td class="px-6 py-4">
                                {{ Str::limit($categoria->descripcion, 60) }}
                            </td>

                            <td class="px-6 py-4">
                                @if($categoria->estado)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    Activa
                                </span>
                                @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    Inactiva
                                </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button
                                        onclick="document.getElementById('modalVer{{ $categoria->id }}').classList.remove('hidden')"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Ver
                                    </button>

                                    <button
                                        onclick="document.getElementById('modalEditar{{ $categoria->id }}').classList.remove('hidden')"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                        Editar
                                    </button>

                                    <button
                                        onclick="document.getElementById('modalEliminar{{ $categoria->id }}').classList.remove('hidden')"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL VER --}}
                        <div id="modalVer{{ $categoria->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                                <h3 class="text-xl font-bold mb-4">Detalle de categoría</h3>

                                <p class="mb-2">
                                    <strong>Nombre:</strong> {{ $categoria->nombre }}
                                </p>

                                <p class="mb-2">
                                    <strong>Descripción:</strong>
                                    {{ $categoria->descripcion ?? 'Sin descripción registrada.' }}
                                </p>

                                <p class="mb-4">
                                    <strong>Estado:</strong>
                                    {{ $categoria->estado ? 'Activa' : 'Inactiva' }}
                                </p>

                                <div class="flex justify-end">
                                    <button
                                        onclick="document.getElementById('modalVer{{ $categoria->id }}').classList.add('hidden')"
                                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL EDITAR --}}
                        <div id="modalEditar{{ $categoria->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                                <h3 class="text-xl font-bold mb-4">Editar categoría</h3>

                                <form action="{{ route('admin.categorias-adicciones.update', $categoria) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nombre
                                        </label>
                                        <input
                                            type="text"
                                            name="nombre"
                                            value="{{ old('nombre', $categoria->nombre) }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Descripción
                                        </label>
                                        <textarea
                                            name="descripcion"
                                            rows="4"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Estado
                                        </label>
                                        <select
                                            name="estado"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="1" {{ $categoria->estado ? 'selected' : '' }}>Activa</option>
                                            <option value="0" {{ !$categoria->estado ? 'selected' : '' }}>Inactiva</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            onclick="document.getElementById('modalEditar{{ $categoria->id }}').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                            Cancelar
                                        </button>

                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                            Guardar cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- MODAL ELIMINAR --}}
                        <div id="modalEliminar{{ $categoria->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                            <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
                                <h3 class="text-xl font-bold text-red-600 mb-4">
                                    Confirmar eliminación
                                </h3>

                                <p class="text-gray-700 mb-6">
                                    ¿Seguro que deseas eliminar la categoría
                                    <strong>{{ $categoria->nombre }}</strong>?
                                </p>

                                <form action="{{ route('admin.categorias-adicciones.destroy', $categoria) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            onclick="document.getElementById('modalEliminar{{ $categoria->id }}').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                            Cancelar
                                        </button>

                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                            Eliminar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No hay categorías registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $categorias->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR --}}
    <div id="modalCrear" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Nueva categoría de adicción</h3>

            <form action="{{ route('admin.categorias-adicciones.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre
                    </label>
                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción
                    </label>
                    <textarea
                        name="descripcion"
                        rows="4"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('descripcion') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select
                        name="estado"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="document.getElementById('modalCrear').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>