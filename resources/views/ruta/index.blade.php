<!-- resources/views/ruta/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Gestión de Rutas</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Lista de Rutas</h5>
                    <a href="{{ route('ruta.create') }}" class="btn btn-primary">Crear Nueva Ruta</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Nombre Ruta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruta as $rutum)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rutum->origen }}</td>
                                    <td>{{ $rutum->destino }}</td>
                                    <td>{{ $rutum->nombre_ruta }}</td>
                                    <td>
                                        <a href="{{ route('ruta.show', $rutum->id) }}" class="btn btn-sm btn-info me-1">Ver</a>
                                        <a href="{{ route('ruta.edit', $rutum->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                        <form action="{{ route('ruta.destroy', $rutum->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $ruta->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
