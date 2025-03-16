<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class MenuProductoController extends Controller{
    // En algún servicio o controlador
    public function addProductToMenu(Menu $menu, Producto $producto, int $cantidad = 1)
    {
        $menu->productos()->attach($producto->id, ['cantidad' => $cantidad]);
        
        // Llamar manualmente al método del observador
        app(MenuObserver::class)->productsAttached($menu, [$producto->id]);
        
        return $menu;
    }

    public function removeProductFromMenu(Menu $menu, Producto $producto)
    {
        $menu->productos()->detach($producto->id);
        
        // Llamar manualmente al método del observador
        app(MenuObserver::class)->productsDetached($menu, [$producto->id]);
        
        return $menu;
    }
}