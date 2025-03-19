<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Producto;
use App\Models\Bebida;
use App\Models\Comida;
use App\Models\Menu;

class ControllerInheritanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test para verificar el comportamiento de herencia a través del método store de Producto
     */
    public function test_producto_store_behavior()
    {
        $productoData = [
            'cod' => 'P100',
            'nombre' => 'Producto Test Controller',
            'pvp' => 15.99,
            'stock' => 25,
            'precioCompra' => 10.99
        ];

        $response = $this->post(route('productos.store'), $productoData);

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Producto creado exitosamente');
        
        $this->assertDatabaseHas('productos', [
            'cod' => 'P100',
            'nombre' => 'Producto Test Controller'
        ]);
    }

    /**
     * Test para verificar el comportamiento de herencia en BebidaController
     */
    public function test_bebida_store_behavior()
    {
        $bebidaData = [
            'cod' => 'B100',
            'tipoBebida' => 'Refresco',
            'tamanyo' => 'Grande',
            'pvp' => 3.99,
            'stock' => 50,
            'precioCompra' => 2.49
        ];

        $response = $this->post(route('bebidas.store'), $bebidaData);

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Bebida creada exitosamente');
        
        // Verificar que se creó la bebida
        $this->assertDatabaseHas('bebidas', [
            'cod' => 'B100',
            'tipoBebida' => 'Refresco',
            'tamanyo' => 'Grande'
        ]);

        // Verificar que también se creó el producto base - esto confirma la herencia
        $this->assertDatabaseHas('productos', [
            'cod' => 'B100',
            'nombre' => 'Refresco (Grande)'
        ]);
    }

    /**
     * Test para verificar el comportamiento de herencia en ComidaController
     */
    public function test_comida_store_behavior()
    {
        $comidaData = [
            'cod' => 'C100',
            'descripcion' => 'Hamburguesa Especial',
            'pvp' => 12.99,
            'stock' => 15,
            'precioCompra' => 8.50
        ];

        $response = $this->post(route('comidas.store'), $comidaData);

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Comida creada exitosamente');
        
        // Verificar que se creó la comida
        $this->assertDatabaseHas('comidas', [
            'cod' => 'C100',
            'descripcion' => 'Hamburguesa Especial'
        ]);

        // Verificar que también se creó el producto base - esto confirma la herencia
        $this->assertDatabaseHas('productos', [
            'cod' => 'C100',
            'nombre' => 'Hamburguesa Especial'
        ]);
    }

    /**
     * Test para verificar el comportamiento de herencia en MenuController
     */
    public function test_menu_store_behavior()
    {
        // Crear algunos productos para incluir en el menú
        $producto1 = Producto::create([
            'cod' => 'P101',
            'nombre' => 'Producto para Menú 1',
            'pvp' => 8.99,
            'stock' => 10,
            'precioCompra' => 5.50
        ]);
        
        $producto2 = Producto::create([
            'cod' => 'P102',
            'nombre' => 'Producto para Menú 2',
            'pvp' => 6.99,
            'stock' => 15,
            'precioCompra' => 4.50
        ]);

        $menuData = [
            'cod' => 'M100',
            'descripcion' => 'Menú Especial Test',
            'productos' => ['P101', 'P102']
        ];

        $response = $this->post(route('menus.store'), $menuData);

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Menú creado exitosamente');
        
        // Verificar que se creó el menú
        $this->assertDatabaseHas('menus', [
            'cod' => 'M100',
            'descripcion' => 'Menú Especial Test'
        ]);

        // Verificar que también se creó el producto base - esto confirma la herencia
        $this->assertDatabaseHas('productos', [
            'cod' => 'M100',
            'nombre' => 'Menú Especial Test',
        ]);

        // Verificar las relaciones en la tabla pivote
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M100',
            'producto_cod' => 'P101'
        ]);
        
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M100',
            'producto_cod' => 'P102'
        ]);
    }

    /**
     * Test para verificar el comportamiento de update en ProductoController
     */
    public function test_producto_update_behavior()
    {
        // Crear un producto para actualizarlo
        $producto = Producto::create([
            'cod' => 'P200',
            'nombre' => 'Producto Original',
            'pvp' => 25.99,
            'stock' => 30,
            'precioCompra' => 18.50
        ]);

        $updateData = [
            'nombre' => 'Producto Actualizado',
            'pvp' => 29.99,
            'stock' => 35,
            'precioCompra' => 20.50
        ];

        $response = $this->put(route('productos.update', $producto->cod), $updateData);

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Producto actualizado exitosamente');
        
        $this->assertDatabaseHas('productos', [
            'cod' => 'P200',
            'nombre' => 'Producto Actualizado',
            'pvp' => 29.99
        ]);
    }

    /**
     * Test para verificar el comportamiento de update en BebidaController
     */
    public function test_bebida_update_behavior()
    {
        // Crear producto base
        $productoBase = Producto::create([
            'cod' => 'B200',
            'nombre' => 'Agua (Pequeña)',
            'pvp' => 1.99,
            'stock' => 100,
            'precioCompra' => 0.99
        ]);

        // Crear bebida
        $bebida = Bebida::create([
            'cod' => 'B200',
            'tipoBebida' => 'Agua',
            'tamanyo' => 'Pequeña'
        ]);

        $updateData = [
            'tipoBebida' => 'Agua Mineral',
            'tamanyo' => 'Mediana',
            'pvp' => 2.49,
            'stock' => 80
        ];

        $response = $this->put(route('bebidas.update', $bebida->cod), $updateData);

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Bebida actualizada exitosamente');
        
        // Verificar que se actualizó la bebida
        $this->assertDatabaseHas('bebidas', [
            'cod' => 'B200',
            'tipoBebida' => 'Agua Mineral',
            'tamanyo' => 'Mediana'
        ]);

        // Verificar que también se actualizó el producto base - esto confirma la herencia
        $this->assertDatabaseHas('productos', [
            'cod' => 'B200',
            'nombre' => 'Agua Mineral (Mediana)'
        ]);
    }

    /**
     * Test para verificar el comportamiento de destroy en ProductoController
     */
    public function test_producto_destroy_behavior()
    {
        $producto = Producto::create([
            'cod' => 'P300',
            'nombre' => 'Producto Para Eliminar',
            'pvp' => 19.99,
            'stock' => 5,
            'precioCompra' => 15.50
        ]);

        $response = $this->delete(route('productos.destroy', $producto->cod));

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Producto eliminado exitosamente');
        
        $this->assertDatabaseMissing('productos', [
            'cod' => 'P300'
        ]);
    }

    /**
     * Test para verificar el comportamiento de destroy en BebidaController (herencia)
     */
    public function test_bebida_destroy_behavior()
    {
        // Crear producto base
        $productoBase = Producto::create([
            'cod' => 'B300',
            'nombre' => 'Café (Grande)',
            'pvp' => 3.99,
            'stock' => 30,
            'precioCompra' => 1.99
        ]);

        // Crear bebida
        $bebida = Bebida::create([
            'cod' => 'B300',
            'tipoBebida' => 'Café',
            'tamanyo' => 'Grande'
        ]);

        $response = $this->delete(route('bebidas.destroy', $bebida->cod));

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Bebida eliminada exitosamente');
        
        // Verificar que se eliminó la bebida
        $this->assertDatabaseMissing('bebidas', [
            'cod' => 'B300'
        ]);

        // Verificar que también se eliminó el producto base - esto confirma la herencia
        $this->assertDatabaseMissing('productos', [
            'cod' => 'B300'
        ]);
    }

    /**
     * Test para verificar el comportamiento de destroy en MenuController (herencia + relaciones)
     */
    public function test_menu_destroy_behavior()
    {
        // Crear productos para el menú
        $producto1 = Producto::create([
            'cod' => 'P301',
            'nombre' => 'Producto Para Menú Eliminar',
            'pvp' => 8.99,
            'stock' => 10,
            'precioCompra' => 5.99
        ]);

        // Crear producto base para el menú
        $productoBase = Producto::create([
            'cod' => 'M300',
            'nombre' => 'Menú Para Eliminar',
            'pvp' => 8.99 * 0.9,
            'stock' => 0,
            'precioCompra' => 0
        ]);

        // Crear menú
        $menu = Menu::create([
            'cod' => 'M300',
            'descripcion' => 'Menú Para Eliminar'
        ]);

        // Asociar producto al menú
        $menu->productos()->attach('P301');

        $response = $this->delete(route('menus.destroy', $menu->cod));

        $response->assertStatus(302); // Redirección
        $response->assertSessionHas('success', 'Menú eliminado exitosamente');
        
        // Verificar que se eliminó el menú
        $this->assertDatabaseMissing('menus', [
            'cod' => 'M300'
        ]);

        // Verificar que también se eliminó el producto base - esto confirma la herencia
        $this->assertDatabaseMissing('productos', [
            'cod' => 'M300'
        ]);

        // Verificar que se eliminaron las relaciones
        $this->assertDatabaseMissing('menu_producto', [
            'menu_cod' => 'M300'
        ]);

        // Verificar que el producto original NO se eliminó (confirmando comportamiento especial)
        $this->assertDatabaseHas('productos', [
            'cod' => 'P301'
        ]);
    }
}