<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mesa;

class MesaTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function puede_crear_una_mesa()
    {
        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M001',
            'ocupada' => false
        ]);

        $this->assertDatabaseHas('mesas', ['codMesa' => 'M001']);
    }
}
