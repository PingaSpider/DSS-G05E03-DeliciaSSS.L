<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LineaPedido extends Model
{
    

    protected $fillable = ['cantidad', 'precio', 'estado', 'pedido_id', 'carta_id'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}