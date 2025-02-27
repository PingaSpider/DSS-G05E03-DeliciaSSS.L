<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Bebida extends Model
{
    
    protected $primaryKey = 'cod';
    protected $keyType = 'string';
    protected $fillable = ['cod','tamanyo', 'tipoBebida'];
    public $incrementing = false;

    //get public para obtener la descripcion de la comida
    public function getDescripcionAttribute()
    {
        return $this->fillable['tipoBebida'];
    }

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'cod', 'cod');
    }
}