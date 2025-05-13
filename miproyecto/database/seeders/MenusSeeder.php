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
        // Menu 1 - Menú Hamburguesa Clásica
        Producto::create([
            'cod' => 'M0001',
            'pvp' => 15.95,
            'nombre' => 'Menú Cheeseburger',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '10.00',
            'imagen_url' => 'hb02'
        ]);

        $menu1 = Menu::create([
            'cod' => 'M0001', 
            'descripcion' => 'Menú Hamburguesa Clásica'
        ]);

        // Hamburguesa principal
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'C0027', // Cheeseburger Clásica
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Patatas
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'C0033', // Patatas Fritas Deluxe
            'cantidad' => 1, 
            'descripcion' => 'Acompañamiento'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'B0001', // Coca-Cola
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu1->cod, 
            'producto_cod' => 'P0003', // Helado Stracciatella
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);

        // Menu 2 - Menú Pizza Margherita
        Producto::create([
            'cod' => 'M0002',
            'pvp' => 14.95,
            'nombre' => 'Menú Pizza Margherita',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '9.00',
            'imagen_url' => 'pz03'
        ]);
        
        $menu2 = Menu::create([
            'cod' => 'M0002', 
            'descripcion' => 'Menú Pizza Clásica'
        ]);

        // Pizza principal
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'C0036', // Pizza Margherita
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'B0015', // San Miguel
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu2->cod, 
            'producto_cod' => 'P0001', // Tarta de Chocolate
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);

        // Menu 3 - Menú BBQ Burger
        Producto::create([
            'cod' => 'M0003',
            'pvp' => 17.95,
            'nombre' => 'Menú BBQ Premium',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '12.00',
            'imagen_url' => 'hb05'
        ]);

        $menu3 = Menu::create([
            'cod' => 'M0003', 
            'descripcion' => 'Menú Hamburguesa BBQ'
        ]);

        // Hamburguesa principal
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0030', // BBQ Burger
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Patatas
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'C0033', // Patatas Fritas Deluxe
            'cantidad' => 1, 
            'descripcion' => 'Acompañamiento'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'B0012', // Coca-Cola Zero
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu3->cod, 
            'producto_cod' => 'P0002', // Cheesecake de Frutos Rojos
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);

        // Menu 4 - Menú Pizza Pepperoni
        Producto::create([
            'cod' => 'M0004',
            'pvp' => 16.95,
            'nombre' => 'Menú Pizza Pepperoni',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '11.00',
            'imagen_url' => 'pz05'
        ]);

        $menu4 = Menu::create([
            'cod' => 'M0004', 
            'descripcion' => 'Menú Pizza Americana'
        ]);

        // Pizza principal
        MenuProducto::create([
            'menu_cod' => $menu4->cod, 
            'producto_cod' => 'C0038', // Pizza Pepperoni
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu4->cod, 
            'producto_cod' => 'B0013', // Sprite
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu4->cod, 
            'producto_cod' => 'P0004', // Helado de Chocolate y Avellanas
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);

        // Menu 5 - Menú Black Angus Premium
        Producto::create([
            'cod' => 'M0005',
            'pvp' => 19.95,
            'nombre' => 'Menú Black Angus',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '14.00',
            'imagen_url' => 'hb06'
        ]);

        $menu5 = Menu::create([
            'cod' => 'M0005', 
            'descripcion' => 'Menú Premium Black Angus'
        ]);

        // Hamburguesa principal
        MenuProducto::create([
            'menu_cod' => $menu5->cod, 
            'producto_cod' => 'C0031', // Black Angus Burger
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Patatas
        MenuProducto::create([
            'menu_cod' => $menu5->cod, 
            'producto_cod' => 'C0033', // Patatas Fritas Deluxe
            'cantidad' => 1, 
            'descripcion' => 'Acompañamiento'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu5->cod, 
            'producto_cod' => 'B0016', // Estrella Damm
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Bebida extra (opción premium)
        MenuProducto::create([
            'menu_cod' => $menu5->cod, 
            'producto_cod' => 'B0018', // Solán de Cabras (agua)
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu5->cod, 
            'producto_cod' => 'P0001', // Tarta de Chocolate
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);

        // Menu 6 - Menú Pizza Vegetariana
        Producto::create([
            'cod' => 'M0006',
            'pvp' => 15.95,
            'nombre' => 'Menú Pizza Veggie',
            'stock' => 40,
            'disponible'=> true,
            'precioCompra'=> '10.00',
            'imagen_url' => 'pz02'
        ]);

        $menu6 = Menu::create([
            'cod' => 'M0006', 
            'descripcion' => 'Menú Pizza Vegetariana'
        ]);

        // Pizza principal
        MenuProducto::create([
            'menu_cod' => $menu6->cod, 
            'producto_cod' => 'C0035', // Pizza Vegetariana
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu6->cod, 
            'producto_cod' => 'B0014', // Fanta Naranja
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Bebida extra
        MenuProducto::create([
            'menu_cod' => $menu6->cod, 
            'producto_cod' => 'B0019', // Evian (agua)
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu6->cod, 
            'producto_cod' => 'P0003', // Helado Stracciatella
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);

        // Menu 7 - Menú Doble Burger
        Producto::create([
            'cod' => 'M0007',
            'pvp' => 18.95,
            'nombre' => 'Menú Double Deluxe',
            'stock' => 35,
            'disponible'=> true,
            'precioCompra'=> '13.00',
            'imagen_url' => 'hb03'
        ]);

        $menu7 = Menu::create([
            'cod' => 'M0007', 
            'descripcion' => 'Menú Hamburguesa Doble'
        ]);

        // Hamburguesa principal
        MenuProducto::create([
            'menu_cod' => $menu7->cod, 
            'producto_cod' => 'C0028', // Double Burger
            'cantidad' => 1, 
            'descripcion' => 'Principal'
        ]);

        // Patatas
        MenuProducto::create([
            'menu_cod' => $menu7->cod, 
            'producto_cod' => 'C0033', // Patatas Fritas Deluxe
            'cantidad' => 1, 
            'descripcion' => 'Acompañamiento'
        ]);

        // Bebida
        MenuProducto::create([
            'menu_cod' => $menu7->cod, 
            'producto_cod' => 'B0001', // Coca-Cola
            'cantidad' => 1, 
            'descripcion' => 'Bebida'
        ]);

        // Postre
        MenuProducto::create([
            'menu_cod' => $menu7->cod, 
            'producto_cod' => 'P0002', // Cheesecake de Frutos Rojos
            'cantidad' => 1, 
            'descripcion' => 'Postre'
        ]);
    }
}