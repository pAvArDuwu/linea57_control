<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rutum
 *
 * @property $id
 * @property $origen
 * @property $destino
 * @property $nombre_ruta
 * @property $created_at
 * @property $updated_at
 *
 * @property Turno[] $turnos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rutum extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['origen', 'destino', 'nombre_ruta'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function turnos()
    {
        return $this->hasMany(\App\Models\Turno::class, 'id', 'ruta_id');
    }
    
}
