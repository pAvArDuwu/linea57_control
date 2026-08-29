<?php

namespace App\Services;

use App\Models\AsignacionTurno;
use App\Models\ControlRecorrido;
use App\Models\RutaParada;
use App\Models\SeguimientoGps;
use Illuminate\Support\Facades\Log;

class ControlRecorridoService
{
    /**
     * Radio de tolerancia en metros para dar por cumplida una parada.
     */
    public const RADIO_TOLERANCIA_METROS = 80.0;

    /**
     * Evalúa una posición GPS respecto a la secuencia de paradas de la ruta.
     *
     * El seguimiento opera en dos fases:
     *   1. Fase IDA: evalúa las paradas con sentido='Ida' ordenadas por `orden`.
     *   2. Fase VUELTA: una vez cumplidas TODAS las paradas de Ida, evalúa las de 'Vuelta'.
     * La asignación se marca como 'completado' al cumplir la última parada de Vuelta.
     *
     * Si la ruta no tiene paradas de Vuelta, el recorrido finaliza al completar Ida.
     */
    public function evaluarPunto(AsignacionTurno $asignacion, SeguimientoGps $gps): ?ControlRecorrido
    {
        $ruta = $asignacion->ruta;
        if (!$ruta) {
            return null;
        }

        // ── Determinar el sentido activo ───────────────────────────────────
        // Obtener paradas activas de Ida y Vuelta por separado
        $paradasIda = RutaParada::where('ruta_id', $ruta->id)
            ->where('sentido', 'Ida')
            ->where('estado', 'activo')
            ->with('parada')
            ->orderBy('orden')
            ->get();

        $paradasVuelta = RutaParada::where('ruta_id', $ruta->id)
            ->where('sentido', 'Vuelta')
            ->where('estado', 'activo')
            ->with('parada')
            ->orderBy('orden')
            ->get();

        // IDs de paradas ya cumplidas en esta asignación
        $cumplidasIds = ControlRecorrido::where('asignacion_turno_id', $asignacion->id)
            ->where('estado', 'cumplido')
            ->pluck('ruta_parada_id')
            ->toArray();

        // Determinar si ya se completaron todas las paradas de Ida
        $todasIdaCumplidas = $paradasIda->isNotEmpty()
            && $paradasIda->every(fn ($rp) => in_array($rp->id, $cumplidasIds));

        // Seleccionar la secuencia activa según la fase del recorrido
        if (!$todasIdaCumplidas && $paradasIda->isNotEmpty()) {
            $secuenciaActiva = $paradasIda;
            $sentidoActivo   = 'Ida';
        } elseif ($paradasVuelta->isNotEmpty()) {
            $secuenciaActiva = $paradasVuelta;
            $sentidoActivo   = 'Vuelta';
        } else {
            // Sin más paradas: ruta completamente cubierta
            return null;
        }

        if ($secuenciaActiva->isEmpty()) {
            return null;
        }

        // Encontrar la siguiente parada esperada en la secuencia activa
        $siguienteParada = $secuenciaActiva->first(
            fn ($rp) => !in_array($rp->id, $cumplidasIds)
        );

        if (!$siguienteParada || !$siguienteParada->parada) {
            return null;
        }

        $paradaLat = (float) $siguienteParada->parada->latitud;
        $paradaLng = (float) $siguienteParada->parada->longitud;

        // Calcular distancia Haversine
        $distancia = $this->calcularDistanciaMetros(
            (float) $gps->latitud,
            (float) $gps->longitud,
            $paradaLat,
            $paradaLng
        );

        // Si está dentro del radio de tolerancia, registrar parada cumplida
        if ($distancia <= self::RADIO_TOLERANCIA_METROS) {
            $control = ControlRecorrido::create([
                'asignacion_turno_id' => $asignacion->id,
                'seguimiento_gps_id'  => $gps->id,
                'ruta_parada_id'      => $siguienteParada->id,
                'fecha_hora'          => $gps->fecha_hora_gps,
                'estado'              => 'cumplido',
                'distancia_metros'    => round($distancia, 2),
                'observacion'         => "Parada cumplida [{$sentidoActivo}]: {$siguienteParada->parada->nombre} a {$distancia}m",
            ]);

            // ── Culminación automática ────────────────────────────────────
            // Se completa al cumplir la última parada:
            //   - Si hay Vuelta: al terminar la última parada de Vuelta.
            //   - Si no hay Vuelta: al terminar la última parada de Ida.
            $ultimaParadaTotal = $paradasVuelta->isNotEmpty()
                ? $paradasVuelta->last()
                : $paradasIda->last();

            $esUltimaParadaTotal = ($siguienteParada->id === $ultimaParadaTotal?->id);

            if ($esUltimaParadaTotal && $asignacion->estado === 'en_curso') {
                $asignacion->update([
                    'estado'       => 'completado',
                    'hora_llegada' => now()->format('H:i:s'),
                ]);

                Log::info("Asignacion #{$asignacion->id} culminada automáticamente al cumplir la última parada [{$sentidoActivo}].");
            }

            return $control;
        }

        return null;
    }

    /**
     * Calcula la distancia en metros entre dos puntos geográficos (Haversine).
     */
    public function calcularDistanciaMetros(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $radioTierra = 6371000; // Metros

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radioTierra * $c;
    }
}
