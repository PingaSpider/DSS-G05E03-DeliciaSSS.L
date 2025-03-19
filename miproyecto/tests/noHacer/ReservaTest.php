<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Reserva;
use App\Models\Mesa;
use App\Models\Usuario;
use Carbon\Carbon;

class ReservaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_reserva()
    {
        // Crear mesa y usuario necesarios para la reserva
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

        // Crear la reserva
        $reserva = Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => 0,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        $this->assertDatabaseHas('reservas', [
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => 0
        ]);
        
        $this->assertNotNull($reserva->id);
    }

    /** @test */
    public function puede_modificar_una_reserva()
    {
        // Crear mesa y usuario necesarios para la reserva
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

        // Crear la reserva
        $reserva = Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8002,
            'cantPersona' => 3,
            'reservaConfirmada' => false,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Modificar la reserva
        $reserva->update([
            'fecha' => '2025-03-02',
            'hora' => '21:00:00',
            'cantPersona' => 4,
            'reservaConfirmada' => 0
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('reservas', [
            'codReserva' => 8002,
            'fecha' => '2025-03-02',
            'hora' => '21:00:00',
            'cantPersona' => 4,
            'reservaConfirmada' => 0
        ]);
    }

    /** @test */
    public function puede_eliminar_una_reserva()
    {
        // Crear mesa y usuario necesarios para la reserva
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

        // Crear la reserva
        $reserva = Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        $reservaId = $reserva->id;

        // Eliminar la reserva
        $reserva->delete();

        // Verificar que ya no existe
        $this->assertDatabaseMissing('reservas', ['id' => $reservaId]);
    }

    /** @test */
    public function puede_buscar_reservas_por_fecha()
    {
        // Crear mesa y usuario necesarios para las reservas
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

        // Crear varias reservas con diferentes fechas
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

        // Buscar reservas para el 1 de marzo de 2025
        $reservas = Reserva::where('fecha', '2025-03-01')->get();

        // Verificar que encontramos 2 reservas para esa fecha
        $this->assertEquals(2, $reservas->count());
    }

    /** @test */
    public function no_puede_crear_reservas_duplicadas_para_misma_mesa_fecha_y_hora()
    {
        // Crear mesa y usuario necesarios para la reserva
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

        // Crear la primera reserva
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

        // Intentar crear otra reserva para la misma mesa, fecha y hora debe fallar
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8002,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);
    }
}