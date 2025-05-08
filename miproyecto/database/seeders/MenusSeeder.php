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
        // Menu 1
        Producto::create([
            'cod' => 'M0001',
            'pvp' => 32.98,
            'nombre' => 'Menu 1',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '25.98',
            'imagen_url' => 'bb01'
        ]);

        $menu1 = Menu::create([
            'cod' => 'M0001', 
            'descripcion' => 'Menú Clásico'
        ]);

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

        // Menu 2
        Producto::create([
            'cod' => 'M0002',
            'pvp' => 25.98,
            'nombre' => 'Menu 2',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '19',
            'imagen_url' => 'bb02'
        ]);
        
        $menu2 = Menu::create([
            'cod' => 'M0002', 
            'descripcion' => 'Menú Vegano'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'C0027', 
            'cantidad' => 1, 
            'descripcion' => 'Menu2'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'C0028', 
            'cantidad' => 1, 
            'descripcion' => 'Menu2'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'B0016', 
            'cantidad' => 1, 
            'descripcion' => 'Menu2'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'B0017', 
            'cantidad' => 1, 
            'descripcion' => 'Menu2'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'P0002', 
            'cantidad' => 1, 
            'descripcion' => 'Menu2'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'P0003', 
            'cantidad' => 1, 
            'descripcion' => 'Menu2'
        ]);

        // Menu 3
        Producto::create([
            'cod' => 'M0003',
            'pvp' => 35.99,
            'nombre' => 'Menu 3',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '21',
            'imagen_url' => 'bb03'
        ]);

        $menu3 = Menu::create([
            'cod' => 'M0003', 
            'descripcion' => 'Menú Infantil'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0037', 
            'cantidad' => 1, 
            'descripcion' => 'Menu3'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0035', 
            'cantidad' => 1, 
            'descripcion' => 'Menu3'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0033', 
            'cantidad' => 1, 
            'descripcion' => 'Menu3'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0025', 
            'cantidad' => 1, 
            'descripcion' => 'Menu3'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'P0001', 
            'cantidad' => 1, 
            'descripcion' => 'Menu3'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'P0004', 
            'cantidad' => 1, 
            'descripcion' => 'Menu3'
        ]);

        // Menu 4
        Producto::create([
            'cod' => 'M0004',
            'pvp' => 39.99,
            'nombre' => 'Menu 4',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '25',
            'imagen_url' => 'bb03'
        ]);

        $menu3 = Menu::create([
            'cod' => 'M0004', 
            'descripcion' => 'Menú Normal 4'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0022', 
            'cantidad' => 1, 
            'descripcion' => 'Menu4'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0026', 
            'cantidad' => 1, 
            'descripcion' => 'Menu4'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0033', 
            'cantidad' => 1, 
            'descripcion' => 'Menu4'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0025', 
            'cantidad' => 1, 
            'descripcion' => 'Menu4'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0023', 
            'cantidad' => 1, 
            'descripcion' => 'Menu4'
        ]);

        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'P0004', 
            'cantidad' => 1, 
            'descripcion' => 'Menu4'
        ]);
    }
}
