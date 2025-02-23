<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    

    protected $fillable = ['email', 'nombre', 'password', 'telefono'];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
    
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}

