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

    //Colocar una columna con el email del usuario
    public function getEmailUsuarioAttribute()
    {
        return $this->usuario->email;
    }

    //Colocar una columna con el nombre de la mesa reservada
    public function scopeEnFechaYHora($query, $fecha, $hora)
    {
        return $query->where('fecha', $fecha)->where('hora', $hora);
    }

    // Marcar la mesa como ocupada al crear una reserva
    protected static function booted()
    {
        static::created(function ($reserva) {
            // Marcar la mesa como ocupada
            Mesa::where('codMesa', $reserva->mesa_id)
                ->update(['ocupada' => true]);
        });
        
        static::deleted(function ($reserva) {
            // Si no hay más reservas activas para esta mesa, marcarla como disponible
            if (!Reserva::where('mesa_id', $reserva->mesa_id)->where('fecha', '>=', now())->exists()) {
                Mesa::where('codMesa', $reserva->mesa_id)
                    ->update(['ocupada' => false]);
            }
        });
    }
}

