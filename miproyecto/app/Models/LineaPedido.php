<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LineaPedido extends Model
{
    protected $table = 'linea_pedidos';
    protected $primaryKey = 'linea';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'linea', 'cantidad', 'precio', 'estado', 'pedido_id', 'producto_id', 'notas'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
    
    // Asegurar que created_at siempre tenga un valor
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : Carbon::now();
    }
    
    // Asegurar que updated_at siempre tenga un valor
    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : Carbon::now();
    }
    
    /**
     * Actualiza la cantidad de la línea de pedido
     */
    public function actualizarCantidad($cantidad)
    {
        if ($cantidad <= 0) {
            return $this->delete();
        }
        
        $this->cantidad = $cantidad;
        return $this->save();
    }
    
    /**
     * Replica una línea de pedido para un nuevo pedido
     */
    public function replicarPara($nuevoPedidoId)
    {
        $nuevaLinea = $this->replicate();
        $nuevaLinea->linea = uniqid(); // Generar un nuevo ID único
        $nuevaLinea->pedido_id = $nuevoPedidoId;
        $nuevaLinea->save();
        
        return $nuevaLinea;
    }
    
    /**
     * Obtener nombre del producto (acceso directo)
     */
    public function getNombreProductoAttribute()
    {
        return $this->producto ? $this->producto->nombre : 'Producto no disponible';
    }
    
    /**
     * Obtener imagen del producto (acceso directo)
     */
    public function getImagenProductoAttribute()
    {
        return $this->producto && isset($this->producto->imagen) ? $this->producto->imagen : null;
    }
}