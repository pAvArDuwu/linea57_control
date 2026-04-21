<!-- resources/views/conductores/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Conductor: :name', ['name' => $conductor->nombre . ' ' . $conductor->apellido]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información del Conductor</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">ID:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->id }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Nombre:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->nombre }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Apellido:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->apellido }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Teléfono:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->telefono }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Correo:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->correo }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">CI:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->ci }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Creado:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Actualizado:</strong>
                            <p class="text-gray-900 dark:text-gray-100">{{ $conductor->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('conductores.index') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Volver</a>
                        <a href="{{ route('conductores.edit', $conductor->id) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>