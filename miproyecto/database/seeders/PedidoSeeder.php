<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => '2025-03-01',
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0001', 'cantidad' => 2, 'precio' => 17.00, 'estado' => 'Pendiente', 'pedido_id' => 'P0001', 'producto_id' => 'C0001'],
            ['linea' => 'L0002', 'cantidad' => 1, 'precio' => 2.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0001', 'producto_id' => 'B0001']
        ]);

        // Pedido 2
        Pedido::create([
            'cod' => 'P0002',
            'fecha' => '2025-03-01',
            'estado' => 'En Preparación',
            'usuario_id' => 2
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0003', 'cantidad' => 1, 'precio' => 9.95, 'estado' => 'En Preparación', 'pedido_id' => 'P0002', 'producto_id' => 'C0003'],
            ['linea' => 'L0004', 'cantidad' => 1, 'precio' => 3.50, 'estado' => 'En Preparación', 'pedido_id' => 'P0002', 'producto_id' => 'B0002']
        ]);

        // Pedido 3
        Pedido::create([
            'cod' => 'P0003',
            'fecha' => '2025-03-02',
            'estado' => 'Entregado',
            'usuario_id' => 3
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0005', 'cantidad' => 3, 'precio' => 25.50, 'estado' => 'Entregado', 'pedido_id' => 'P0003', 'producto_id' => 'C0001'],
            ['linea' => 'L0006', 'cantidad' => 2, 'precio' => 7.00, 'estado' => 'Entregado', 'pedido_id' => 'P0003', 'producto_id' => 'B0001']
        ]);

        // Pedido 4
        Pedido::create([
            'cod' => 'P0004',
            'fecha' => '2025-03-03',
            'estado' => 'Cancelado',
            'usuario_id' => 4
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0007', 'cantidad' => 1, 'precio' => 14.95, 'estado' => 'Cancelado', 'pedido_id' => 'P0004', 'producto_id' => 'C0003'],
            ['linea' => 'L0008', 'cantidad' => 1, 'precio' => 2.50, 'estado' => 'Cancelado', 'pedido_id' => 'P0004', 'producto_id' => 'B0001']
        ]);

        // Pedido 5
        Pedido::create([
            'cod' => 'P0005',
            'fecha' => '2025-03-04',
            'estado' => 'Pendiente',
            'usuario_id' => 5
        ]);

        \DB::table('linea_pedidos')->insert([
            ['linea' => 'L0009', 'cantidad' => 2, 'precio' => 19.90, 'estado' => 'Pendiente', 'pedido_id' => 'P0005', 'producto_id' => 'C0002'],
            ['linea' => 'L0010', 'cantidad' => 1, 'precio' => 3.50, 'estado' => 'Pendiente', 'pedido_id' => 'P0005', 'producto_id' => 'B0002']
        ]);
    }
}
