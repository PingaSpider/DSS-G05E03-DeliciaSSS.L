<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Mesa;
use App\Models\Reserva;
use App\Models\Pedido;
use App\Models\LineaPedido;
use App\Models\Producto;
use App\Models\Menu;
use App\Models\Comida;
use App\Models\Bebida;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    // Ejecutar los seeders para rellenar la base de datos
    public function run(): void
    {
        // Usuarios
        Usuario::create([
            'nombre' => 'javier',
            'email' => 'javier@ua.es',
            'password' => '123456',  
            'telefono' => '987654321'
        ]);

        Usuario::create([
            'nombre' => 'cliente',
            'email' => 'cliente@example.com',
            'password' => '123456',  
            'telefono' => '123123123'
        ]);

        Usuario::create([
            'nombre' => 'maria',
            'email' => 'maria@example.com',
            'password' => '123456',
            'telefono' => '666777888'
        ]);

        // Mesas
        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M001',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M002',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 6,
            'codMesa' => 'M003',
            'ocupada' => true
        ]);

        // Reservas
        $usuario = Usuario::find(1);
        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1001,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 'M001',  
            'usuario_id' => 1,
            'usuario_email' => $usuario->email
        ]);

        Reserva::create([
            'fecha' => now()->addDays(3),
            'hora' => '21:00:00',
            'codReserva' => 1002,
            'cantPersona' => 4,
            'reservaConfirmada' => true,
            'mesa_id' => 'M002',  
            'usuario_id' => 2,
            'usuario_email' => 'cliente@example.com'
        ]);

        // PRODUCTOS
        // Primero creamos todos los productos, tanto comidas, bebidas como menús
        
        // Productos tipo comida
        Producto::create([
            'cod' => 'C0001',
            'pvp' => 8.50,
            'nombre' => 'Hamburguesa',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '6.70'
        ]);

        Producto::create([
            'cod' => 'C0002',
            'pvp' => 3.50,
            'nombre' => 'Patatas',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70'
        ]);

        Producto::create([
            'cod' => 'C0003',
            'pvp' => 9.95,
            'nombre' => 'Ensalada César',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '4.50'
        ]);

        // Productos tipo bebida
        Producto::create([
            'cod' => 'B0001',
            'pvp' => 2.50,
            'nombre' => 'Coca-Cola',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70'
        ]);

        Producto::create([
            'cod' => 'B0002',
            'pvp' => 3.50,
            'nombre' => 'Cerveza',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '1.20'
        ]);

        // Productos tipo menú
        Producto::create([
            'cod' => 'M0001',
            'pvp' => 12.50,
            'nombre' => 'Menú del día',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '6.70'
        ]);

        Producto::create([
            'cod' => 'M0002',
            'pvp' => 18.95,
            'nombre' => 'Menú Premium',
            'stock' => 10,
            'disponible'=> true,
            'precioCompra'=> '9.50'
        ]);

        // COMIDAS
        Comida::create([
            'descripcion' => 'Cheese Burguer',
            'cod' => 'C0001'
        ]);

        Comida::create([
            'descripcion' => 'Patatas Fritas',
            'cod' => 'C0002'
        ]);

        Comida::create([
            'descripcion' => 'Ensalada fresca con pollo y salsa césar',
            'cod' => 'C0003'
        ]);

        // BEBIDAS
        Bebida::create([
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Coca-Cola',
            'cod' => 'B0001'
        ]);

        Bebida::create([
            'tamanyo' => 'Caña',
            'tipoBebida' => 'Cerveza Rubia',
            'cod' => 'B0002'
        ]);

        // MENÚS - Ahora creamos los menús después de que existan los productos
        Menu::create([
            'cod' => 'M0001',
            'descripcion' => 'Menú del día'
        ]);

        Menu::create([
            'cod' => 'M0002',
            'descripcion' => 'Menú Premium'
        ]);

        // Relaciones entre menús y productos
        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0001', 'producto_cod' => 'C0001', 'cantidad' => 1, 'descripcion' => 'Hamburguesa principal'],
            ['menu_cod' => 'M0001', 'producto_cod' => 'C0002', 'cantidad' => 1, 'descripcion' => 'Patatas de acompañamiento'],
            ['menu_cod' => 'M0001', 'producto_cod' => 'B0001', 'cantidad' => 1, 'descripcion' => 'Bebida incluida']
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0002', 'producto_cod' => 'C0003', 'cantidad' => 1, 'descripcion' => 'Ensalada de primer plato'],
            ['menu_cod' => 'M0002', 'producto_cod' => 'C0002', 'cantidad' => 1, 'descripcion' => 'Patatas de acompañamiento'],
            ['menu_cod' => 'M0002', 'producto_cod' => 'B0002', 'cantidad' => 1, 'descripcion' => 'Cerveza incluida']
        ]);

        // PEDIDOS CONFIRMADOS
        // Pedido completo confirmado
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => now(),
            'estado' => 'confirmado',
            'usuario_id' => 1  
        ]);

        LineaPedido::create([
            'linea'=>'L0001',
            'cantidad' => 2,
            'precio' => 8.50,
            'estado' => 'confirmado',
            'pedido_id' => 'P0001', 
            'producto_id' => 'C0001'  
        ]);

        LineaPedido::create([
            'linea'=>'L0002',
            'cantidad' => 2,
            'precio' => 2.50,
            'estado' => 'confirmado',
            'pedido_id' => 'P0001', 
            'producto_id' => 'B0001'  
        ]);

        // CESTAS DE COMPRA (Pedidos en estado "en_cesta")
        // Cesta 1 - Usuario 2 con varios productos
        Pedido::create([
            'cod' => 'P0002',
            'fecha' => now(),
            'estado' => 'en_cesta',
            'usuario_id' => 2  
        ]);

        LineaPedido::create([
            'linea'=>'L0003',
            'cantidad' => 1,
            'precio' => 12.50,
            'estado' => 'en_cesta',
            'pedido_id' => 'P0002', 
            'producto_id' => 'M0001'  
        ]);

        LineaPedido::create([
            'linea'=>'L0004',
            'cantidad' => 1,
            'precio' => 3.50,
            'estado' => 'en_cesta',
            'pedido_id' => 'P0002', 
            'producto_id' => 'B0002'  
        ]);

        // Cesta 2 - Usuario 3 con un solo producto
        Pedido::create([
            'cod' => 'P0003',
            'fecha' => now(),
            'estado' => 'en_cesta',
            'usuario_id' => 3  
        ]);

        LineaPedido::create([
            'linea'=>'L0005',
            'cantidad' => 1,
            'precio' => 18.95,
            'estado' => 'en_cesta',
            'pedido_id' => 'P0003', 
            'producto_id' => 'M0002'  
        ]);

        // Cesta 3 - Cesta vacía para el usuario 1 (solo el pedido sin líneas)
        Pedido::create([
            'cod' => 'P0004',
            'fecha' => now(),
            'estado' => 'en_cesta',
            'usuario_id' => 1  
        ]);

        // HISTORIAL DE PEDIDOS
        // Crear varios pedidos con diferentes estados para historial
        $fechasAnteriores = [
            Carbon::now()->subDays(1),
            Carbon::now()->subDays(3),
            Carbon::now()->subDays(5),
            Carbon::now()->subDays(8),
        ];

        $estados = ['confirmado', 'preparando', 'entregado', 'pagado'];

        foreach ($fechasAnteriores as $index => $fecha) {
            $pedido = Pedido::create([
                'cod' => 'P100' . ($index + 1),
                'fecha' => $fecha,
                'estado' => $estados[$index % count($estados)],
                'usuario_id' => $index % 3 + 1  // Alternar entre usuarios 1, 2 y 3
            ]);

            // Añadir líneas de pedido diferentes para cada pedido histórico
            LineaPedido::create([
                'linea' => 'L100' . ($index * 2 + 1),
                'cantidad' => rand(1, 3),
                'precio' => 8.50,
                'estado' => $estados[$index % count($estados)],
                'pedido_id' => $pedido->cod, 
                'producto_id' => 'C0001'  
            ]);

            LineaPedido::create([
                'linea' => 'L100' . ($index * 2 + 2),
                'cantidad' => rand(1, 2),
                'precio' => 2.50,
                'estado' => $estados[$index % count($estados)],
                'pedido_id' => $pedido->cod, 
                'producto_id' => 'B0001'  
            ]);
        }
    }
}