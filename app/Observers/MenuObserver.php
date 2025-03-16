<?php

namespace App\Observers;

use App\Models\Menu;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class MenuObserver
{
    // Métodos existentes...
    
    /**
     * Verificar la disponibilidad del menú basado en sus productos
     * Este método debe llamarse cuando:
     * - Se añaden/eliminan productos del menú
     * - Se actualiza el stock de cualquier producto que compone el menú
     */
    public function checkMenuAvailability(Menu $menu): void
    {
        if ($menu->producto) {
            // Obtener todos los productos del menú
            $productos = $menu->productos;
            
            // Verificar si algún producto tiene stock cero o no está disponible
            $hayProductoSinStock = $productos->contains(function ($producto) {
                return $producto->stock <= 0 || !$producto->disponible;
            });
            
            // Calcular el stock máximo posible para el menú
            // (limitado por el producto con menor stock disponible)
            $stockMinimo = $productos->isEmpty() ? 0 : $productos->min(function ($producto) {
                // Ajustar por la cantidad requerida en el menú
                $cantidadEnMenu = $producto->pivot->cantidad ?? 1;
                return floor($producto->stock / $cantidadEnMenu);
            });
            
            // Actualizar el producto asociado al menú
            $menu->producto->update([
                'stock' => $stockMinimo,
                'disponible' => !$hayProductoSinStock && $stockMinimo > 0
            ]);
        }
    }
    
    /**
     * Handle the attachment of products to a menu.
     */
    public function productsAttached(Menu $menu, $productoId): void
    {
        // Recalcular el precio del menú
        $this->recalculateMenuPrice($menu);
        
        // Verificar disponibilidad
        $this->checkMenuAvailability($menu);
    }
    
    /**
     * Handle the detachment of products from a menu.
     */
    public function productsDetached(Menu $menu, $productoId): void
    {
        // Recalcular el precio del menú
        $this->recalculateMenuPrice($menu);
        
        // Verificar disponibilidad
        $this->checkMenuAvailability($menu);
    }
}