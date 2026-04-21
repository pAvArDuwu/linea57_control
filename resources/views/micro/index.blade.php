<!-- resources/views/micro/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Gestión de Micros</h2>
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
                    <h5 class="card-title">Lista de Micros</h5>
                    <a href="{{ route('micros.create') }}" class="btn btn-primary">Crear Nuevo Micro</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Dueño ID</th>
                                <th>Placa</th>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Capacidad Pasajeros</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($micros as $micro)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $micro->dueño_id }}</td>
                                    <td>{{ $micro->placa }}</td>
                                    <td>{{ $micro->modelo }}</td>
                                    <td>{{ $micro->marca }}</td>
                                    <td>{{ $micro->capacidad_pasajeros }}</td>
                                    <td>
                                        <a href="{{ route('micros.show', $micro->id) }}" class="btn btn-sm btn-info me-1">Ver</a>
                                        <a href="{{ route('micros.edit', $micro->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                        <form action="{{ route('micros.destroy', $micro->id) }}" method="POST" class="d-inline">
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
                    {{ $micros->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
