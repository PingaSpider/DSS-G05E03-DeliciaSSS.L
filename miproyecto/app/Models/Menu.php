<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    
    protected $fillable = ['descripcion'];

     // Relación con el producto base (puesto que un menú ES un producto)
     public function producto()
     {
         return $this->belongsTo(Producto::class, 'cod', 'cod');
     }
 
     // Relación con los productos que componen este menú
     public function productos()
     {
         return $this->belongsToMany(Producto::class, 'menu_producto', 'menu_cod', 'producto_cod')
                     ->withPivot('cantidad');
     }   
}
