<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class Producto extends Model
{
    

    protected $fillable = ['cod', 'pvp', 'nombre','stock','precioCompra'];

    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class);
    }
}
