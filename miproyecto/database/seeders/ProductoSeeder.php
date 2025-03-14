<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Menu;


class ProductoSeeder extends Seeder
{
    public function run()
    {
        // 1. Hamburguesa
        Producto::create([
            'cod' => 'C0001',
            'pvp' => 8.50,
            'nombre' => 'Hamburguesa',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '6.70'
        ]);

        Comida::create([
            'cod' => 'C0001',
            'descripcion' => 'Hamburguesa con queso y bacon'
        ]);

        // 2. Patatas Fritas
        Producto::create([
            'cod' => 'C0002',
            'pvp' => 3.50,
            'nombre' => 'Patatas Fritas',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70'
        ]);

        Comida::create([
            'cod' => 'C0002',
            'descripcion' => 'Patatas fritas con salsa especial'
        ]);

        // 3. Ensalada César
        Producto::create([
            'cod' => 'C0003',
            'pvp' => 9.95,
            'nombre' => 'Ensalada César',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '4.50'
        ]);

        Comida::create([
            'cod' => 'C0003',
            'descripcion' => 'Ensalada con pollo, crutones y parmesano'
        ]);

        // 4. Pizza Margarita
        Producto::create([
            'cod' => 'C0004',
            'pvp' => 8.50,
            'nombre' => 'Pizza Margarita',
            'stock' => 10,
            'disponible'=> true,
            'precioCompra'=> '5.00'
        ]);

        Comida::create([
            'cod' => 'C0004',
            'descripcion' => 'Pizza con tomate, mozzarella y albahaca'
        ]);

        // 5. Nuggets de Pollo
        Producto::create([
            'cod' => 'C0005',
            'pvp' => 6.75,
            'nombre' => 'Nuggets de Pollo',
            'stock' => 40,
            'disponible'=> true,
            'precioCompra'=> '3.20'
        ]);

        Comida::create([
            'cod' => 'C0005',
            'descripcion' => 'Nuggets de pollo con salsa BBQ'
        ]);

        // 6. Sándwich Club
        Producto::create([
            'cod' => 'C0006',
            'pvp' => 7.50,
            'nombre' => 'Sándwich Club',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '4.00'
        ]);

        Comida::create([
            'cod' => 'C0006',
            'descripcion' => 'Sándwich con pollo, bacon, lechuga y tomate'
        ]);

        // 7. Hot Dog
        Producto::create([
            'cod' => 'C0007',
            'pvp' => 5.50,
            'nombre' => 'Hot Dog',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '2.50'
        ]);

        Comida::create([
            'cod' => 'C0007',
            'descripcion' => 'Hot Dog con cebolla crujiente y mostaza'
        ]);

        // 8. Tacos
        Producto::create([
            'cod' => 'C0008',
            'pvp' => 7.95,
            'nombre' => 'Tacos',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '4.00'
        ]);

        Comida::create([
            'cod' => 'C0008',
            'descripcion' => 'Tacos rellenos de carne y guacamole'
        ]);

        // 9. Wrap Vegetariano
        Producto::create([
            'cod' => 'C0009',
            'pvp' => 6.95,
            'nombre' => 'Wrap Vegetariano',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '3.80'
        ]);

        Comida::create([
            'cod' => 'C0009',
            'descripcion' => 'Wrap con hummus y vegetales frescos'
        ]);

        // 10. Sushi Box
        Producto::create([
            'cod' => 'C0010',
            'pvp' => 12.50,
            'nombre' => 'Sushi Box',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '7.50'
        ]);

        Comida::create([
            'cod' => 'C0010',
            'descripcion' => 'Caja de sushi variado con soja'
        ]);

        // Productos tipo bebida
        // 1. Coca-Cola
        Producto::create([
            'cod' => 'B0001',
            'pvp' => 2.50,
            'nombre' => 'Coca-Cola',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70'
        ]);

        Bebida::create([
            'cod' => 'B0001',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Gaseosa'
        ]);

        // 2. Cerveza
        Producto::create([
            'cod' => 'B0002',
            'pvp' => 3.50,
            'nombre' => 'Cerveza',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '1.20'
        ]);

        Bebida::create([
            'cod' => 'B0002',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Cerveza'
        ]);

        // 3. Agua Mineral
        Producto::create([
            'cod' => 'B0003',
            'pvp' => 1.50,
            'nombre' => 'Agua Mineral',
            'stock' => 100,
            'disponible'=> true,
            'precioCompra'=> '0.30'
        ]);

        Bebida::create([
            'cod' => 'B0003',
            'tamanyo' => 'Pequeño',
            'tipoBebida' => 'Agua'
        ]);

        // 4. Jugo de Naranja
        Producto::create([
            'cod' => 'B0004',
            'pvp' => 3.00,
            'nombre' => 'Jugo de Naranja',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '1.00'
        ]);

        Bebida::create([
            'cod' => 'B0004',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Jugo'
        ]);

        // 5. Té Helado
        Producto::create([
            'cod' => 'B0005',
            'pvp' => 2.75,
            'nombre' => 'Té Helado',
            'stock' => 40,
            'disponible'=> true,
            'precioCompra'=> '0.80'
        ]);

        Bebida::create([
            'cod' => 'B0005',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Té'
        ]);

        // 1. Menú Clásico
        Producto::create([
            'cod' => 'M0001',
            'pvp' => 12.50,
            'nombre' => 'Menú Clásico',
            'stock' => 10,
            'disponible'=> true,
            'precioCompra'=> '7.50'
        ]);

        Menu::create([
            'cod' => 'M0001',
            'descripcion' => 'Menú Clásico con hamburguesa, patatas y bebida'
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0001', 'producto_cod' => 'C0001', 'cantidad' => 1, 'descripcion' => 'Hamburguesa clásica'],
            ['menu_cod' => 'M0001', 'producto_cod' => 'C0002', 'cantidad' => 1, 'descripcion' => 'Patatas de acompañamiento'],
            ['menu_cod' => 'M0001', 'producto_cod' => 'B0001', 'cantidad' => 1, 'descripcion' => 'Coca-Cola incluida']
        ]);

        // 2. Menú Saludable
        Producto::create([
            'cod' => 'M0002',
            'pvp' => 14.95,
            'nombre' => 'Menú Saludable',
            'stock' => 8,
            'disponible'=> true,
            'precioCompra'=> '9.50'
        ]);

        Menu::create([
            'cod' => 'M0002',
            'descripcion' => 'Menú saludable con ensalada, fruta y agua'
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0002', 'producto_cod' => 'C0003', 'cantidad' => 1, 'descripcion' => 'Ensalada César'],
            ['menu_cod' => 'M0002', 'producto_cod' => 'C0004', 'cantidad' => 1, 'descripcion' => 'Fruta de temporada'],
            ['menu_cod' => 'M0002', 'producto_cod' => 'B0003', 'cantidad' => 1, 'descripcion' => 'Agua Mineral']
        ]);

        // 3. Menú Infantil
        Producto::create([
            'cod' => 'M0003',
            'pvp' => 9.95,
            'nombre' => 'Menú Infantil',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '5.50'
        ]);

        Menu::create([
            'cod' => 'M0003',
            'descripcion' => 'Menú infantil con nuggets, patatas y jugo'
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0003', 'producto_cod' => 'C0005', 'cantidad' => 1, 'descripcion' => 'Nuggets de pollo'],
            ['menu_cod' => 'M0003', 'producto_cod' => 'C0002', 'cantidad' => 1, 'descripcion' => 'Patatas de acompañamiento'],
            ['menu_cod' => 'M0003', 'producto_cod' => 'B0004', 'cantidad' => 1, 'descripcion' => 'Jugo de Naranja']
        ]);
    }
}
