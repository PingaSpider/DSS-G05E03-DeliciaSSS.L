<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class Producto extends Model
{
    

    protected $fillable = ['cod', 'pvp', 'nombre','stock','precioCompra'];
    protected $primaryKey = 'cod';
    protected $keyType = 'string';

    public $incrementing = false;

    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class);
    }

    // Relaciones con las subclases
    public function comida()
    {
        return $this->hasOne(Comida::class, 'cod', 'cod');
    }

    public function bebida()
    {
        return $this->hasOne(Bebida::class, 'cod', 'cod');
    }

    public function menu()
    {
        return $this->hasOne(Menu::class, 'cod', 'cod');
    }
}
