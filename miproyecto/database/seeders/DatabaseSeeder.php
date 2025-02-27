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
            'nombre' => 'Pizza Margarita',
            'descripcion' => 'Pizza con tomate, mozzarella y albahaca.',
            'precio' => 8.50
        ]);

        Comida::create([
            'nombre' => 'Hamburguesa Clásica',
            'descripcion' => 'Carne de res con lechuga, tomate y queso cheddar.',
            'precio' => 7.00
        ]);

        Comida::create([
            'nombre' => 'Ensalada César',
            'descripcion' => 'Lechuga romana, pollo a la parrilla y aderezo César.',
            'precio' => 6.50
        ]);

        Comida::create([
            'nombre' => 'Tacos al Pastor',
            'descripcion' => 'Tacos de cerdo marinados con piña y cilantro.',
            'precio' => 5.00
        ]);

        Comida::create([
            'nombre' => 'Sushi Roll California',
            'descripcion' => 'Rollo de cangrejo, aguacate y pepino.',
            'precio' => 9.00
        ]);

        Comida::create([
            'nombre' => 'Espaguetis Carbonara',
            'descripcion' => 'Pasta con salsa de huevo, panceta y queso parmesano.',
            'precio' => 10.00
        ]);

        Comida::create([
            'nombre' => 'Pollo Asado',
            'descripcion' => 'Pollo a la parrilla con guarnición de papas fritas.',
            'precio' => 11.50
        ]);

        Comida::create([
            'nombre' => 'Lasagna Boloñesa',
            'descripcion' => 'Capas de pasta, carne molida y salsa de tomate.',
            'precio' => 9.50
        ]);

        Comida::create([
            'nombre' => 'Falafel con Hummus',
            'descripcion' => 'Bolitas de garbanzo con hummus y ensalada.',
            'precio' => 7.50
        ]);

        Comida::create([
            'nombre' => 'Churrasco con Chimichurri',
            'descripcion' => 'Corte de carne a la parrilla con salsa chimichurri.',
            'precio' => 15.00
        ]);


        // BEBIDAS
        Bebida::create([
            'nombre' => 'Coca-Cola',
            'descripcion' => 'Refresco de cola.',
            'precio' => 2.00
        ]);

        Bebida::create([
            'nombre' => 'Limonada Natural',
            'descripcion' => 'Bebida refrescante de limón.',
            'precio' => 2.50
        ]);

        Bebida::create([
            'nombre' => 'Cerveza Artesanal',
            'descripcion' => 'Cerveza local con sabor intenso.',
            'precio' => 4.00
        ]);

        Bebida::create([
            'nombre' => 'Té Helado',
            'descripcion' => 'Té frío con limón.',
            'precio' => 2.20
        ]);

        Bebida::create([
            'nombre' => 'Café Espresso',
            'descripcion' => 'Café concentrado y aromático.',
            'precio' => 1.80
        ]);


        // MENÚS - Ahora creamos los menús después de que existan los productos
        Menu::create([
            'nombre' => 'Menú Ejecutivo',
            'descripcion' => 'Entrada, plato principal y postre.',
            'precio' => 15.00
        ]);

        Menu::create([
            'nombre' => 'Menú Infantil',
            'descripcion' => 'Plato principal pequeño con bebida.',
            'precio' => 8.00
        ]);

        Menu::create([
            'nombre' => 'Menú Degustación',
            'descripcion' => 'Variedad de platos en pequeñas porciones.',
            'precio' => 25.00
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
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => now(),
            'estado' => 'confirmado',
            'usuario_id' => 1  
        ]);

        Pedido::create([
            'cod' => 'P0002',
            'fecha' => now(),
            'estado' => 'pendiente',
            'usuario_id' => 2  
        ]);

        Pedido::create([
            'cod' => 'P0003',
            'fecha' => now(),
            'estado' => 'entregado',
            'usuario_id' => 3  
        ]);

        Pedido::create([
            'cod' => 'P0004',
            'fecha' => now(),
            'estado' => 'cancelado',
            'usuario_id' => 4  
        ]);

        Pedido::create([
            'cod' => 'P0005',
            'fecha' => now(),
            'estado' => 'en_cesta',
            'usuario_id' => 5  
        ]);

        // Obtenemos los IDs de los pedidos recién creados
        $pedido1 = Pedido::where('cod', 'P0001')->first()->id;
        $pedido2 = Pedido::where('cod', 'P0002')->first()->id;
        $pedido3 = Pedido::where('cod', 'P0003')->first()->id;
        $pedido4 = Pedido::where('cod', 'P0004')->first()->id;
        $pedido5 = Pedido::where('cod', 'P0005')->first()->id;

        // Lineas de Pedido para P0001
        LineaPedido::create([
            'linea'=>'L0001',
            'cantidad' => 2,
            'precio' => 8.50,
            'estado' => 'confirmado',
            'pedido_id' => $pedido1,
            'producto_id' => 'C0001'
        ]);

        LineaPedido::create([
            'linea'=>'L0002',
            'cantidad' => 2,
            'precio' => 2.50,
            'estado' => 'confirmado',
            'pedido_id' => $pedido1,
            'producto_id' => 'B0001'
        ]);

        // Lineas de Pedido para P0002
        LineaPedido::create([
            'linea'=>'L0003',
            'cantidad' => 1,
            'precio' => 12.50,
            'estado' => 'pendiente',
            'pedido_id' => $pedido2,
            'producto_id' => 'M0001'
        ]);

        // Lineas de Pedido para P0003
        LineaPedido::create([
            'linea'=>'L0004',
            'cantidad' => 1,
            'precio' => 18.95,
            'estado' => 'entregado',
            'pedido_id' => $pedido3,
            'producto_id' => 'M0002'
        ]);

        // Pedido P0004 no tiene líneas (cancelado)

        // Lineas de Pedido para P0005 (en cesta)
        LineaPedido::create([
            'linea'=>'L0005',
            'cantidad' => 1,
            'precio' => 7.50,
            'estado' => 'en_cesta',
            'pedido_id' => $pedido5,
            'producto_id' => 'C0003'
        ]);
    }
}
