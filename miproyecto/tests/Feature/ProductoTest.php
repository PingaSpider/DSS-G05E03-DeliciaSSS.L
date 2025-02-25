<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_producto()
    {
        Producto::create([
            'cod' => 'C002',
            'pvp' => 15.99,
            'nombre' => 'Pasta ',
            'stock' => 5,
            'precioCompra' => 10,
            'disponible' => true
        ]);

        $this->assertDatabaseHas('productos', ['cod' => 'C002']);
    }
}
