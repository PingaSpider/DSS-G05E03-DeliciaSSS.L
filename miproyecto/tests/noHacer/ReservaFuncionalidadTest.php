<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mesa;
use App\Models\Reserva;
use App\Models\Usuario;
use Carbon\Carbon;

class ReservaFuncionalidadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_filtrar_reservas_con_scope_en_fecha_y_hora()
    {
        // Crear mesa y usuario
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear varias reservas en distintas fechas y horas
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '21:00:00',
            'codReserva' => 8002,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        Reserva::create([
            'fecha' => '2025-03-02',
            'hora' => '20:00:00',
            'codReserva' => 8003,
            'cantPersona' => 4,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Usar el scope para filtrar
        $reservas = Reserva::enFechaYHora('2025-03-01', '20:00:00')->get();

        // Verificar que solo encontramos una reserva con esa fecha y hora
        $this->assertEquals(1, $reservas->count());
        $this->assertEquals(8001, $reservas->first()->codReserva);
    }

    /** @test */
    public function crear_reserva_marca_mesa_como_ocupada()
    {
        // Crear mesa y usuario
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false, // Inicialmente disponible
            'cantidadMesa' => 4
        ]);

        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Verificar que la mesa está inicialmente disponible
        $this->assertFalse($mesa->ocupada);

        // Crear una reserva para esta mesa
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Recargar la mesa desde la base de datos
        $mesa->refresh();

        // Verificar que la mesa ahora está marcada como ocupada
        $this->assertTrue($mesa->ocupada);
    }

    /** @test */
    public function eliminar_reserva_marca_mesa_como_disponible_si_no_hay_mas_reservas_futuras()
    {
        // Crear mesa y usuario
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear una reserva para esta mesa
        $reserva = Reserva::create([
            'fecha' => Carbon::now()->addDays(1)->format('Y-m-d'), // Fecha futura
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Recargar la mesa desde la base de datos
        $mesa->refresh();
        // Verificar que la mesa está ocupada
        $this->assertTrue($mesa->ocupada);

        // Eliminar la reserva
        $reserva->delete();

        // Recargar la mesa desde la base de datos
        $mesa->refresh();
        // Verificar que la mesa ahora está disponible
        $this->assertFalse($mesa->ocupada);
    }

    /** @test */
    public function eliminar_reserva_no_marca_mesa_como_disponible_si_hay_mas_reservas_futuras()
    {
        // Crear mesa y usuario
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear dos reservas futuras para esta mesa
        $reserva1 = Reserva::create([
            'fecha' => Carbon::now()->addDays(1)->format('Y-m-d'), // Mañana
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        $reserva2 = Reserva::create([
            'fecha' => Carbon::now()->addDays(2)->format('Y-m-d'), // Pasado mañana
            'hora' => '21:00:00',
            'codReserva' => 8002,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Recargar la mesa desde la base de datos
        $mesa->refresh();
        // Verificar que la mesa está ocupada
        $this->assertTrue($mesa->ocupada);

        // Eliminar solo la primera reserva
        $reserva1->delete();

        // Recargar la mesa desde la base de datos
        $mesa->refresh();
        // Verificar que la mesa sigue ocupada porque aún hay una reserva futura
        $this->assertTrue($mesa->ocupada);
    }
}