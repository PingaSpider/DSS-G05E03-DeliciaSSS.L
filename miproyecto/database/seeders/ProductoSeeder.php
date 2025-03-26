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

        // 11. Pasta Alfredo
        Producto::create([
            'cod' => 'C0011',
            'pvp' => 10.50,
            'nombre' => 'Pasta Alfredo',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '5.50'
        ]);

        Comida::create([
            'cod' => 'C0011',
            'descripcion' => 'Pasta con salsa Alfredo y pollo'
        ]);

        // 12. Burrito
        Producto::create([
            'cod' => 'C0012',
            'pvp' => 8.75,
            'nombre' => 'Burrito',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '4.20'
        ]);

        Comida::create([
            'cod' => 'C0012',
            'descripcion' => 'Burrito relleno de carne y frijoles'
        ]);

        // 13. Sopa de Tomate
        Producto::create([
            'cod' => 'C0013',
            'pvp' => 6.25,
            'nombre' => 'Sopa de Tomate',
            'stock' => 12,
            'disponible'=> true,
            'precioCompra'=> '3.00'
        ]);

        Comida::create([
            'cod' => 'C0013',
            'descripcion' => 'Sopa de tomate con albahaca fresca'
        ]);

        // 14. Lasagna
        Producto::create([
            'cod' => 'C0014',
            'pvp' => 11.00,
            'nombre' => 'Lasagna',
            'stock' => 10,
            'disponible'=> true,
            'precioCompra'=> '6.00'
        ]);

        Comida::create([
            'cod' => 'C0014',
            'descripcion' => 'Lasagna de carne con queso y bechamel'
        ]);

        // 15. Falafel
        Producto::create([
            'cod' => 'C0015',
            'pvp' => 7.50,
            'nombre' => 'Falafel',
            'stock' => 18,
            'disponible'=> true,
            'precioCompra'=> '3.80'
        ]);

        Comida::create([
            'cod' => 'C0015',
            'descripcion' => 'Falafel con salsa de yogurt'
        ]);

        // 16. Tortilla de Patatas
        Producto::create([
            'cod' => 'C0016',
            'pvp' => 7.95,
            'nombre' => 'Tortilla de Patatas',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '3.50'
        ]);

        Comida::create([
            'cod' => 'C0016',
            'descripcion' => 'Tortilla de patatas clásica con cebolla'
        ]);

        // 17. Empanadas
        Producto::create([
            'cod' => 'C0017',
            'pvp' => 5.75,
            'nombre' => 'Empanadas',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '2.80'
        ]);

        Comida::create([
            'cod' => 'C0017',
            'descripcion' => 'Empanadas de carne o queso'
        ]);

        // 18. Croquetas de Jamón
        Producto::create([
            'cod' => 'C0018',
            'pvp' => 7.25,
            'nombre' => 'Croquetas de Jamón',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '3.60'
        ]);

        Comida::create([
            'cod' => 'C0018',
            'descripcion' => 'Croquetas de jamón ibérico crujientes'
        ]);
        
        // 19. Arepas
        Producto::create([
            'cod' => 'C0019',
            'pvp' => 8.50,
            'nombre' => 'Arepas',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '4.50'
        ]);

        Comida::create([
            'cod' => 'C0019',
            'descripcion' => 'Arepas rellenas de queso o carne'
        ]);

        // 20. Risotto de Champiñones
        Producto::create([
            'cod' => 'C0020',
            'pvp' => 13.50,
            'nombre' => 'Risotto de Champiñones',
            'stock' => 12,
            'disponible'=> true,
            'precioCompra'=> '7.20'
        ]);

        Comida::create([
            'cod' => 'C0020',
            'descripcion' => 'Risotto cremoso con champiñones y parmesano'
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

        // 6. Red Bull
        Producto::create([
            'cod' => 'B0006',
            'pvp' => 3.75,
            'nombre' => 'Red Bull',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '1.50'
        ]);

        Bebida::create([
            'cod' => 'B0006',
            'tamanyo' => 'Pequeño',
            'tipoBebida' => 'Energética'
        ]);

        // 7. Latte Macchiato
        Producto::create([
            'cod' => 'B0007',
            'pvp' => 4.00,
            'nombre' => 'Latte Macchiato',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '2.00'
        ]);

        Bebida::create([
            'cod' => 'B0007',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Café'
        ]);

        // 8. Smoothie de Fresas
        Producto::create([
            'cod' => 'B0008',
            'pvp' => 4.50,
            'nombre' => 'Smoothie de Fresas',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '2.50'
        ]);

        Bebida::create([
            'cod' => 'B0008',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Batido'
        ]);

        // 9. Limonada
        Producto::create([
            'cod' => 'B0009',
            'pvp' => 3.00,
            'nombre' => 'Limonada',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '1.20'
        ]);

        Bebida::create([
            'cod' => 'B0009',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Refresco'
        ]);

        // 10. Chocolate Caliente
        Producto::create([
            'cod' => 'B0010',
            'pvp' => 3.75,
            'nombre' => 'Chocolate Caliente',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '2.00'
        ]);

        Bebida::create([
            'cod' => 'B0010',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Chocolate'
        ]);

        // 11. Vino Tinto
        Producto::create([
            'cod' => 'B0011',
            'pvp' => 5.00,
            'nombre' => 'Vino Tinto',
            'stock' => 10,
            'disponible'=> true,
            'precioCompra'=> '3.00'
        ]);

        Bebida::create([
            'cod' => 'B0011',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Vino'
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

        // 4. Menú Español
        Producto::create([
            'cod' => 'M0004',
            'pvp' => 13.95,
            'nombre' => 'Menú Español',
            'stock' => 12,
            'disponible'=> true,
            'precioCompra'=> '8.00'
        ]);

        Menu::create([
            'cod' => 'M0004',
            'descripcion' => 'Menú con tortilla de patatas, pan con tomate y cerveza'
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0004', 'producto_cod' => 'C0016', 'cantidad' => 1, 'descripcion' => 'Tortilla de patatas clásica'],
            ['menu_cod' => 'M0004', 'producto_cod' => 'C0007', 'cantidad' => 1, 'descripcion' => 'Pan con tomate'],
            ['menu_cod' => 'M0004', 'producto_cod' => 'B0002', 'cantidad' => 1, 'descripcion' => 'Cerveza incluida']
        ]);

        // 5. Menú Mexicano
        Producto::create([
            'cod' => 'M0005',
            'pvp' => 15.50,
            'nombre' => 'Menú Mexicano',
            'stock' => 10,
            'disponible'=> true,
            'precioCompra'=> '9.00'
        ]);

        Menu::create([
            'cod' => 'M0005',
            'descripcion' => 'Menú con tacos, nachos y jugo'
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0005', 'producto_cod' => 'C0008', 'cantidad' => 2, 'descripcion' => 'Tacos de carne con guacamole'],
            ['menu_cod' => 'M0005', 'producto_cod' => 'C0010', 'cantidad' => 1, 'descripcion' => 'Nachos con queso'],
            ['menu_cod' => 'M0005', 'producto_cod' => 'B0004', 'cantidad' => 1, 'descripcion' => 'Jugo de Naranja']
        ]);
    }
}
