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
            'cod' => 'P001',
            'pvp' => 15.99,
            'nombre' => 'Producto Test',
            'stock' => 5,
            'precioCompra' => 10,
            'disponible' => true
        ]);

        $this->assertDatabaseHas('productos', ['nombre' => 'Producto Test']);
    }

    /** @test */
    public function puede_crear_productos_con_formato_correcto()
    {
        // Producto tipo comida
        Producto::create([
            'cod' => 'C001',
            'pvp' => 12.50,
            'nombre' => 'Ensalada César',
            'stock' => 10,
            'precioCompra' => 8,
            'disponible' => true
        ]);

        // Producto tipo bebida
        Producto::create([
            'cod' => 'B001',
            'pvp' => 3.50,
            'nombre' => 'Refresco Cola',
            'stock' => 20,
            'precioCompra' => 2,
            'disponible' => true
        ]);

        // Producto tipo menú
        Producto::create([
            'cod' => 'M001',
            'pvp' => 18.95,
            'nombre' => 'Menú Ejecutivo',
            'stock' => 15,
            'precioCompra' => 12,
            'disponible' => true
        ]);

        $this->assertDatabaseHas('productos', ['cod' => 'C001']);
        $this->assertDatabaseHas('productos', ['cod' => 'B001']);
        $this->assertDatabaseHas('productos', ['cod' => 'M001']);
    }

    /** @test */
    public function puede_modificar_un_producto()
    {
        // Primero crear el producto
        Producto::create([
            'cod' => 'P001',
            'pvp' => 15.99,
            'nombre' => 'Producto Original',
            'stock' => 5,
            'precioCompra' => 10,
            'disponible' => true
        ]);

        // Luego actualizar
        $producto = Producto::where('cod', 'P001')->first();
        $producto->update([
            'pvp' => 18.50,
            'nombre' => 'Producto Modificado',
            'stock' => 10,
            'disponible' => 1
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('productos', [
            'cod' => 'P001',
            'pvp' => 18.50,
            'nombre' => 'Producto Modificado',
            'stock' => 10,
            'disponible' => 1
        ]);
    }

    /** @test */
    public function puede_eliminar_un_producto()
    {
        // Primero crear el producto
        Producto::create([
            'cod' => 'P001',
            'pvp' => 15.99,
            'nombre' => 'Producto a Eliminar',
            'stock' => 5,
            'precioCompra' => 10,
            'disponible' => true
        ]);

        // Luego eliminar
        $producto = Producto::where('cod', 'P001')->first();
        $producto->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('productos', ['cod' => 'P001']);
    }
}