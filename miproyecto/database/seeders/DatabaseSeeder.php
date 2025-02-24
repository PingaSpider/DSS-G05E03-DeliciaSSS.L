<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Mesa;
use App\Models\Reserva;
use App\Models\Pedido;
use App\Models\LineaPedido;
use App\Models\Carta;
use App\Models\Stock;
use App\Models\Menu;
use App\Models\Comida;
use App\Models\Bebida;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // 1. Usuarios
        Usuario::create([
            'nombre' => 'javier',
            'email' => 'javier@ua.es',
            'password' => '123456',
            'telefono' => '987654321'
        ]);

        Usuario::create([
            'nombre' => 'maria',
            'email' => 'maria@ua.es',
            'password' => '123456',
            'telefono' => '654321987'
        ]);

        Usuario::create([
            'nombre' => 'carlos',
            'email' => 'carlos@ua.es',
            'password' => '123456',
            'telefono' => '123456789'
        ]);

        
        // 2. Mesas
        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M001',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M002',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 6,
            'codMesa' => 'M003',
            'ocupada' => false
        ]);

        
        // 3. Reservas
        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1001,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 1,
            'usuario_id' => 1
        ]);

        Reserva::create([
            'fecha' => now()->addDays(3),
            'hora' => '20:00:00',
            'codReserva' => 1002,
            'cantPersona' => 4,
            'reservaConfirmada' => false,
            'mesa_id' => 2,
            'usuario_id' => 2
        ]);

        Reserva::create([
            'fecha' => now()->addDays(4),
            'hora' => '18:00:00',
            'codReserva' => 1003,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => 3,
            'usuario_id' => 3
        ]);

        // 4. Pedidos
        Pedido::create([
            'cod' => 5001,
            'fecha' => now(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        Pedido::create([
            'cod' => 5002,
            'fecha' => now(),
            'estado' => 'Completado',
            'usuario_id' => 2
        ]);

        Pedido::create([
            'cod' => 5003,
            'fecha' => now(),
            'estado' => 'Cancelado',
            'usuario_id' => 3
        ]);

        // 5. Cartas
        Carta::create([
            'cod' => 'C001',
            'precio' => 12.50,
            'nombre' => 'Pizza'
        ]);

        Carta::create([
            'cod' => 'C002',
            'precio' => 8.00,
            'nombre' => 'Ensalada'
        ]);

        Carta::create([
            'cod' => 'C003',
            'precio' => 5.00,
            'nombre' => 'Refresco'
        ]);

        // 6. Stocks
        Stock::create([
            'cantidad' => 50,
            'disponible' => true,
            'precio' => 12.50,
            'carta_id' => 1
        ]);

        Stock::create([
            'cantidad' => 30,
            'disponible' => true,
            'precio' => 8.00,
            'carta_id' => 2
        ]);

        Stock::create([
            'cantidad' => 100,
            'disponible' => true,
            'precio' => 5.00,
            'carta_id' => 3
        ]);

        // 7. LineaPedidos
        LineaPedido::create([
            'cantidad' => 2,
            'precio' => 25.00,
            'estado' => 'Preparando',
            'pedido_id' => 1,
            'carta_id' => 1
        ]);

        LineaPedido::create([
            'cantidad' => 1,
            'precio' => 8.00,
            'estado' => 'Entregado',
            'pedido_id' => 2,
            'carta_id' => 2
        ]);

        LineaPedido::create([
            'cantidad' => 3,
            'precio' => 15.00,
            'estado' => 'Cancelado',
            'pedido_id' => 3,
            'carta_id' => 3
        ]);

        // 8. Menus
        Menu::create(['descripcion' => 'Menú del día']);
        Menu::create(['descripcion' => 'Menú vegetariano']);
        Menu::create(['descripcion' => 'Menú infantil']);

        // 9. Comidas
        Comida::create(['descripcion' => 'Ensalada']);
        Comida::create(['descripcion' => 'Hamburguesa']);
        Comida::create(['descripcion' => 'Pizza']);

        // 10. Bebidas
        Bebida::create(['tamanyo' => 'Grande', 'tipoBebida' => 'Coca-Cola']);
        Bebida::create(['tamanyo' => 'Mediano', 'tipoBebida' => 'Agua']);
        Bebida::create(['tamanyo' => 'Pequeño', 'tipoBebida' => 'Cerveza']);
    }
}
