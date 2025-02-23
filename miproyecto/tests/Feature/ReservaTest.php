<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Mesa;
use App\Models\Reserva;

class ReservaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_reserva()
    {
        $usuario = Usuario::create([
            'nombre' => 'Carlos López',
            'email' => 'carlos@gmail.com',
            'password' => 'dss',
            'telefono' => '987654321'
        ]);

        $mesa = Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M002',
            'ocupada' => false
        ]);

        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1002,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->id,
            'usuario_id' => $usuario->id
        ]);

        $this->assertDatabaseHas('reservas', ['codReserva' => 1002]);
    }
}
