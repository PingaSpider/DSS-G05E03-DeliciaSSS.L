<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Bebida;
use App\Models\Producto;

class BebidaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_bebida()
    {
        //Crear priemro el producto
        Producto::create([
            'cod' => 'B001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        Bebida::create([
            'cod' => 'B001',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Fanta'
        ]);

        $this->assertDatabaseHas('bebidas', ['tipoBebida' => 'Fanta']);
    }

    /** @test */
    public function puede_modificar_una_bebida()
    {
        //Crear priemro el producto
        Producto::create([
            'cod' => 'B001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        // Primero crear la bebida
        Bebida::create([
            'cod' => 'B001',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Fanta'
        ]);

        // Luego actualizar
        $bebida = Bebida::where('cod', 'B001')->first();
        $bebida->update([
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Coca-Cola'
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('bebidas', [
            'cod' => 'B001',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Coca-Cola'
        ]);
    }

    /** @test */
    public function puede_eliminar_una_bebida()
    {
        //Crear priemro el producto
        Producto::create([
            'cod' => 'B001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        // Primero crear la bebida
        Bebida::create([
            'cod' => 'B001',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Fanta'
        ]);

        // Luego eliminar
        $bebida = Bebida::where('cod', 'B001')->first();
        $bebida->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('bebidas', ['cod' => 'B001']);
    }
}