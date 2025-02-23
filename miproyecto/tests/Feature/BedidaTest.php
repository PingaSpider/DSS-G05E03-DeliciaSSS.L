<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Bebida;

class BebidaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_bebida()
    {
        Bebida::create([
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Fanta'
        ]);

        $this->assertDatabaseHas('bebidas', ['tipoBebida' => 'Fanta']);
    }
}
