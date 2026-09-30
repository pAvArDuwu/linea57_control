<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        return response()->json(Turno::where('estado', '!=', 'inactivo')->get());
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        $turno = Turno::create($data);

        return response()->json($turno, 201);
    }

    public function show(string $id)
    {
        return response()->json(Turno::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update($this->validar($request, true));

        return response()->json($turno->fresh());
    }

    public function destroy(string $id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update(['estado' => 'inactivo']);

        return response()->json(['message' => 'Turno desactivado correctamente']);
    }

    /**
     * El cliente móvil envía `tipo` y `fiscalizador_id`, columnas que se
     * eliminaron en la migración 2026_08_13_000001. Se acepta `tipo` como
     * alias de `nombre` y se descarta `fiscalizador_id` para no romperlo.
     */
    protected function validar(Request $request, bool $partial = false): array
    {
        $requerido = $partial ? 'sometimes' : 'required';

        $datos = $request->validate([
            'nombre' => [$requerido, 'string', 'in:mañana,tarde,noche'],
            'tipo' => ['nullable', 'string', 'in:mañana,tarde,noche'],
            'hora_inicio' => [$requerido, 'date_format:H:i:s'],
            'hora_fin' => [$requerido, 'date_format:H:i:s'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'in:activo,inactivo'],
            'fiscalizador_id' => ['nullable', 'integer'],
        ]);

        $datos['nombre'] = $datos['nombre'] ?? $datos['tipo'] ?? null;
        unset($datos['tipo'], $datos['fiscalizador_id']);

        return $datos;
    }
}
