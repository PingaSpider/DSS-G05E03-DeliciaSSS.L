<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comida extends Model
{
    
    protected $primaryKey = 'cod';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = ['cod','descripcion'];


    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'cod', 'cod');
    }

}