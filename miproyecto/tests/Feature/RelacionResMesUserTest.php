<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mesa;
use App\Models\Reserva;
use App\Models\Usuario;

class RelacionResMesUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function una_mesa_a_una_hora_solo_puede_estar_asignada_a_una_reserva()
    {
        // Crear mesa y usuarios
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

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

        // Crear primera reserva
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario1->id,
            'usuario_email' => $usuario1->email
        ]);

        // Intentar crear otra reserva para la misma mesa, fecha y hora debe fallar
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 'R002',
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario2->id,
            'usuario_email' => $usuario2->email
        ]);
    }

    /** @test */
    public function una_reserva_solo_esta_asignada_a_un_usuario()
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

        // Crear reserva
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

        // Verificar la relación
        $this->assertEquals($usuario->id, $reserva->usuario_id);
        
        // Si hay relaciones definidas en los modelos, podemos usar:
        if (method_exists($reserva, 'usuario')) {
            $reservaUsuario = $reserva->usuario;
            $this->assertNotNull($reservaUsuario);
            $this->assertEquals($usuario->email, $reservaUsuario->email);
        }
    }

    /** @test */
    public function un_usuario_puede_tener_muchas_reservas()
    {
        // Crear mesas
        $mesa1 = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        $mesa2 = Mesa::create([
            'codMesa' => 'M002',
            'ocupada' => false,
            'cantidadMesa' => 2
        ]);

        // Crear usuario
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear varias reservas para el mismo usuario
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '20:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa1->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        Reserva::create([
            'fecha' => '2025-03-02',
            'hora' => '21:00:00',
            'codReserva' => 8002,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa2->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Verificar que el usuario tiene 2 reservas
        $reservasUsuario = Reserva::where('usuario_id', $usuario->id)->get();
        $this->assertEquals(2, $reservasUsuario->count());
        
        // Si hay relaciones definidas en los modelos, podemos usar:
        if (method_exists($usuario, 'reservas')) {
            $this->assertEquals(2, $usuario->reservas->count());
        }
    }

    /** @test */
    public function una_mesa_puede_tener_diferentes_reservas_a_diferentes_horas()
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

        // Crear reservas para la misma mesa en diferentes horarios
        Reserva::create([
            'fecha' => '2025-03-01',
            'hora' => '14:00:00',
            'codReserva' => 8001,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

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

        Reserva::create([
            'fecha' => '2025-03-02',
            'hora' => '14:00:00',
            'codReserva' => 8003,
            'cantPersona' => 4,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Verificar que la mesa tiene 3 reservas en total
        $reservasMesa = Reserva::where('mesa_id', $mesa->codMesa)->get();
        $this->assertEquals(3, $reservasMesa->count());
        
        // Si hay relaciones definidas en los modelos, podemos usar:
        if (method_exists($mesa, 'reservas')) {
            $this->assertEquals(3, $mesa->reservas->count());
        }
    }

    /** @test */
    public function una_mesa_no_puede_tener_reservas_superpuestas()
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

        // Crear primera reserva
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

        // Las siguientes reservas para la misma fecha y hora deben fallar
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

    /** @test */
    public function eliminar_usuario_debe_eliminar_sus_reservas()
    {
        // Crear mesa y usuario
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        $usuario = Usuario::create([
            'email' => 'delete@example.com',
            'nombre' => 'Usuario para Eliminar',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        // Crear reservas para el usuario
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
            'fecha' => '2025-03-02',
            'hora' => '21:00:00',
            'codReserva' => 8002,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => $mesa->codMesa,
            'usuario_id' => $usuario->id,
            'usuario_email' => $usuario->email
        ]);

        // Verificar que existen 2 reservas
        $this->assertEquals(2, Reserva::where('usuario_id', $usuario->id)->count());

        // Eliminar el usuario
        $usuario->delete();

        // Verificar que las reservas también se han eliminado
        $this->assertEquals(0, Reserva::where('usuario_id', $usuario->id)->count());
    }
}