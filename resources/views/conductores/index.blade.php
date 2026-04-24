<!-- resources/views/conductores/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 text-primary">Gestión de Conductores</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                @if(session('info'))
                    <div class="alert alert-success">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Lista de Conductores</h5>
                    @can('conductor.create')
                        <a href="{{ route('conductor.create') }}" class="btn btn-primary">Crear Nuevo Conductor</a>
                    @endcan
                </div>

                <form action="{{ route('conductor.index') }}" method="GET" class="d-flex mb-3">
                    <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar conductor..." value="{{ $buscar }}">
                    <button type="submit" class="btn btn-outline-secondary">Buscar</button>
                    @if($buscar)
                        <a href="{{ route('conductor.index') }}" class="btn btn-outline-secondary ms-2">Limpiar</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>CI</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($conductores as $conductor)
                                <tr>
                                    <td>{{ $conductor->id }}</td>
                                    <td>{{ $conductor->nombre }}</td>
                                    <td>{{ $conductor->apellido }}</td>
                                    <td>{{ $conductor->telefono }}</td>
                                    <td>{{ $conductor->correo }}</td>
                                    <td>{{ $conductor->ci }}</td>
                                    <td>
                                        @can('conductor.show')
                                            <a href="{{ route('conductor.show', $conductor->id) }}" class="btn btn-sm btn-info me-1">Ver</a>
                                        @endcan
                                        @can('conductor.edit')
                                            <a href="{{ route('conductor.edit', $conductor->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                        @endcan
                                        @can('conductor.destroy')
                                            <form action="{{ route('conductor.destroy', $conductor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $conductores->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>