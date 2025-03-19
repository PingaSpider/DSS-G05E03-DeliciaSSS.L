<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Usuario;
use Carbon\Carbon;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_pedido()
    {
        // Crear usuario para asignar al pedido
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear pedido
        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Verificar que se creó correctamente
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P0001',
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);
    }

    /** @test */
    public function puede_modificar_un_pedido()
    {
        // Crear usuario
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear pedido
        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'pendiente',
            'usuario_id' => $usuario->id
        ]);

        // Modificar pedido
        $pedido->update([
            'estado' => 'confirmado',
            'fecha' => Carbon::now()->addDay()
        ]);

        // Verificar modificación
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P0001',
            'estado' => 'confirmado'
        ]);
    }

    /** @test */
    public function puede_eliminar_un_pedido()
    {
        // Crear usuario
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear pedido
        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'pendiente',
            'usuario_id' => $usuario->id
        ]);

        // Eliminar pedido
        $pedido->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('pedidos', [
            'cod' => 'P0001'
        ]);
    }

    /** @test */
    public function puede_encontrar_pedidos_por_estado()
    {
        // Crear usuario
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear varios pedidos con distintos estados
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'pendiente',
            'usuario_id' => $usuario->id
        ]);

        Pedido::create([
            'cod' => 'P0002',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        Pedido::create([
            'cod' => 'P0003',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Buscar pedidos confirmados
        $pedidosConfirmados = Pedido::where('estado', 'confirmado')->get();

        // Verificar que encontramos los 2 pedidos confirmados
        $this->assertEquals(2, $pedidosConfirmados->count());
    }

    /** @test */
    public function puede_encontrar_pedidos_por_fecha()
    {
        // Crear usuario
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $fechaHoy = Carbon::now();
        $fechaAyer = Carbon::now()->subDay();
        $fechaManana = Carbon::now()->addDay();

        // Crear pedidos en diferentes fechas
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => $fechaHoy,
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        Pedido::create([
            'cod' => 'P0002',
            'fecha' => $fechaAyer,
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        Pedido::create([
            'cod' => 'P0003',
            'fecha' => $fechaManana,
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Buscar pedidos de hoy
        $pedidosHoy = Pedido::whereDate('fecha', $fechaHoy)->get();

        // Verificar que encontramos 1 pedido
        $this->assertEquals(1, $pedidosHoy->count());
        $this->assertEquals('P0001', $pedidosHoy->first()->cod);
    }

    /** @test */
    public function puede_encontrar_pedidos_por_usuario()
    {
        // Crear usuarios
        $usuario1 = Usuario::create([
            'email' => 'user1@example.com',
            'nombre' => 'Usuario Uno',
            'password' => bcrypt('password123'),
            'telefono' => '111222333'
        ]);

        $usuario2 = Usuario::create([
            'email' => 'user2@example.com',
            'nombre' => 'Usuario Dos',
            'password' => bcrypt('password123'),
            'telefono' => '444555666'
        ]);

        // Crear pedidos para diferentes usuarios
        Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario1->id
        ]);

        Pedido::create([
            'cod' => 'P0002',
            'fecha' => Carbon::now(),
            'estado' => 'pendiente',
            'usuario_id' => $usuario1->id
        ]);

        Pedido::create([
            'cod' => 'P0003',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario2->id
        ]);

        // Buscar pedidos del usuario 1
        $pedidosUsuario1 = Pedido::where('usuario_id', $usuario1->id)->get();

        // Verificar que encontramos 2 pedidos
        $this->assertEquals(2, $pedidosUsuario1->count());
    }
}