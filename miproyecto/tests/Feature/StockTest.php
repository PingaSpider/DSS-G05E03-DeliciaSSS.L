<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Carta;
use App\Models\Stock;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_stock()
    {
        $carta = Carta::create([
            'cod' => 'C003',
            'precio' => 10.99,
            'nombre' => 'Agua'
        ]);

        Stock::create([
            'cantidad' => 20,
            'disponible' => true,
            'precio' => 10.99,
            'carta_id' => $carta->id
        ]);

        $this->assertDatabaseHas('stocks', ['cantidad' => 20]);
    }
}
