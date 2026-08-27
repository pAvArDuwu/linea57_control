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
     */
    public function evaluarPunto(AsignacionTurno $asignacion, SeguimientoGps $gps): ?ControlRecorrido
    {
        $ruta = $asignacion->ruta;
        if (!$ruta) {
            return null;
        }

        // Obtener paradas activas ordenadas de la ruta
        $paradasRuta = RutaParada::where('ruta_id', $ruta->id)
            ->where('estado', 'activo')
            ->with('parada')
            ->orderBy('orden')
            ->get();

        if ($paradasRuta->isEmpty()) {
            return null;
        }

        // Obtener IDs de paradas ya cumplidas en esta asignación
        $cumplidasIds = ControlRecorrido::where('asignacion_turno_id', $asignacion->id)
            ->where('estado', 'cumplido')
            ->pluck('ruta_parada_id')
            ->toArray();

        // Encontrar la siguiente parada esperada en la secuencia
        $siguienteParada = $paradasRuta->first(function ($rp) use ($cumplidasIds) {
            return !in_array($rp->id, $cumplidasIds);
        });

        if (!$siguienteParada || !$siguienteParada->parada) {
            return null;
        }

        $paradaLat = (float) $siguienteParada->parada->latitud;
        $paradaLng = (float) $siguienteParada->parada->longitud;

        // Calcular distancia Haversine entre el punto GPS y la parada
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
                'seguimiento_gps_id' => $gps->id,
                'ruta_parada_id' => $siguienteParada->id,
                'fecha_hora' => $gps->fecha_hora_gps,
                'estado' => 'cumplido',
                'distancia_metros' => round($distancia, 2),
                'observacion' => "Parada cumplida: {$siguienteParada->parada->nombre} a {$distancia}m",
            ]);

            // CULMINACIÓN AUTOMÁTICA (SDD Sección 13):
            // Verificar si era la última parada de la ruta completa
            $esUltimaParada = ($siguienteParada->id === $paradasRuta->last()->id);
            if ($esUltimaParada && $asignacion->estado === 'en_curso') {
                $asignacion->update([
                    'estado' => 'completado',
                    'hora_llegada' => now()->format('H:i:s'),
                ]);

                Log::info("Asignacion #{$asignacion->id} culminada automáticamente al cumplir la última parada.");
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
