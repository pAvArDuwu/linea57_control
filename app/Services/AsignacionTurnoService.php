<?php

namespace App\Services;

use App\Models\AsignacionTurno;
use App\Models\Turno;
use Illuminate\Validation\ValidationException;

class AsignacionTurnoService
{
    /**
     * Crea una nueva asignación de turno validando reglas de negocio.
     */
    public function crear(array $datos): AsignacionTurno
    {
        $this->validarTurnoActivo($datos['turno_id']);
        $this->validarDisponibilidad($datos);

        return AsignacionTurno::create($datos);
    }

    /**
     * Actualiza una asignación de turno existente.
     */
    public function actualizar(AsignacionTurno $asignacion, array $datos): AsignacionTurno
    {
        $this->validarTurnoActivo($datos['turno_id']);
        $this->validarDisponibilidad($datos, $asignacion->id);

        $asignacion->update($datos);

        return $asignacion;
    }

    /**
     * Cancela lógicamente una asignación de turno.
     */
    public function cancelar(AsignacionTurno $asignacion): AsignacionTurno
    {
        $asignacion->update(['estado' => 'cancelado']);

        return $asignacion;
    }

    /**
     * Valida que el turno seleccionado se encuentre activo.
     */
    protected function validarTurnoActivo(int $turnoId): void
    {
        $turno = Turno::findOrFail($turnoId);

        if ($turno->estado !== 'activo') {
            throw ValidationException::withMessages([
                'turno_id' => 'El turno seleccionado está inactivo.',
            ]);
        }
    }

    /**
     * Valida que el conductor o micro no estén duplicados en la misma fecha y turno.
     */
    protected function validarDisponibilidad(array $datos, ?int $exceptId = null): void
    {
        // 1. Validar conductor en fecha y turno (si la asignación no está cancelada)
        $conductorOcupado = AsignacionTurno::where('conductor_id', $datos['conductor_id'])
            ->where('fecha', $datos['fecha'])
            ->where('turno_id', $datos['turno_id'])
            ->where('estado', '!=', 'cancelado')
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists();

        if ($conductorOcupado) {
            throw ValidationException::withMessages([
                'conductor_id' => 'El conductor seleccionado ya tiene una asignación activa para este turno y fecha.',
            ]);
        }

        // 2. Validar micro en fecha y turno
        $microOcupado = AsignacionTurno::where('micro_id', $datos['micro_id'])
            ->where('fecha', $datos['fecha'])
            ->where('turno_id', $datos['turno_id'])
            ->where('estado', '!=', 'cancelado')
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists();

        if ($microOcupado) {
            throw ValidationException::withMessages([
                'micro_id' => 'El micro seleccionado ya se encuentra asignado a este turno y fecha.',
            ]);
        }
    }
}
