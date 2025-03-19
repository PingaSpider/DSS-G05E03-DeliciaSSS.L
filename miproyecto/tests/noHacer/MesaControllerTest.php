<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Mesa;

class MesaControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test para verificar la creación directa de una mesa usando el modelo
     */
    public function test_mesa_model_creation()
    {
        // Crear una mesa directamente usando el modelo
        $mesa = Mesa::create([
            'codMesa' => 'M001',
            'cantidadMesa' => 4,
            'ocupada' => false
        ]);

        // Verificar que la mesa se creó en la base de datos
        $this->assertDatabaseHas('mesas', [
            'codMesa' => 'M001',
            'cantidadMesa' => 4,
            'ocupada' => 0  // En la base de datos, false se almacena como 0
        ]);

        // Verificar que el objeto se creó correctamente
        $this->assertEquals('M001', $mesa->codMesa);
        $this->assertEquals(4, $mesa->cantidadMesa);
        $this->assertEquals(false, $mesa->ocupada);
    }

    /**
     * Test para verificar la actualización directa de una mesa usando el modelo
     */
    public function test_mesa_model_update()
    {
        // Crear una mesa
        $mesa = Mesa::create([
            'codMesa' => 'M002',
            'cantidadMesa' => 2,
            'ocupada' => false
        ]);

        // Actualizar la mesa
        $mesa->cantidadMesa = 6;
        $mesa->ocupada = true;
        $mesa->save();

        // Verificar que los cambios se guardaron en la base de datos
        $this->assertDatabaseHas('mesas', [
            'codMesa' => 'M002',
            'cantidadMesa' => 6,
            'ocupada' => 1  // En la base de datos, true se almacena como 1
        ]);

        // Verificar que el objeto se actualizó correctamente
        $mesaActualizada = Mesa::find('M002');
        $this->assertEquals(6, $mesaActualizada->cantidadMesa);
        $this->assertEquals(true, $mesaActualizada->ocupada);
    }

    /**
     * Test para verificar la eliminación directa de una mesa usando el modelo
     */
    public function test_mesa_model_delete()
    {
        // Crear una mesa
        $mesa = Mesa::create([
            'codMesa' => 'M003',
            'cantidadMesa' => 3,
            'ocupada' => true
        ]);

        // Eliminar la mesa
        $mesa->delete();

        // Verificar que la mesa se eliminó de la base de datos
        $this->assertDatabaseMissing('mesas', [
            'codMesa' => 'M003'
        ]);

        // Verificar que el objeto no se puede encontrar
        $this->assertNull(Mesa::find('M003'));
    }

    /**
     * Test para verificar múltiples operaciones CRUD en mesas
     */
    public function test_mesa_crud_operations()
    {
        // Crear varias mesas
        Mesa::create([
            'codMesa' => 'M004',
            'cantidadMesa' => 2,
            'ocupada' => false
        ]);

        Mesa::create([
            'codMesa' => 'M005',
            'cantidadMesa' => 4,
            'ocupada' => true
        ]);

        Mesa::create([
            'codMesa' => 'M006',
            'cantidadMesa' => 6,
            'ocupada' => false
        ]);

        // Contar mesas
        $this->assertEquals(3, Mesa::count());

        // Actualizar una mesa
        $mesa = Mesa::find('M004');
        $mesa->cantidadMesa = 8;
        $mesa->save();

        // Verificar actualización
        $this->assertEquals(8, Mesa::find('M004')->cantidadMesa);

        // Eliminar una mesa
        Mesa::find('M005')->delete();

        // Verificar eliminación
        $this->assertEquals(2, Mesa::count());
        $this->assertNull(Mesa::find('M005'));

        // Verificar mesas restantes
        $this->assertNotNull(Mesa::find('M004'));
        $this->assertNotNull(Mesa::find('M006'));
    }
}