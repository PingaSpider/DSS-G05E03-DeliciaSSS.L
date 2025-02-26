<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comida;
use App\Models\Bebida;

class Menu extends Model
{
    
    protected $fillable = ['descripcion'];
    protected $primaryKey = 'cod';
    protected $keyType = 'string';
    public $incrementing = false;


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
     
    //Descripcion de los productos que componen el menu segun si son bebidas o comidas
    public function descripcionProductos($codProducto)
    {
        //Tomar la descripcion de los productos que componen el menu segun si son bebidas o comidas
        $descripcion = '';
        $productos = $this->productos;
        foreach ($productos as $producto) {
            if ($producto->cod == $codProducto) {
                if (substr($producto->cod, 0, 1) == 'C') {
                    $comida = Comida::find($producto->cod);
                    $descripcion .= $comida->descripcion . ' ';
                    break;
                } else if (substr($producto->cod, 0, 1) == 'B'){ 
                        $bebida = Bebida::find($producto->cod);
                        $descripcion .= $bebida->tipoBebida . ' ' . '(' . $bebida->tamanyo .')' . ' ';
                        break;
                }
                
            }
            
        }

        return $descripcion;
    }

}