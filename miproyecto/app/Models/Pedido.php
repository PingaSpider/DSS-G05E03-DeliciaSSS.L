<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'cod';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'cod', 'fecha', 'estado', 'usuario_id', 'direccion_id', 'metodo_pago', 'notas'
    ];

    protected $casts = [
        'fecha' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Estados posibles del pedido
     */
    const ESTADOS = [
        'en_cesta',
        'pendiente',
        'confirmado',
        'preparando',
        'listo',
        'entregado',
        'cancelado'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    // Relación con líneas de pedido
    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class, 'pedido_id', 'cod');
    }
    
    /**
     * Relación con la dirección de entrega
     */
    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    // Obtener cesta actual del usuario o crear una nueva si no existe
    public static function obtenerCestaUsuario($usuarioId)
    {
        // Buscar si el usuario ya tiene un pedido en estado "en_cesta"
        $cesta = self::where('usuario_id', $usuarioId)
                    ->where('estado', 'en_cesta')
                    ->first();
        
        // Si no existe, crear uno nuevo
        if (!$cesta) {
            $cesta = self::create([
                'cod' => 'P' . time() . rand(100, 999), // Generar código único
                'fecha' => Carbon::now(),
                'estado' => 'en_cesta',
                'usuario_id' => $usuarioId
            ]);
        }
        
        return $cesta;
    }

    // Agregar producto a la cesta
    public function agregarProducto($productoId, $cantidad = 1)
    {
        // Verificar si el producto ya está en la cesta
        $lineaExistente = $this->lineasPedido()
                               ->where('producto_id', $productoId)
                               ->where('estado', 'en_cesta')
                               ->first();
        
        if ($lineaExistente) {
            // Si ya existe, actualizar la cantidad
            $lineaExistente->cantidad += $cantidad;
            $lineaExistente->save();
            return $lineaExistente;
        } else {
            // Si no existe, crear nueva línea
            $producto = Producto::find($productoId);
            return LineaPedido::create([
                'linea' => uniqid(), // Generar ID único para la línea
                'cantidad' => $cantidad,
                'precio' => $producto->pvp,
                'estado' => 'en_cesta',
                'pedido_id' => $this->cod,
                'producto_id' => $productoId
            ]);
        }
    }

    // Remover producto de la cesta
    public function removerProducto($productoId)
    {
        return $this->lineasPedido()
                    ->where('producto_id', $productoId)
                    ->where('estado', 'en_cesta')
                    ->delete();
    }

    // Actualizar cantidad de un producto en la cesta
    public function actualizarCantidad($productoId, $cantidad)
    {
        $linea = $this->lineasPedido()
                      ->where('producto_id', $productoId)
                      ->where('estado', 'en_cesta')
                      ->first();
        
        if ($linea) {
            if ($cantidad <= 0) {
                // Si la cantidad es 0 o negativa, eliminar el producto
                return $this->removerProducto($productoId);
            } else {
                // Actualizar cantidad
                $linea->cantidad = $cantidad;
                $linea->save();
                return $linea;
            }
        }
        
        return false;
    }

    // Obtener todos los productos en la cesta
    public function obtenerProductosCesta()
    {
        return $this->lineasPedido()
                    ->with('producto') // Cargar relación con productos
                    ->where('estado', 'en_cesta')
                    ->get();
    }

    // Vaciar la cesta
    public function vaciarCesta()
    {
        return $this->lineasPedido()
                    ->where('estado', 'en_cesta')
                    ->delete();
    }

    // Confirmar pedido (cambiar estado de en_cesta a confirmado)
    public function confirmarPedido()
    {
        // Verificar que hay productos en la cesta
        $tieneProdutos = $this->lineasPedido()
                              ->where('estado', 'en_cesta')
                              ->exists();
        
        if (!$tieneProdutos) {
            return false; // No se puede confirmar un pedido vacío
        }
        
        // Actualizar estado del pedido
        $this->estado = 'confirmado';
        $this->fecha = Carbon::now(); // Actualizar fecha al momento de confirmación
        $this->save();
        
        // Actualizar estado de todas las líneas
        $this->lineasPedido()
             ->where('estado', 'en_cesta')
             ->update(['estado' => 'confirmado']);
        
        // Actualizar stock de productos (implementar según tus reglas de negocio)
        foreach ($this->lineasPedido as $linea) {
            $producto = $linea->producto;
            $producto->stock -= $linea->cantidad;
            $producto->save();
        }
        
        return true;
    }

    // Calcular total del pedido
    public function calcularTotal()
    {
        return $this->lineasPedido()
                    ->where('estado', $this->estado) // Usar el mismo estado que el pedido
                    ->sum(\DB::raw('precio * cantidad'));
    }
    
    /**
     * Obtiene el monto total calculado del pedido
     */
    public function getTotalAttribute()
    {
        return $this->calcularTotal();
    }
    
    /**
     * Cambia el estado del pedido
     */
    public function cambiarEstado($estado)
    {
        if (in_array($estado, self::ESTADOS)) {
            $this->estado = $estado;
            $this->save();
            
            // Actualizar estado de todas las líneas
            $this->lineasPedido()
                 ->update(['estado' => $estado]);
                 
            return true;
        }
        
        return false;
    }
    
    /**
     * Repite un pedido existente creando una copia con estado pendiente
     */
    public static function repetirPedido($pedidoId, $usuarioId)
    {
        $pedidoAnterior = self::with('lineasPedido')->findOrFail($pedidoId);
        
        // Verificar que el pedido pertenece al usuario
        if ($pedidoAnterior->usuario_id != $usuarioId) {
            return false;
        }
        
        // Crear nuevo pedido
        $nuevoPedido = self::create([
            'cod' => 'P' . time() . rand(100, 999), // Generar código único
            'fecha' => Carbon::now(),
            'estado' => 'pendiente',
            'usuario_id' => $usuarioId,
            'direccion_id' => $pedidoAnterior->direccion_id,
            'metodo_pago' => $pedidoAnterior->metodo_pago,
            'notas' => $pedidoAnterior->notas
        ]);
        
        // Copiar líneas de pedido
        foreach ($pedidoAnterior->lineasPedido as $lineaAnterior) {
            LineaPedido::create([
                'linea' => uniqid(), // Generar ID único para la línea
                'cantidad' => $lineaAnterior->cantidad,
                'precio' => $lineaAnterior->precio,
                'estado' => 'pendiente',
                'pedido_id' => $nuevoPedido->cod,
                'producto_id' => $lineaAnterior->producto_id
            ]);
        }
        
        return $nuevoPedido;
    }
    
    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
    
    /**
     * Scope para filtrar pedidos recientes
     */
    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha', '>=', Carbon::now()->subDays($dias));
    }
    
    /**
     * Scope para filtrar por usuario
     */
    public function scopeUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }
}