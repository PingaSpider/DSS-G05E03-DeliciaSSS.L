<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Comida;
use App\Models\Producto;

class ComidaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_comida()
    {
        //Crear producto primero para poder crear la comida
        Producto::create([
            'cod' => 'C001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);


        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Pasta Carbonara'
        ]);

        $this->assertDatabaseHas('comidas', ['descripcion' => 'Pasta Carbonara']);
    }

    /** @test */
    public function puede_modificar_una_comida()
    {
        //Crear producto primero para poder crear la comida
        Producto::create([
            'cod' => 'C001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);
        // Primero crear la comida
        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Pasta Carbonara'
        ]);

        // Luego actualizar
        $comida = Comida::where('cod', 'C001')->first();
        $comida->update([
            'descripcion' => 'Pasta Bolognesa'
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('comidas', [
            'cod' => 'C001',
            'descripcion' => 'Pasta Bolognesa'
        ]);
    }

    /** @test */
    public function puede_eliminar_una_comida()
    {
        //Crear producto primero para poder crear la comida
        Producto::create([
            'cod' => 'C001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);
        // Primero crear la comida
        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Pasta Carbonara'
        ]);

        // Luego eliminar
        $comida = Comida::where('cod', 'C001')->first();
        $comida->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('comidas', ['cod' => 'C001']);
    }
}