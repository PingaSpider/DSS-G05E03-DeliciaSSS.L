<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuProducto extends Model
{
    protected $table = 'menu_producto';
    
    // Cambiado para usar la columna id normal como clave primaria
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'menu_cod', 'producto_id', 'cantidad', 'descripcion'
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    // Relación con el menú
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_cod', 'cod');
    }
    
    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'cod');
    }
    
    // Calcular el subtotal de este ítem del menú
    public function calcularSubtotal()
    {
        $producto = $this->producto;
        if ($producto) {
            return $producto->pvp * $this->cantidad;
        }
        return 0;
    }
}