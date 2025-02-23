<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Pedido;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_pedido()
    {
        $usuario = Usuario::create([
            'nombre' => 'Ana Martínez',
            'email' => 'ana@hotmail.com',
            'password' => 'admin',
            'telefono' => '672966125'
        ]);

        Pedido::create([
            'cod' => 5002,
            'fecha' => now(),
            'estado' => 'Pendiente',
            'usuario_id' => $usuario->id
        ]);

        $this->assertDatabaseHas('pedidos', ['cod' => 5002]);
    }
}
