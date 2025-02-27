<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LineaPedido extends Model
{
    protected $table = 'linea_pedidos';
    protected $primaryKey = 'linea';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'linea', 'cantidad', 'precio', 'estado', 'pedido_id', 'producto_id'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'float',
    ];

    // Relación con pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'cod');
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'cod');
    }

    // Calcular subtotal de la línea
    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->precio;
    }
}