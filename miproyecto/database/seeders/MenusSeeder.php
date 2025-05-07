<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuProducto;
use App\Models\Producto;

class MenusSeeder extends Seeder
{
    public function run()
    {
        Producto::create([
            'cod' => 'M0001',
            'pvp' => 28,
            'nombre' => 'Menu 1',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '20',
            'imagen_url' => 'bb01'
        ]);

        Producto::create([
            'cod' => 'M0002',
            'pvp' => 25,
            'nombre' => 'Menu 2',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '19',
            'imagen_url' => 'bb02'
        ]);

        Producto::create([
            'cod' => 'M0003',
            'pvp' => 31,
            'nombre' => 'Menu 3',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '21',
            'imagen_url' => 'bb03'
        ]);

        // Crear menús utilizando el modelo Menu
        $menu1 = Menu::create([
            'cod' => 'M0001', 
            'descripcion' => 'Menú Clásico'
        ]);

        $menu2 = Menu::create([
            'cod' => 'M0002', 
            'descripcion' => 'Menú Vegano'
        ]);

        $menu3 = Menu::create([
            'cod' => 'M0003', 
            'descripcion' => 'Menú Infantil'
        ]);

        // Asociar productos a cada menú utilizando el modelo MenuProducto
        // Menú Clásico
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'C0028', 
            'cantidad' => 1, 
            'descripcion' => 'Primero'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'B0012', 
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'P0003', 
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'P0001', 
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'C0033', 
            'cantidad' => 1, 
            'descripcion' => 'Segundo'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'C0031', 
            'cantidad' => 1, 
            'descripcion' => 'Primero'
        ]);
        
        // Menú Vegano
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'B0012', 
            'cantidad' => 1, 
            'descripcion' => 'Hamburguesa menu2'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'B0012', 
            'cantidad' => 1, 
            'descripcion' => 'Ensalada menu2'
        ]);

        // Menú Infantil
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'B0013', 
            'cantidad' => 1, 
            'descripcion' => 'Pizza pequeña'
        ]);
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'B0013', 
            'cantidad' => 1, 
            'descripcion' => 'Jugo'
        ]);
    }
}
