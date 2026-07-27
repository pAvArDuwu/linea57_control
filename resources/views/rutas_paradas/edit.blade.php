<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Editar Asociación de Ruta y Parada</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Modificar Datos de la Asociación</h5>
                <form action="{{ route('rutas-paradas.update', $rutaParada->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('rutas_paradas.form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
