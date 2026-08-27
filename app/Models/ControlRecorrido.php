<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo ControlRecorrido - Evaluación automática de una posición GPS respecto a la ruta y paradas.
 *
 * @property int $id
 * @property int $asignacion_turno_id
 * @property int $seguimiento_gps_id
 * @property int|null $ruta_parada_id
 * @property string $fecha_hora
 * @property string $estado      pendiente | cumplido | omitido | fuera_ruta
 * @property float|null $distancia_metros
 * @property string|null $observacion
 */
class ControlRecorrido extends Model
{
    use HasFactory;

    protected $table = 'control_recorrido';

    protected $fillable = [
        'asignacion_turno_id',
        'seguimiento_gps_id',
        'ruta_parada_id',
        'fecha_hora',
        'estado',
        'distancia_metros',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
            'distancia_metros' => 'float',
        ];
    }

    public function asignacionTurno(): BelongsTo
    {
        return $this->belongsTo(AsignacionTurno::class, 'asignacion_turno_id');
    }

    public function seguimientoGps(): BelongsTo
    {
        return $this->belongsTo(SeguimientoGps::class, 'seguimiento_gps_id');
    }

    public function rutaParada(): BelongsTo
    {
        return $this->belongsTo(RutaParada::class, 'ruta_parada_id');
    }
}
