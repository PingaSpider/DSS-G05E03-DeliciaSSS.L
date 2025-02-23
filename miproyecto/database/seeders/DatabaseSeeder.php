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
        
        Usuario::create([
            'nombre' => 'javier',
            'email' => 'javier@ua.es',
            'password' => '123456',  
            'telefono' => '987654321'
        ]);

        
        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M001',
            'ocupada' => false
        ]);

        
        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1001,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 1,  
            'usuario_id' => 1  
        ]);

       
        Pedido::create([
            'cod' => 5001,
            'fecha' => now(),
            'estado' => 'Pendiente',
            'usuario_id' => 1  
        ]);

        
        Carta::create([
            'cod' => 'C001',
            'precio' => 12.50,
            'nombre' => 'Pizza'
        ]);

       
        Stock::create([
            'cantidad' => 50,
            'disponible' => true,
            'precio' => 12.50,
            'carta_id' => 1  
        ]);

        
        LineaPedido::create([
            'cantidad' => 2,
            'precio' => 25.00,
            'estado' => 'Preparando',
            'pedido_id' => 1, 
            'carta_id' => 1  
        ]);

       
        Menu::create([
            'descripcion' => 'Menú del día'
        ]);

        
        Comida::create([
            'descripcion' => 'Ensalada'
        ]);

        
        Bebida::create([
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Coca-Cola'
        ]);
    }
}
