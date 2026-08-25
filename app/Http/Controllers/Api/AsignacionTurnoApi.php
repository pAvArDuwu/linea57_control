<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AsignacionTurno;
use App\Services\AsignacionTurnoService;
use Illuminate\Http\Request;

class AsignacionTurnoApi extends Controller
{
    public function __construct(
        protected AsignacionTurnoService $asignacionTurnoService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $asignaciones = AsignacionTurno::with(['turno', 'conductor', 'micro', 'ruta'])
            ->when($buscar, fn ($q) => $q->buscarPorConductor($buscar))
            ->orderByDesc('fecha')
            ->paginate(15);

        return response()->json($asignaciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha'        => ['required', 'date'],
            'turno_id'     => ['required', 'integer', 'exists:turno,id'],
            'ruta_id'      => ['required', 'integer', 'exists:ruta,id'],
            'micro_id'     => ['required', 'integer', 'exists:micro,id'],
            'conductor_id' => ['required', 'integer', 'exists:conductor,id'],
            'hora_salida'  => ['nullable', 'date_format:H:i'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'estado'       => ['required', 'in:pendiente,en_curso,completado,retrasado,cancelado'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $asignacion = $this->asignacionTurnoService->crear($data);

        return response()->json($asignacion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asignacion = AsignacionTurno::with(['turno', 'conductor', 'micro', 'ruta'])->findOrFail($id);

        return response()->json($asignacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asignacion = AsignacionTurno::findOrFail($id);

        $data = $request->validate([
            'fecha'        => ['required', 'date'],
            'turno_id'     => ['required', 'integer', 'exists:turno,id'],
            'ruta_id'      => ['required', 'integer', 'exists:ruta,id'],
            'micro_id'     => ['required', 'integer', 'exists:micro,id'],
            'conductor_id' => ['required', 'integer', 'exists:conductor,id'],
            'hora_salida'  => ['nullable', 'date_format:H:i'],
            'hora_llegada' => ['nullable', 'date_format:H:i'],
            'estado'       => ['required', 'in:pendiente,en_curso,completado,retrasado,cancelado'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ]);

        $actualizado = $this->asignacionTurnoService->actualizar($asignacion, $data);

        return response()->json($actualizado);
    }

    /**
     * Remove the specified resource from storage (cancelación lógica).
     */
    public function destroy(string $id)
    {
        $asignacion = AsignacionTurno::findOrFail($id);
        $this->asignacionTurnoService->cancelar($asignacion);

        return response()->json(['message' => 'Asignación cancelada correctamente']);
    }
}
