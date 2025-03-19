<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_usuario()
    {
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => Hash::make('password123'),
            'telefono' => '123456789'
        ]);

        $this->assertDatabaseHas('usuarios', [
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test'
        ]);
        
        $this->assertNotNull($usuario->id);
    }

    /** @test */
    public function no_puede_crear_usuarios_con_email_duplicado()
    {
        // Crear el primer usuario
        Usuario::create([
            'email' => 'duplicate@example.com',
            'nombre' => 'Usuario Original',
            'password' => Hash::make('password123'),
            'telefono' => '111222333'
        ]);

        // Intentar crear otro usuario con el mismo email debe fallar
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Usuario::create([
            'email' => 'duplicate@example.com',
            'nombre' => 'Usuario Duplicado',
            'password' => Hash::make('otherpassword'),
            'telefono' => '444555666'
        ]);
    }

    /** @test */
    public function puede_modificar_un_usuario()
    {
        // Crear el usuario primero
        $usuario = Usuario::create([
            'email' => 'update@example.com',
            'nombre' => 'Nombre Original',
            'password' => Hash::make('password123'),
            'telefono' => '111222333'
        ]);

        // Modificar el usuario
        $usuario->update([
            'nombre' => 'Nombre Actualizado',
            'telefono' => '999888777'
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('usuarios', [
            'email' => 'update@example.com',
            'nombre' => 'Nombre Actualizado',
            'telefono' => '999888777'
        ]);
    }

    /** @test */
    public function puede_eliminar_un_usuario()
    {
        // Crear el usuario primero
        $usuario = Usuario::create([
            'email' => 'delete@example.com',
            'nombre' => 'Usuario para Eliminar',
            'password' => Hash::make('password123'),
            'telefono' => '111222333'
        ]);

        $usuarioId = $usuario->id;

        // Eliminar el usuario
        $usuario->delete();

        // Verificar que ya no existe
        $this->assertDatabaseMissing('usuarios', ['id' => $usuarioId]);
    }

    /** @test */
    public function puede_buscar_usuarios_por_email()
    {
        // Crear varios usuarios
        Usuario::create([
            'email' => 'user1@example.com',
            'nombre' => 'Usuario Uno',
            'password' => Hash::make('password123'),
            'telefono' => '111222333'
        ]);

        Usuario::create([
            'email' => 'user2@example.com',
            'nombre' => 'Usuario Dos',
            'password' => Hash::make('password123'),
            'telefono' => '444555666'
        ]);

        // Buscar usuario específico por email
        $usuario = Usuario::where('email', 'user1@example.com')->first();

        // Verificar que encontramos el usuario correcto
        $this->assertNotNull($usuario);
        $this->assertEquals('Usuario Uno', $usuario->nombre);
    }
}