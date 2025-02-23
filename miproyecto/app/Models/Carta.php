<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class Carta extends Model
{
    

    protected $fillable = ['cod', 'precio', 'nombre'];

    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class);
    }
}
