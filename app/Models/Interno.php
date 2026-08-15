<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interno extends Model
{
    protected $perPage = 20;

    protected $table = 'interno';

    protected $fillable = ['numero_interno', 'fecha_ingreso', 'observaciones', 'estado'];

    

    public function micro()
    {
        return $this->hasOne(\App\Models\Micro::class, 'interno_id', 'id');
    }
}
