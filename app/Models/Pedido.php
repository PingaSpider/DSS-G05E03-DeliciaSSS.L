<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Pedido extends Model
{
    

    protected $fillable = ['cod', 'fecha', 'estado', 'usuario_id'];

    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}