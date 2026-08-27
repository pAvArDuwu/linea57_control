<?php

namespace App\Services;

use App\Models\AsignacionTurno;
use App\Models\SeguimientoGps;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SeguimientoGpsService
{
    public function __construct(
        protected ControlRecorridoService $controlRecorridoService
    ) {}

    /**
     * Registra un único punto GPS y evalúa el recorrido.
     */
    public function registrarPunto(AsignacionTurno $asignacion, array $datos): array
    {
        $this->validarRangoTemporal($datos['fecha_hora_gps']);

        return DB::transaction(function () use ($asignacion, $datos) {
            // Evitar duplicados (idempotencia)
            $gps = SeguimientoGps::firstOrCreate(
                [
                    'asignacion_turno_id' => $asignacion->id,
                    'fecha_hora_gps' => $datos['fecha_hora_gps'],
                ],
                [
                    'latitud' => $datos['latitud'],
                    'longitud' => $datos['longitud'],
                    'velocidad' => $datos['velocidad'] ?? 0.0,
                    'fecha_hora_sincronizacion' => now(),
                ]
            );

            // Evaluar avance de parada en el recorrido
            $control = $this->controlRecorridoService->evaluarPunto($asignacion, $gps);

            return [
                'seguimiento_gps' => $gps,
                'control_recorrido' => $control,
                'asignacion_estado' => $asignacion->fresh()->estado,
            ];
        });
    }

    /**
     * Registra un lote de ubicaciones sincronizadas offline (SDD Sección 17 y 26.5).
     */
    public function registrarLote(AsignacionTurno $asignacion, array $puntos): array
    {
        if (count($puntos) > 300) {
            throw ValidationException::withMessages([
                'ubicaciones' => 'El lote no puede exceder los 300 puntos por sincronización.',
            ]);
        }

        $guardados = 0;
        $duplicados = 0;
        $rechazados = [];

        foreach ($puntos as $index => $punto) {
            try {
                if (empty($punto['fecha_hora_gps']) || !isset($punto['latitud']) || !isset($punto['longitud'])) {
                    $rechazados[] = ['index' => $index, 'motivo' => 'Campos obligatorios faltantes'];
                    continue;
                }

                $this->validarRangoTemporal($punto['fecha_hora_gps']);

                $resultado = $this->registrarPunto($asignacion, $punto);
                
                if ($resultado['seguimiento_gps']->wasRecentlyCreated) {
                    $guardados++;
                } else {
                    $duplicados++;
                }
            } catch (\Throwable $e) {
                $rechazados[] = ['index' => $index, 'motivo' => $e->getMessage()];
            }
        }

        return [
            'total_procesados' => count($puntos),
            'guardados' => $guardados,
            'duplicados_omitidos' => $duplicados,
            'rechazados' => $rechazados,
            'asignacion_estado' => $asignacion->fresh()->estado,
        ];
    }

    /**
     * Valida que la fecha/hora del GPS no esté en el futuro más allá de un margen de 2 minutos.
     */
    protected function validarRangoTemporal(string|Carbon $fechaHoraGps): void
    {
        $fecha = Carbon::parse($fechaHoraGps);
        $limiteFuturo = now()->addMinutes(2);

        if ($fecha->greaterThan($limiteFuturo)) {
            throw ValidationException::withMessages([
                'fecha_hora_gps' => 'La fecha/hora del GPS no puede ser posterior a la hora actual del servidor.',
            ]);
        }
    }
}
