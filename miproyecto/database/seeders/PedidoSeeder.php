<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        // Pedido 1 - Desayuno
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => '2025-03-01',
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0001', 'cantidad' => 1, 'precio' => 8.95, 'estado' => 'Pendiente', 'pedido_id' => 'P0001', 'producto_id' => 'C0022'], // Desayuno Americano
            ['linea' => 'L0002', 'cantidad' => 1, 'precio' => 2.00, 'estado' => 'Pendiente', 'pedido_id' => 'P0001', 'producto_id' => 'B0023']  // Café con Leche
        ]);

        // Pedido 2 - Hamburguesa con patatas y bebida
        Pedido::create([
            'cod' => 'P0002',
            'fecha' => '2025-03-01',
            'estado' => 'En Preparación',
            'usuario_id' => 2
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0003', 'cantidad' => 1, 'precio' => 9.50, 'estado' => 'En Preparación', 'pedido_id' => 'P0002', 'producto_id' => 'C0027'], // Cheeseburger Clásica
            ['linea' => 'L0004', 'cantidad' => 1, 'precio' => 3.50, 'estado' => 'En Preparación', 'pedido_id' => 'P0002', 'producto_id' => 'C0033'], // Patatas Fritas
            ['linea' => 'L0005', 'cantidad' => 1, 'precio' => 2.50, 'estado' => 'En Preparación', 'pedido_id' => 'P0002', 'producto_id' => 'B0001']  // Coca-Cola
        ]);

        // Pedido 3 - Pizza para compartir
        Pedido::create([
            'cod' => 'P0003',
            'fecha' => '2025-03-02',
            'estado' => 'Entregado',
            'usuario_id' => 3
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0006', 'cantidad' => 1, 'precio' => 12.95, 'estado' => 'Entregado', 'pedido_id' => 'P0003', 'producto_id' => 'C0038'], // Pizza Pepperoni
            ['linea' => 'L0007', 'cantidad' => 2, 'precio' => 7.00, 'estado' => 'Entregado', 'pedido_id' => 'P0003', 'producto_id' => 'B0015'],  // San Miguel x2
            ['linea' => 'L0008', 'cantidad' => 1, 'precio' => 5.50, 'estado' => 'Entregado', 'pedido_id' => 'P0003', 'producto_id' => 'C0040']   // Tarta de Chocolate
        ]);

        // Pedido 4 - Vegetariano
        Pedido::create([
            'cod' => 'P0004',
            'fecha' => '2025-03-03',
            'estado' => 'Cancelado',
            'usuario_id' => 4
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0009', 'cantidad' => 1, 'precio' => 11.95, 'estado' => 'Cancelado', 'pedido_id' => 'P0004', 'producto_id' => 'C0035'], // Pizza Vegetariana
            ['linea' => 'L0010', 'cantidad' => 1, 'precio' => 2.00, 'estado' => 'Cancelado', 'pedido_id' => 'P0004', 'producto_id' => 'B0018']   // Solán de Cabras
        ]);

        // Pedido 5 - Menú completo
        Pedido::create([
            'cod' => 'P0005',
            'fecha' => '2025-03-04',
            'estado' => 'Pendiente',
            'usuario_id' => 5
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0011', 'cantidad' => 1, 'precio' => 11.95, 'estado' => 'Pendiente', 'pedido_id' => 'P0005', 'producto_id' => 'C0028'], // Double Burger
            ['linea' => 'L0012', 'cantidad' => 1, 'precio' => 3.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0005', 'producto_id' => 'C0033'],  // Patatas Fritas
            ['linea' => 'L0013', 'cantidad' => 1, 'precio' => 2.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0005', 'producto_id' => 'B0013'],  // Sprite
            ['linea' => 'L0014', 'cantidad' => 1, 'precio' => 4.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0005', 'producto_id' => 'C0042']   // Helado Stracciatella
        ]);

        // Pedido 6 - Desayuno para dos
        Pedido::create([
            'cod' => 'P0006',
            'fecha' => '2025-03-05',
            'estado' => 'En Preparación',
            'usuario_id' => 6
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0015', 'cantidad' => 2, 'precio' => 15.90, 'estado' => 'En Preparación', 'pedido_id' => 'P0006', 'producto_id' => 'C0025'], // Pancakes x2
            ['linea' => 'L0016', 'cantidad' => 2, 'precio' => 3.00, 'estado' => 'En Preparación', 'pedido_id' => 'P0006', 'producto_id' => 'B0022'],  // Espresso x2
            ['linea' => 'L0017', 'cantidad' => 1, 'precio' => 4.50, 'estado' => 'En Preparación', 'pedido_id' => 'P0006', 'producto_id' => 'C0024']   // Café y Croissant
        ]);

        // Pedido 7 - Pizza party
        Pedido::create([
            'cod' => 'P0007',
            'fecha' => '2025-03-06',
            'estado' => 'Entregado',
            'usuario_id' => 7
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0018', 'cantidad' => 1, 'precio' => 12.50, 'estado' => 'Entregado', 'pedido_id' => 'P0007', 'producto_id' => 'C0034'], // Pizza Carbonara
            ['linea' => 'L0019', 'cantidad' => 1, 'precio' => 13.50, 'estado' => 'Entregado', 'pedido_id' => 'P0007', 'producto_id' => 'C0039'], // Pizza Campesina
            ['linea' => 'L0020', 'cantidad' => 4, 'precio' => 10.00, 'estado' => 'Entregado', 'pedido_id' => 'P0007', 'producto_id' => 'B0001'], // Coca-Cola x4
            ['linea' => 'L0021', 'cantidad' => 2, 'precio' => 11.90, 'estado' => 'Entregado', 'pedido_id' => 'P0007', 'producto_id' => 'C0041']  // Cheesecake x2
        ]);

        // Pedido 8 - Cena romántica
        Pedido::create([
            'cod' => 'P0008',
            'fecha' => '2025-03-07',
            'estado' => 'Pendiente',
            'usuario_id' => 8
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0022', 'cantidad' => 1, 'precio' => 10.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0008', 'producto_id' => 'C0036'], // Pizza Margherita
            ['linea' => 'L0023', 'cantidad' => 1, 'precio' => 8.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0008', 'producto_id' => 'B0020'],  // Vino Tinto
            ['linea' => 'L0024', 'cantidad' => 2, 'precio' => 9.90, 'estado' => 'Pendiente', 'pedido_id' => 'P0008', 'producto_id' => 'C0043']   // Helado de Chocolate x2
        ]);
    }
}