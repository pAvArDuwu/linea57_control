<?php

namespace App\Models;

use App\Models\Concerns\TieneEstadoLogico;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ruta
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $sentido
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Turno[] $turnos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ruta extends Model
{
    use TieneEstadoLogico;

    protected $perPage = 20;

    protected $table = 'ruta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion', 'sentido', 'estado'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function turnos()
    {
        return $this->hasMany(\App\Models\Turno::class, 'ruta_id', 'id');
    }

    public function paradas()
    {
        return $this->belongsToMany(\App\Models\Parada::class, 'parada_ruta', 'ruta_id', 'parada_id')
                    ->withPivot(['orden', 'estado'])
                    ->orderByPivot('orden')
                    ->withTimestamps();
    }
}
