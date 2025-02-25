<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Comida;

class ComidaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_comida()
    {
        Comida::create([
            'descripcion' => 'Arroz'
        ]);

        $this->assertDatabaseHas('comidas', ['descripcion' => 'Arroz']);
    }
}
