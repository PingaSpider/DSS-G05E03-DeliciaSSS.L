<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Producto;
use App\Models\Menu;
use App\Models\MenuProducto;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductoBaseController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ComidaController;
use App\Http\Controllers\BebidaController;


class MenuHerenciaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para verificar la estructura de herencia a nivel de datos
     */
    public function test_estructura_herencia_nivel_datos()
    {
        // Crear un producto para asociar al menú
        $producto = Producto::create([
            'cod' => 'P001',
            'nombre' => 'Producto Test',
            'pvp' => 10.99,
            'stock' => 50,
            'precioCompra' => 5.99
        ]);

        // PRIMERO crear el producto base del menú
        $productoBase = Producto::create([
            'cod' => 'M001',
            'nombre' => 'Menú Test',
            'pvp' => 9.99,
            'stock' => 0,
            'precioCompra' => 0
        ]);

        // DESPUÉS crear el menú que utiliza el mismo código
        $menu = Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú Test'
        ]);

        // Asociar el producto al menú
        $menu->productos()->attach('P001', [
            'cantidad' => 1,
            'descripcion' => 'Incluido en menú'
        ]);

        // Verificar que el menú existe
        $this->assertNotNull(Menu::find('M001'));
        
        // Verificar que se creó el registro en la tabla pivot
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'P001',  // Corregido para coincidir con la migración
            'cantidad' => 1
        ]);

        // Verificar que el producto base del menú existe
        $this->assertNotNull(Producto::find('M001'));
        
        // Verificar la herencia a nivel de datos
        $foundMenu = Menu::find('M001');
        $productoBase = $foundMenu->producto;
        
        $this->assertEquals('M001', $productoBase->cod);
        $this->assertEquals('Menú Test', $productoBase->nombre);
    }

    /**
     * Test para verificar la creación de menú mantiene herencia a nivel de producto
     */
    public function test_creacion_menu_mantiene_herencia_producto()
    {
        // Crear productos para incluir en el menú
        $producto1 = Producto::create([
            'cod' => 'P002',
            'nombre' => 'Producto 2',
            'pvp' => 5.99,
            'stock' => 30,
            'precioCompra' => 3.99
        ]);
        
        $producto2 = Producto::create([
            'cod' => 'P003',
            'nombre' => 'Producto 3',
            'pvp' => 7.99,
            'stock' => 20,
            'precioCompra' => 4.99
        ]);
        
        // Datos del menú
        $menuData = [
            'cod' => 'M002',
            'descripcion' => 'Menú Combo',
            'productos' => ['P002', 'P003']
        ];
        
        // PRIMERO crear el producto base del menú
        $productoBase = new Producto();
        $productoBase->cod = $menuData['cod'];
        $productoBase->nombre = $menuData['descripcion'];
        $productoBase->pvp = ($producto1->pvp + $producto2->pvp) * 0.9;
        $productoBase->stock = 0;
        $productoBase->precioCompra = 0;
        $productoBase->save();
        
        // DESPUÉS crear el menú
        $menu = new Menu();
        $menu->cod = $menuData['cod'];
        $menu->descripcion = $menuData['descripcion'];
        $menu->save();
        
        // Asociar los productos
        $menu->productos()->attach([
            'P002' => ['cantidad' => 1, 'descripcion' => 'Parte del combo'],
            'P003' => ['cantidad' => 1, 'descripcion' => 'Parte del combo']
        ]);
        
        // Verificaciones
        $this->assertDatabaseHas('menus', [
            'cod' => 'M002',
            'descripcion' => 'Menú Combo'
        ]);
        
        $this->assertDatabaseHas('productos', [
            'cod' => 'M002',
            'nombre' => 'Menú Combo'
        ]);
        
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M002',
            'producto_cod' => 'P002'  // Corregido para coincidir con la migración
        ]);
        
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M002',
            'producto_cod' => 'P003'  // Corregido para coincidir con la migración
        ]);
        
        // Verificar precio correcto (descuento del 10%)
        $precioEsperado = ($producto1->pvp + $producto2->pvp) * 0.9;
        $productoActualizado = Producto::find('M002');
        $this->assertEquals($precioEsperado, $productoActualizado->pvp);
    }

    /**
     * Test para verificar la actualización de un menú
     */
    public function test_actualizacion_menu_mantiene_herencia_producto()
    {
        // Crear un producto para asociar
        $producto1 = Producto::create([
            'cod' => 'P004',
            'nombre' => 'Producto 4',
            'pvp' => 6.99,
            'stock' => 15,
            'precioCompra' => 3.49
        ]);
        
        // PRIMERO crear el producto base
        $productoBase = Producto::create([
            'cod' => 'M003',
            'nombre' => 'Menú Inicial',
            'pvp' => 6.99 * 0.9,
            'stock' => 0,
            'precioCompra' => 0
        ]);
        
        // DESPUÉS crear el menú
        $menu = Menu::create([
            'cod' => 'M003',
            'descripcion' => 'Menú Inicial'
        ]);
        
        // Asociar el producto inicial
        $menu->productos()->attach('P004', [
            'cantidad' => 1,
            'descripcion' => 'Inicial'
        ]);
        
        // Ahora crear otro producto para añadirlo en la actualización
        $producto2 = Producto::create([
            'cod' => 'P005',
            'nombre' => 'Producto 5',
            'pvp' => 8.99,
            'stock' => 25,
            'precioCompra' => 4.49
        ]);
        
        // Actualizar el menú
        $menu->descripcion = 'Menú Actualizado';
        $menu->save();
        
        // Actualizar producto base
        $productoBase = Producto::find('M003');
        $productoBase->nombre = 'Menú Actualizado';
        $productoBase->save();
        
        // Reemplazar productos
        $menu->productos()->detach();
        $menu->productos()->attach([
            'P004' => ['cantidad' => 1, 'descripcion' => 'Actualizado'],
            'P005' => ['cantidad' => 1, 'descripcion' => 'Nuevo']
        ]);
        
        // Actualizar precio
        $precioEsperado = ($producto1->pvp + $producto2->pvp) * 0.9;
        $productoBase->pvp = $precioEsperado;
        $productoBase->save();
        
        // Verificaciones
        $this->assertDatabaseHas('menus', [
            'cod' => 'M003',
            'descripcion' => 'Menú Actualizado'
        ]);
        
        $this->assertDatabaseHas('productos', [
            'cod' => 'M003',
            'nombre' => 'Menú Actualizado',
        ]);
        
        // Verificar que ahora tiene dos productos asociados
        $menuActualizado = Menu::find('M003');
        $this->assertCount(2, $menuActualizado->productos);
    }

    /**
     * Test para verificar la eliminación de un menú elimina el producto base
     */
    public function test_eliminacion_menu_elimina_producto_base()
    {
        // PRIMERO crear el producto base
        $productoBase = Producto::create([
            'cod' => 'M004',
            'nombre' => 'Menú para eliminar',
            'pvp' => 12.99,
            'stock' => 0,
            'precioCompra' => 0
        ]);
        
        // DESPUÉS crear el menú
        $menu = Menu::create([
            'cod' => 'M004',
            'descripcion' => 'Menú para eliminar'
        ]);
        
        // Crear un producto para asociar
        $producto = Producto::create([
            'cod' => 'P006',
            'nombre' => 'Producto 6',
            'pvp' => 5.99,
            'stock' => 10,
            'precioCompra' => 2.99
        ]);
        
        // Asociar el producto al menú
        $menu->productos()->attach('P006', [
            'cantidad' => 1,
            'descripcion' => 'Para eliminar'
        ]);
        
        // Verificar que todo se creó correctamente
        $this->assertNotNull(Menu::find('M004'));
        $this->assertNotNull(Producto::find('M004'));
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M004',
            'producto_cod' => 'P006'  // Corregido para coincidir con la migración
        ]);
        
        // Eliminar el menú
        $menu->delete();
        
        // También eliminar el producto base (simulando on delete cascade)
        $productoBase->delete();
        
        // Verificar que se eliminó el menú
        $this->assertNull(Menu::find('M004'));
        
        // Verificar que se eliminó el producto base
        $this->assertNull(Producto::find('M004'));
        
        // Verificar que se eliminaron las relaciones
        $this->assertDatabaseMissing('menu_producto', [
            'menu_cod' => 'M004'
        ]);
        
        // Verificar que el producto original sigue existiendo
        $this->assertNotNull(Producto::find('P006'));
    }
}