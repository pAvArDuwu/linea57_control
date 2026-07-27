<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dueño
 *
 * @property $id
 * @property $nombre
 * @property $apellido
 * @property $telefono
 * @property $correo
 * @property $ci
 * @property $estado
 * @property $fecha_registro
 * @property $created_at
 * @property $updated_at
 *
 * @property Micro[] $micros
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Dueño extends Model
{
    protected $perPage = 20;

    protected $table = 'propietarios';

    protected $fillable = ['nombre', 'apellido', 'telefono', 'correo', 'ci', 'estado', 'fecha_registro'];

    public function micros()
    {
        return $this->hasMany(\App\Models\Micro::class, 'propietario_id', 'id');
    }
}
