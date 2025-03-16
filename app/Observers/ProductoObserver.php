<?php

namespace App\Observers;

use App\Models\Producto;
use App\Models\Menu;
use App\Observers\MenuObserver;

class ProductoObserver
{
    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto): void
    {
        // Buscar todos los menús que contengan este producto
        $menus = Menu::whereHas('productos', function ($query) use ($producto) {
            $query->where('productos.id', $producto->id);
        })->get();
        
        $menuObserver = app(MenuObserver::class);
        
        // Actualizar cada menú
        foreach ($menus as $menu) {
            $menuObserver->checkMenuAvailability($menu);
        }
    }
    
    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto): void
    {
        // Buscar todos los menús que contengan este producto
        $menus = Menu::whereHas('productos', function ($query) use ($producto) {
            $query->where('productos.id', $producto->id);
        })->get();
        
        // Desacoplar el producto de todos los menús
        foreach ($menus as $menu) {
            $menu->productos()->detach($producto->id);
            
            // Si el menú queda sin productos, considerar inactivarlo
            if ($menu->productos()->count() == 0) {
                if ($menu->producto) {
                    $menu->producto->update([
                        'disponible' => false,
                        'stock' => 0
                    ]);
                }
            }
        }
    }
}