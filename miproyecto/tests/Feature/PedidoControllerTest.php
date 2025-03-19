<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Usuario;

class PedidoControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Preparar el entorno de prueba
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un usuario para asociar con los pedidos
        Usuario::create([
            'id' => 1,
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123')
        ]);
        
        // Crear un segundo usuario para pruebas
        Usuario::create([
            'id' => 2,
            'email' => 'test2@example.com',
            'nombre' => 'Usuario Test 2',
            'password' => bcrypt('password123')
        ]);
    }

    /**
     * Test para verificar la creación directa de un pedido usando el modelo
     */
    public function test_pedido_model_creation()
    {
        // Crear un pedido directamente usando el modelo
        $pedido = Pedido::create([
            'cod' => 'P001',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        // Verificar que el pedido se creó en la base de datos
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P001',
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        // Verificar que el objeto se creó correctamente
        $this->assertEquals('P001', $pedido->cod);
        $this->assertEquals('Pendiente', $pedido->estado);
        $this->assertEquals(1, $pedido->usuario_id);
    }

    /**
     * Test para verificar la actualización directa de un pedido usando el modelo
     */
    public function test_pedido_model_update()
    {
        // Crear un pedido
        $pedido = Pedido::create([
            'cod' => 'P002',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 2
        ]);

        // Actualizar el pedido
        $pedido->estado = 'Completado';
        $pedido->save();

        // Verificar que los cambios se guardaron en la base de datos
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P002',
            'fecha' => now()->toDateString(),
            'estado' => 'Completado',
            'usuario_id' => 2
        ]);

        // Verificar que el objeto se actualizó correctamente
        $pedidoActualizado = Pedido::find('P002');
        $this->assertEquals('Completado', $pedidoActualizado->estado);
    }

    /**
     * Test para verificar la eliminación directa de un pedido usando el modelo
     */
    public function test_pedido_model_delete()
    {
        // Crear un pedido
        $pedido = Pedido::create([
            'cod' => 'P003',
            'fecha' => now()->toDateString(),
            'estado' => 'Cancelado',
            'usuario_id' => 1
        ]);

        // Eliminar el pedido
        $pedido->delete();

        // Verificar que el pedido se eliminó de la base de datos
        $this->assertDatabaseMissing('pedidos', [
            'cod' => 'P003'
        ]);

        // Verificar que el objeto no se puede encontrar
        $this->assertNull(Pedido::find('P003'));
    }

    /**
     * Test para verificar múltiples operaciones CRUD en pedidos
     */
    public function test_pedido_crud_operations()
    {
        // Crear varios pedidos
        Pedido::create([
            'cod' => 'P004',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        Pedido::create([
            'cod' => 'P005',
            'fecha' => now()->toDateString(),
            'estado' => 'En proceso',
            'usuario_id' => 1
        ]);

        Pedido::create([
            'cod' => 'P006',
            'fecha' => now()->toDateString(),
            'estado' => 'Completado',
            'usuario_id' => 1
        ]);

        // Contar pedidos
        $this->assertEquals(3, Pedido::count());

        // Actualizar un pedido
        $pedido = Pedido::find('P004');
        $pedido->estado = 'En proceso';
        $pedido->save();

        // Verificar actualización
        $this->assertEquals('En proceso', Pedido::find('P004')->estado);

        // Eliminar un pedido
        Pedido::find('P005')->delete();

        // Verificar eliminación
        $this->assertEquals(2, Pedido::count());
        $this->assertNull(Pedido::find('P005'));

        // Verificar pedidos restantes
        $this->assertNotNull(Pedido::find('P004'));
        $this->assertNotNull(Pedido::find('P006'));
    }

    /**
     * Test para verificar la relación entre pedido y usuario
     */
    public function test_pedido_usuario_relationship()
    {
        // Crear un pedido asociado al usuario
        $pedido = Pedido::create([
            'cod' => 'P007',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);

        // Verificar la relación
        $usuario = $pedido->usuario;
        $this->assertNotNull($usuario);
        $this->assertEquals('Usuario Test', $usuario->nombre);
        $this->assertEquals('test@example.com', $usuario->email);
    }
    
    /**
     * Test para verificar la creación de un pedido a través del controlador
     */
    public function test_controller_store_pedido()
    {
        $pedidoData = [
            'cod' => 'P101',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ];
        
        $response = $this->post(route('pedidos.store'), $pedidoData);
        
        // Verificar redirección exitosa
        $response->assertRedirect(route('pedidos.index'));
        
        // Verificar que se creó el pedido
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P101',
            'usuario_id' => 1
        ]);
    }
    
    /**
     * Test para verificar la validación de usuario existente al crear un pedido
     */
    public function test_controller_validates_user_exists_on_create()
    {
        $pedidoData = [
            'cod' => 'P102',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 999 // ID que no existe
        ];
        
        $response = $this->post(route('pedidos.store'), $pedidoData);
        
        // Verificar que hay errores de validación
        $response->assertSessionHasErrors('usuario_id');
        
        // Verificar que no se creó el pedido
        $this->assertDatabaseMissing('pedidos', ['cod' => 'P102']);
    }
    
    /**
     * Test para verificar la actualización de un pedido a través del controlador
     */
    public function test_controller_update_pedido()
    {
        // Crear un pedido
        $pedido = Pedido::create([
            'cod' => 'P103',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);
        
        // Datos para actualizar
        $updateData = [
            'fecha' => now()->addDay()->toDateString(),
            'estado' => 'Completado',
            // No incluimos usuario_id intencionalmente
        ];
        
        $response = $this->put(route('pedidos.update', $pedido->cod), $updateData);
        
        // Verificar redirección exitosa
        $response->assertRedirect(route('pedidos.index'));
        
        // Verificar que se actualizó el pedido
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P103',
            'estado' => 'Completado',
            'usuario_id' => 1 // El usuario_id debe seguir siendo el mismo
        ]);
    }
    
    /**
     * Test para verificar que no se puede cambiar el usuario de un pedido al actualizarlo
     */
    public function test_controller_cannot_change_usuario_id_on_update()
    {
        // Crear un pedido
        $pedido = Pedido::create([
            'cod' => 'P104',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);
        
        // Datos para actualizar intentando cambiar el usuario
        $updateData = [
            'fecha' => now()->toDateString(),
            'estado' => 'Completado',
            'usuario_id' => 2 // Intentamos cambiar al usuario 2
        ];
        
        $response = $this->put(route('pedidos.update', $pedido->cod), $updateData);
        
        // Verificar redirección exitosa (el controlador ignora el usuario_id)
        $response->assertRedirect(route('pedidos.index'));
        
        // Verificar que el usuario_id NO cambió
        $this->assertDatabaseHas('pedidos', [
            'cod' => 'P104',
            'estado' => 'Completado',
            'usuario_id' => 1 // Sigue siendo el usuario original
        ]);
        
        // Verificar que NO está con el usuario 2
        $this->assertDatabaseMissing('pedidos', [
            'cod' => 'P104',
            'usuario_id' => 2
        ]);
    }
    
    /**
     * Test para verificar la eliminación de un pedido a través del controlador
     */
    public function test_controller_destroy_pedido()
    {
        // Crear un pedido
        $pedido = Pedido::create([
            'cod' => 'P105',
            'fecha' => now()->toDateString(),
            'estado' => 'Pendiente',
            'usuario_id' => 1
        ]);
        
        $response = $this->delete(route('pedidos.destroy', $pedido->cod));
        
        // Verificar redirección exitosa
        $response->assertRedirect(route('pedidos.index'));
        
        // Verificar que el pedido se eliminó
        $this->assertDatabaseMissing('pedidos', ['cod' => 'P105']);
    }
}