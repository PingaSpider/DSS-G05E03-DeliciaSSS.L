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
            'nombre' => 'alvaro',
            'email' => 'alvaro@example.com',
            'password' => '123456',
            'telefono' => '111222333'
        ]);

        Usuario::create([
            'nombre' => 'beatriz',
            'email' => 'beatriz@example.com',
            'password' => '123456',
            'telefono' => '444555666'
        ]);

        Usuario::create([
            'nombre' => 'carlos',
            'email' => 'carlos@example.com',
            'password' => '123456',
            'telefono' => '777888999'
        ]);

        Usuario::create([
            'nombre' => 'daniela',
            'email' => 'daniela@example.com',
            'password' => '123456',
            'telefono' => '123321123'
        ]);

        Usuario::create([
            'nombre' => 'esteban',
            'email' => 'esteban@example.com',
            'password' => '123456',
            'telefono' => '321123321'
        ]);

        Usuario::create([
            'nombre' => 'fabiana',
            'email' => 'fabiana@example.com',
            'password' => '123456',
            'telefono' => '456654456'
        ]);

        Usuario::create([
            'nombre' => 'gonzalo',
            'email' => 'gonzalo@example.com',
            'password' => '123456',
            'telefono' => '654456654'
        ]);

        Usuario::create([
            'nombre' => 'helena',
            'email' => 'helena@example.com',
            'password' => '123456',
            'telefono' => '789987789'
        ]);

        Usuario::create([
            'nombre' => 'ignacio',
            'email' => 'ignacio@example.com',
            'password' => '123456',
            'telefono' => '987789987'
        ]);

        Usuario::create([
            'nombre' => 'jose',
            'email' => 'jose@example.com',
            'password' => '123456',
            'telefono' => '159951159'
        ]);

        Usuario::create([
            'nombre' => 'karina',
            'email' => 'karina@example.com',
            'password' => '123456',
            'telefono' => '951159951'
        ]);

        Usuario::create([
            'nombre' => 'luis',
            'email' => 'luis@example.com',
            'password' => '123456',
            'telefono' => '753357753'
        ]);

        Usuario::create([
            'nombre' => 'marta',
            'email' => 'marta@example.com',
            'password' => '123456',
            'telefono' => '357753357'
        ]);

        Usuario::create([
            'nombre' => 'nicolas',
            'email' => 'nicolas@example.com',
            'password' => '123456',
            'telefono' => '258852258'
        ]);

        Usuario::create([
            'nombre' => 'olivia',
            'email' => 'olivia@example.com',
            'password' => '123456',
            'telefono' => '852258852'
        ]);

        Usuario::create([
            'nombre' => 'pedro',
            'email' => 'pedro@example.com',
            'password' => '123456',
            'telefono' => '147741147'
        ]);

        Usuario::create([
            'nombre' => 'quique',
            'email' => 'quique@example.com',
            'password' => '123456',
            'telefono' => '741147741'
        ]);

        Usuario::create([
            'nombre' => 'rosario',
            'email' => 'rosario@example.com',
            'password' => '123456',
            'telefono' => '369963369'
        ]);

        Usuario::create([
            'nombre' => 'santiago',
            'email' => 'santiago@example.com',
            'password' => '123456',
            'telefono' => '963369963'
        ]);

        Usuario::create([
            'nombre' => 'teresa',
            'email' => 'teresa@example.com',
            'password' => '123456',
            'telefono' => '789123456'
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
        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1001,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 'M001',  
            'usuario_id' => 1,
            'usuario_email' => 'alvaro@example.com'
        ]);

        Reserva::create([
            'fecha' => now()->addDays(3),
            'hora' => '21:00:00',
            'codReserva' => 1002,
            'cantPersona' => 4,
            'reservaConfirmada' => true,
            'mesa_id' => 'M002',  
            'usuario_id' => 2,
            'usuario_email' => 'beatriz@example.com'
        ]);

        // PRODUCTOS
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

        // Pedido 1
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
