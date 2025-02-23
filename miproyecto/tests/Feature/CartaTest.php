<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Carta;

class CartaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_carta()
    {
        Carta::create([
            'cod' => 'C002',
            'precio' => 15.99,
            'nombre' => 'Pasta '
        ]);

        $this->assertDatabaseHas('cartas', ['cod' => 'C002']);
    }
}
