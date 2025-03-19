<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Producto;

class Menu extends Model
{
    protected $fillable = ['cod', 'descripcion'];
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
        return $this->belongsToMany(
            Producto::class, 
            'menu_producto', 
            'menu_cod', 
            'producto_cod'  // Cambiado para coincidir con la columna en la migración
        )->withPivot('cantidad', 'descripcion');
    }
    
    // Descripción de los productos que componen el menú según si son bebidas o comidas
    public function descripcionProductos($codProducto)
    {
        // Tomar la descripción de los productos que componen el menú según si son bebidas o comidas
        $descripcion = '';
        $productos = $this->productos;
        
        foreach ($productos as $producto) {
            if ($producto->cod == $codProducto) {
                if (substr($producto->cod, 0, 1) == 'C') {
                    $comida = Comida::find($producto->cod);
                    if ($comida) {
                        $descripcion .= $comida->descripcion . ' ';
                    }
                    break;
                } else if (substr($producto->cod, 0, 1) == 'B') { 
                    $bebida = Bebida::find($producto->cod);
                    if ($bebida) {
                        $descripcion .= $bebida->tipoBebida . ' (' . $bebida->tamanyo .')' . ' ';
                    }
                    break;
                }
            }
        }

        return $descripcion;
    }

    // Calcular precio total del menú
    public function calcularPrecioTotal()
    {
        $total = 0;
        
        foreach ($this->productos as $producto) {
            $cantidad = $producto->pivot->cantidad ?? 1;
            $total += $producto->pvp * $cantidad;
        }
        
        // Aplicar descuento por ser menú (10%)
        return $total * 0.9;
    }
}