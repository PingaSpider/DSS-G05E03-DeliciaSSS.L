<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuProducto;

class MenuProductoSeeder extends Seeder
{
    public function run()
    {
        // Crear menús utilizando el modelo Menu
        $menu1 = Menu::create([
            'cod' => 'M001', 
            'descripcion' => 'Menú Clásico'
        ]);

        $menu2 = Menu::create([
            'cod' => 'M002', 
            'descripcion' => 'Menú Vegano'
        ]);

        $menu3 = Menu::create([
            'cod' => 'M003', 
            'descripcion' => 'Menú Infantil'
        ]);

        // Asociar productos a cada menú utilizando el modelo MenuProducto
        // Menú Clásico
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'P001', 
            'cantidad' => 1, 
            'descripcion' => 'Hamburguesa'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'P002', 
            'cantidad' => 1, 
            'descripcion' => 'Papas fritas'
        ]);
        
        // Menú Vegano
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'P003', 
            'cantidad' => 1, 
            'descripcion' => 'Hamburguesa Vegana'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'P004', 
            'cantidad' => 1, 
            'descripcion' => 'Ensalada'
        ]);

        // Menú Infantil
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'P005', 
            'cantidad' => 1, 
            'descripcion' => 'Pizza pequeña'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'P006', 
            'cantidad' => 1, 
            'descripcion' => 'Jugo'
        ]);
    }
}
