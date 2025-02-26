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

       
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => now(),
            'estado' => 'Pendiente',
            'usuario_id' => 1  
        ]);

        
        Producto::create([
            'cod' => 'C0001',
            'pvp' => 8.50,
            'nombre' => 'Hamburguesa',
            'stock' => 2,
            'disponible'=> true,
            'precioCompra'=> '6.70'
        ]);

        Producto::create([
            'cod' => 'B0001',
            'pvp' => 2.50,
            'nombre' => 'coca-cola',
            'stock' => 2,
            'disponible'=> true,
            'precioCompra'=> '0.70'
        ]);

        Producto::create([
            'cod' => 'M0001',
            'pvp' => 12.50,
            'nombre' => 'Menu Lunes',
            'stock' => 2,
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
       
                
        LineaPedido::create([
            'linea'=>'L0001',
            'cantidad' => 2,
            'precio' => 25.00,
            'estado' => 'Preparando',
            'pedido_id' => 'P0001', 
            'producto_id' => 'C0001'  
        ]);

        
       
        $menu=Menu::create([
            'cod' => 'M0001',
            'descripcion' => 'Menú del día'
        ]);

        \DB::table('menu_producto')->insert([
            ['menu_cod' => 'M0001', 'producto_cod' => 'C0001', 'cantidad' => 1],
            ['menu_cod' => 'M0001', 'producto_cod' => 'C0002', 'cantidad' => 1],
            ['menu_cod' => 'M0001', 'producto_cod' => 'B0001', 'cantidad' => 1]
        ]);

        Comida::create([
            'descripcion' => 'Cheese Burguer',
            'cod' => 'C0001'
        ]);

        Comida::create([
            'descripcion' => 'Patatas Fritas',
            'cod' => 'C0002'
        ]);

        
        Bebida::create([
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Coca-Cola',
            'cod' => 'B0001'
        ]);

        //Insertar descripciones a menu_producto con la funcion descripcionProductos
        \DB::table('menu_producto')
            ->where('menu_cod', 'M0001')
            ->where('producto_cod', 'C0001')
            ->update([
                'descripcion' => $menu->descripcionProductos('C0001')
        ]);
        
        \DB::table('menu_producto')
            ->where('menu_cod', 'M0001')
            ->where('producto_cod', 'C0002')
            ->update([
                'descripcion' => $menu->descripcionProductos('C0002')
        ]);

        \DB::table('menu_producto')
            ->where('menu_cod', 'M0001')
            ->where('producto_cod', 'B0001')
            ->update([
                'descripcion' => $menu->descripcionProductos('B0001')
        ]);

    }
}
