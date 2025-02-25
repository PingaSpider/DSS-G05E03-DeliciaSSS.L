<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Reserva extends Model
{
    
    protected $fillable = ['fecha', 'hora', 'codReserva', 'cantPersona', 'reservaConfirmada', 'mesa_id', 'usuario_id'];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}

