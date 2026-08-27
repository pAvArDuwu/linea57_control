<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo SeguimientoGps - Registro de posiciones geográficas enviadas por el dispositivo móvil.
 *
 * @property int $id
 * @property int $asignacion_turno_id
 * @property string $fecha_hora_gps
 * @property float $latitud
 * @property float $longitud
 * @property float|null $velocidad
 * @property string|null $fecha_hora_sincronizacion
 */
class SeguimientoGps extends Model
{
    use HasFactory;

    protected $table = 'seguimiento_gps';

    protected $fillable = [
        'asignacion_turno_id',
        'fecha_hora_gps',
        'latitud',
        'longitud',
        'velocidad',
        'fecha_hora_sincronizacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora_gps' => 'datetime',
            'fecha_hora_sincronizacion' => 'datetime',
            'latitud' => 'float',
            'longitud' => 'float',
            'velocidad' => 'float',
        ];
    }

    public function asignacionTurno(): BelongsTo
    {
        return $this->belongsTo(AsignacionTurno::class, 'asignacion_turno_id');
    }

    public function controlRecorrido(): HasOne
    {
        return $this->hasOne(ControlRecorrido::class, 'seguimiento_gps_id');
    }
}
