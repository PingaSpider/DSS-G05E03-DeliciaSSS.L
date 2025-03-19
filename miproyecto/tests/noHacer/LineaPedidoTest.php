<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\LineaPedido;
use App\Models\Usuario;
use App\Models\Producto;
use Carbon\Carbon;

class LineaPedidoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_linea_de_pedido()
    {
        // Crear usuario, producto y pedido necesarios
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $producto = Producto::create([
            'cod' => 'P001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Crear línea de pedido
        $lineaPedido = LineaPedido::create([
            'linea' => 'L0001',
            'cantidad' => 2,
            'precio' => $producto->pvp,
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto->cod
        ]);

        // Verificar que se creó correctamente
        $this->assertDatabaseHas('linea_pedidos', [
            'linea' => 'L0001',
            'cantidad' => 2,
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto->cod
        ]);
    }

    /** @test */
    public function puede_modificar_una_linea_de_pedido()
    {
        // Crear usuario, producto y pedido necesarios
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $producto = Producto::create([
            'cod' => 'P001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Crear línea de pedido
        $lineaPedido = LineaPedido::create([
            'linea' => 'L0001',
            'cantidad' => 2,
            'precio' => $producto->pvp,
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto->cod
        ]);

        // Modificar línea de pedido
        $lineaPedido->update([
            'cantidad' => 5,
            'precio' => 9.99 // Precio con descuento
        ]);

        // Verificar modificación
        $this->assertDatabaseHas('linea_pedidos', [
            'linea' => 'L0001',
            'cantidad' => 5,
            'precio' => 9.99
        ]);
    }

    /** @test */
    public function puede_eliminar_una_linea_de_pedido()
    {
        // Crear usuario, producto y pedido necesarios
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $producto = Producto::create([
            'cod' => 'P001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Crear línea de pedido
        $lineaPedido = LineaPedido::create([
            'linea' => 'L0001',
            'cantidad' => 2,
            'precio' => $producto->pvp,
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto->cod
        ]);

        // Eliminar línea de pedido
        $lineaPedido->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('linea_pedidos', [
            'linea' => 'L0001'
        ]);
    }

    /** @test */
    public function puede_calcular_subtotal_de_linea()
    {
        // Crear usuario, producto y pedido necesarios
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $producto = Producto::create([
            'cod' => 'P001',
            'pvp' => 10.00, // Precio exacto para facilitar cálculos
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Crear línea de pedido
        $lineaPedido = LineaPedido::create([
            'linea' => 'L0001',
            'cantidad' => 3,
            'precio' => $producto->pvp, // 10.00
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto->cod
        ]);

        // Verificar el cálculo del subtotal (3 * 10.00 = 30.00)
        $this->assertEquals(30.00, $lineaPedido->subtotal);
    }

    /** @test */
    public function puede_recuperar_lineas_de_un_pedido()
    {
        // Crear usuario, productos y pedido necesarios
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $producto1 = Producto::create([
            'cod' => 'P001',
            'pvp' => 10.99,
            'nombre' => 'Producto 1',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        $producto2 = Producto::create([
            'cod' => 'P002',
            'pvp' => 15.99,
            'nombre' => 'Producto 2',
            'stock' => 10,
            'disponible' => true,
            'precioCompra' => 8
        ]);

        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Crear varias líneas para el mismo pedido
        LineaPedido::create([
            'linea' => 'L0001',
            'cantidad' => 2,
            'precio' => $producto1->pvp,
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto1->cod
        ]);

        LineaPedido::create([
            'linea' => 'L0002',
            'cantidad' => 1,
            'precio' => $producto2->pvp,
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto2->cod
        ]);

        // Recuperar todas las líneas del pedido
        $lineas = $pedido->lineasPedido;

        // Verificar que recuperamos las 2 líneas
        $this->assertEquals(2, $lineas->count());
    }

    /** @test */
    public function puede_relacionar_linea_con_producto()
    {
        // Crear usuario, producto y pedido necesarios
        $usuario = Usuario::create([
            'email' => 'test@example.com',
            'nombre' => 'Usuario Test',
            'password' => bcrypt('password123'),
            'telefono' => '123456789'
        ]);

        $producto = Producto::create([
            'cod' => 'P001',
            'pvp' => 10.99,
            'nombre' => 'Producto Especial',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        $pedido = Pedido::create([
            'cod' => 'P0001',
            'fecha' => Carbon::now(),
            'estado' => 'confirmado',
            'usuario_id' => $usuario->id
        ]);

        // Crear línea de pedido
        $lineaPedido = LineaPedido::create([
            'linea' => 'L0001',
            'cantidad' => 2,
            'precio' => $producto->pvp,
            'estado' => 'confirmado',
            'pedido_id' => $pedido->cod,
            'producto_id' => $producto->cod
        ]);

        // Verificar la relación con el producto
        $this->assertEquals('Producto Especial', $lineaPedido->producto->nombre);
    }
}