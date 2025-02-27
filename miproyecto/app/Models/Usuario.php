<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    
    protected $fillable = [
        'email', 'nombre', 'password', 'telefono'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relación con reservas: un usuario puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id', 'id');
    }
}

