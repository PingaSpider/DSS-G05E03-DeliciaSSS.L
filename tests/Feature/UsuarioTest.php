<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_crear_un_usuario()
    {
        Usuario::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => '123456',
            'telefono' => '123456789'
        ]);

        $this->assertDatabaseHas('usuarios', ['email' => 'juan@example.com']);
    }
}


