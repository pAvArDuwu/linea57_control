<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Asociar Parada a Ruta</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Nueva Asociación de Ruta y Parada</h5>
                <form action="{{ route('rutas-paradas.store') }}" method="POST">
                    @csrf
                    @include('rutas_paradas.form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
