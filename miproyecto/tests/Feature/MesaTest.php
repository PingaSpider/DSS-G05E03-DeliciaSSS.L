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
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        $this->assertDatabaseHas('mesas', [
            'codMesa' => 'M001',
            'cantidadMesa' => 4
        ]);
    }

    /** @test */
    public function puede_modificar_una_mesa()
    {
        // Crear la mesa primero
        $mesa = Mesa::create([
            'codMesa' => 'M002',
            'ocupada' => false,
            'cantidadMesa' => 2
        ]);

        // Modificar la mesa
        $mesa->update([
            'cantidadMesa' => 6,
            'ocupada' => true
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('mesas', [
            'codMesa' => 'M002',
            'cantidadMesa' => 6,
            'ocupada' => true
        ]);
    }

    /** @test */
    public function puede_eliminar_una_mesa()
    {
        // Crear la mesa primero
        $mesa = Mesa::create([
            'codMesa' => 'M003',
            'ocupada' => false,
            'cantidadMesa' => 8
        ]);

        // Eliminar la mesa
        $mesa->delete();

        // Verificar que ya no existe
        $this->assertDatabaseMissing('mesas', ['codMesa' => 'M003']);
    }

    /** @test */
    public function puede_buscar_mesas_por_capacidad()
    {
        // Crear varias mesas con diferentes capacidades
        Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => false,
            'cantidadMesa' => 2
        ]);

        Mesa::create([
            'codMesa' => 'M002',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        Mesa::create([
            'codMesa' => 'M003',
            'ocupada' => false,
            'cantidadMesa' => 6
        ]);

        // Buscar mesas con capacidad >= 4
        $mesasGrandes = Mesa::where('cantidadMesa', '>=', 4)->get();

        // Verificar que encontramos 2 mesas (la de 4 y la de 6)
        $this->assertEquals(2, $mesasGrandes->count());
        $this->assertTrue($mesasGrandes->contains('cantidadMesa', 4));
        $this->assertTrue($mesasGrandes->contains('cantidadMesa', 6));
    }

    /** @test */
    public function puede_filtrar_mesas_por_ocupacion()
    {
        // Crear varias mesas con diferentes estados
        Mesa::create([
            'codMesa' => 'M001',
            'ocupada' => true,
            'cantidadMesa' => 2
        ]);

        Mesa::create([
            'codMesa' => 'M002',
            'ocupada' => false,
            'cantidadMesa' => 4
        ]);

        Mesa::create([
            'codMesa' => 'M003',
            'ocupada' => false,
            'cantidadMesa' => 6
        ]);

        // Buscar mesas disponibles (no ocupadas)
        $mesasDisponibles = Mesa::where('ocupada', false)->get();

        // Verificar que encontramos 2 mesas disponibles
        $this->assertEquals(2, $mesasDisponibles->count());
    }
}