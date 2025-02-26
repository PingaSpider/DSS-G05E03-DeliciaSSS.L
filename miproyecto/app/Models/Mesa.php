<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Mesa extends Model
{
    

    protected $fillable = ['cantidadMesa', 'codMesa', 'ocupada'];

    public function estaDisponible($fecha, $hora)
    {
        return !$this->hasMany(Reserva::class, 'mesa_id', 'codMesa')
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->exists();
    }
}