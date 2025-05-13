<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'cod';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'cod', 'fecha', 'estado', 'usuario_id'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Constantes para estados
    const ESTADO_EN_CARRITO = 'enCarrito';
    const ESTADO_PREPARANDO = 'preparando';
    const ESTADO_LISTO = 'listo';
    const ESTADO_ENTREGADO = 'entregado';

    // Estados válidos
    const ESTADOS = [
        self::ESTADO_EN_CARRITO,
        self::ESTADO_PREPARANDO,
        self::ESTADO_LISTO,
        self::ESTADO_ENTREGADO
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación con líneas de pedido
    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class, 'pedido_id', 'cod');
    }

    // Scope para obtener el carrito actual del usuario
    public function scopeCarritoActual($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId)
                     ->where('estado', self::ESTADO_EN_CARRITO);
    }

    // Scope para pedidos activos (no en carrito)
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', [
            self::ESTADO_PREPARANDO,
            self::ESTADO_LISTO
        ]);
    }

    // Scope para pedidos completados
    public function scopeCompletados($query)
    {
        return $query->where('estado', self::ESTADO_ENTREGADO);
    }

    // Obtener el total del pedido
    public function getTotalAttribute()
    {
        return $this->lineasPedido->sum(function ($linea) {
            return $linea->cantidad * $linea->precio;
        });
    }

    // Obtener el número de items en el pedido
    public function getCantidadItemsAttribute()
    {
        return $this->lineasPedido->sum('cantidad');
    }

    // Verificar si el pedido está en el carrito
    public function estaEnCarrito()
    {
        return $this->estado === self::ESTADO_EN_CARRITO;
    }

    // Verificar si el pedido está preparándose
    public function estaPreparando()
    {
        return $this->estado === self::ESTADO_PREPARANDO;
    }

    // Verificar si el pedido está listo
    public function estaListo()
    {
        return $this->estado === self::ESTADO_LISTO;
    }

    // Verificar si el pedido está entregado
    public function estaEntregado()
    {
        return $this->estado === self::ESTADO_ENTREGADO;
    }

    // Cambiar el estado del pedido
    public function cambiarEstado($nuevoEstado)
    {
        if (!in_array($nuevoEstado, self::ESTADOS)) {
            throw new \InvalidArgumentException("Estado inválido: {$nuevoEstado}");
        }

        $this->estado = $nuevoEstado;
        $this->save();

        // Actualizar el estado de todas las líneas de pedido
        $this->lineasPedido()->update(['estado' => $nuevoEstado]);

        return $this;
    }

    // Generar el siguiente código de pedido
    public static function generarSiguienteCodigo()
    {
        $ultimoPedido = self::orderBy('cod', 'desc')->first();
        
        if ($ultimoPedido) {
            // Extraer el número del código (formato Pxxxx)
            $numeroActual = intval(substr($ultimoPedido->cod, 1));
            // Incrementar el número y formatear con 4 dígitos
            $siguienteCodigo = 'P' . str_pad($numeroActual + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Si no hay pedidos anteriores, comenzar con P0001
            $siguienteCodigo = 'P0001';
        }
        
        return $siguienteCodigo;
    }

    // Añadir un producto al pedido
    public function agregarProducto($producto, $cantidad = 1)
    {
        // Verificar si el producto ya está en el pedido
        $lineaExistente = $this->lineasPedido()
            ->where('producto_id', $producto->cod)
            ->first();

        if ($lineaExistente) {
            // Actualizar cantidad si ya existe
            $lineaExistente->cantidad += $cantidad;
            $lineaExistente->save();
            return $lineaExistente;
        } else {
            // Crear nueva línea de pedido
            return $this->lineasPedido()->create([
                'linea' => LineaPedido::generarSiguienteCodigo(),
                'cantidad' => $cantidad,
                'precio' => $producto->pvp,
                'estado' => $this->estado,
                'producto_id' => $producto->cod
            ]);
        }
    }

    // Verificar si el pedido puede ser procesado
    public function puedeSerProcesado()
    {
        return $this->estaEnCarrito() && $this->lineasPedido->count() > 0;
    }

    // Procesar el pedido (cambiar de carrito a preparando)
    public function procesar()
    {
        if (!$this->puedeSerProcesado()) {
            throw new \Exception('El pedido no puede ser procesado');
        }

        return $this->cambiarEstado(self::ESTADO_PREPARANDO);
    }
}