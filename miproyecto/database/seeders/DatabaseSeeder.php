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


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        Usuario::create([
            'nombre' => 'javier',
            'email' => 'javier@ua.es',
            'password' => '123456',  
            'telefono' => '987654321'
        ]);

        
        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M001',
            'ocupada' => false
        ]);

        
        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1001,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 'M001',  
            'usuario_id' => 1  
        ]);

       
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => now(),
            'estado' => 'Pendiente',
            'usuario_id' => 1  
        ]);

        
        Producto::create([
            'cod' => 'C0001',
            'pvp' => 12.50,
            'nombre' => 'Pizza',
            'stock' => 2,
            'disponible'=> true,
            'precioCompra'=> '6.70'
        ]);

        Producto::create([
            'cod' => 'C0002',
            'pvp' => 2.50,
            'nombre' => 'coca-cola',
            'stock' => 2,
            'disponible'=> true,
            'precioCompra'=> '0.70'
        ]);

        Producto::create([
            'cod' => 'C0003',
            'pvp' => 12.50,
            'nombre' => 'Menu Lunes',
            'stock' => 2,
            'disponible'=> true,
            'precioCompra'=> '6.70'
        ]);

       
                
        LineaPedido::create([
            'linea'=>'L0001',
            'cantidad' => 2,
            'precio' => 25.00,
            'estado' => 'Preparando',
            'pedido_id' => 'P0001', 
            'producto_id' => 'C0001'  
        ]);

       
        Menu::create([
            'descripcion' => 'Menú del día',
            'cod_producto' => 'C0003'
        ]);

        
        Comida::create([
            'descripcion' => 'Pizza Peperoni',
            'cod_producto' => 'C0001'
        ]);

        
        Bebida::create([
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Coca-Cola',
            'cod_producto' => 'C0002'
        ]);
    }
}
