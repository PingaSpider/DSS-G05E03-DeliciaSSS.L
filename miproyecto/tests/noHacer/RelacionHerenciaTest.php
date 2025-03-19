<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Menu;

class RelacionHerenciaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function producto_puede_ser_extendido_como_comida()
    {
        // 1. Primero creamos el producto base
        $producto = Producto::create([
            'cod' => 'C001',
            'pvp' => 12.50,
            'nombre' => 'Ensalada César',
            'stock' => 10,
            'precioCompra' => 8,
            'disponible' => true
        ]);

        // 2. Luego creamos la comida que extiende al producto
        $comida = Comida::create([
            'cod' => 'C001',  // Mismo código para establecer la relación
            'descripcion' => 'Ensalada con pollo, lechuga, crutones y salsa césar'
        ]);

        // 3. Verificamos que ambos existen en la base de datos
        $this->assertDatabaseHas('productos', [
            'cod' => 'C001',
            'nombre' => 'Ensalada César'
        ]);

        $this->assertDatabaseHas('comidas', [
            'cod' => 'C001',
            'descripcion' => 'Ensalada con pollo, lechuga, crutones y salsa césar'
        ]);

        // 4. Verificamos la relación entre producto y comida
        // Si tus modelos tienen relaciones definidas en Laravel, puedes verificar así:
        if (method_exists($comida, 'producto')) {
            $productoRelacionado = $comida->producto;
            $this->assertNotNull($productoRelacionado);
            $this->assertEquals('Ensalada César', $productoRelacionado->nombre);
        }

        if (method_exists($producto, 'comida')) {
            $comidaRelacionada = $producto->comida;
            $this->assertNotNull($comidaRelacionada);
            $this->assertEquals('Ensalada con pollo, lechuga, crutones y salsa césar', $comidaRelacionada->descripcion);
        }
    }

    /** @test */
    public function producto_puede_ser_extendido_como_bebida()
    {
        // 1. Primero creamos el producto base
        $producto = Producto::create([
            'cod' => 'B001',
            'pvp' => 3.50,
            'nombre' => 'Refresco de cola',
            'stock' => 20,
            'precioCompra' => 2,
            'disponible' => true
        ]);

        // 2. Luego creamos la bebida que extiende al producto
        $bebida = Bebida::create([
            'cod' => 'B001',  // Mismo código para establecer la relación
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Refresco'
        ]);

        // 3. Verificamos que ambos existen en la base de datos
        $this->assertDatabaseHas('productos', [
            'cod' => 'B001',
            'nombre' => 'Refresco de cola'
        ]);

        // Verifica que la bebida también existe
        $this->assertDatabaseHas('bebidas', [
            'cod' => 'B001',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Refresco'
        ]);

        // 4. Verificamos la relación entre producto y bebida
        if (method_exists($bebida, 'producto')) {
            $productoRelacionado = $bebida->producto;
            $this->assertNotNull($productoRelacionado);
            $this->assertEquals('Refresco de cola', $productoRelacionado->nombre);
        }

        if (method_exists($producto, 'bebida')) {
            $bebidaRelacionada = $producto->bebida;
            $this->assertNotNull($bebidaRelacionada);
            $this->assertEquals('Refresco', $bebidaRelacionada->tipoBebida);
        }
    }

    /** @test */
    public function producto_puede_ser_extendido_como_menu()
    {
        // 1. Primero creamos el producto base
        $producto = Producto::create([
            'cod' => 'M001',
            'pvp' => 19.99,
            'nombre' => 'Menú Ejecutivo',
            'stock' => 15,
            'precioCompra' => 12,
            'disponible' => true
        ]);

        // 2. Luego creamos el menú que extiende al producto
        $menu = Menu::create([
            'cod' => 'M001',  // Mismo código para establecer la relación
            'descripcion' => 'Menú ejecutivo con primer plato, segundo y postre'
        ]);

        // 3. Verificamos que ambos existen en la base de datos
        $this->assertDatabaseHas('productos', [
            'cod' => 'M001',
            'nombre' => 'Menú Ejecutivo'
        ]);

        $this->assertDatabaseHas('menus', [
            'cod' => 'M001',
            'descripcion' => 'Menú ejecutivo con primer plato, segundo y postre'
        ]);

        // 4. Verificamos la relación entre producto y menú
        if (method_exists($menu, 'producto')) {
            $productoRelacionado = $menu->producto;
            $this->assertNotNull($productoRelacionado);
            $this->assertEquals('Menú Ejecutivo', $productoRelacionado->nombre);
        }

        if (method_exists($producto, 'menu')) {
            $menuRelacionado = $producto->menu;
            $this->assertNotNull($menuRelacionado);
            $this->assertEquals('Menú ejecutivo con primer plato, segundo y postre', $menuRelacionado->descripcion);
        }
    }

    /** @test */
    public function puede_modificar_producto_y_afectar_a_comida_relacionada()
    {
        // 1. Creamos producto y comida relacionados
        Producto::create([
            'cod' => 'C001',
            'pvp' => 12.50,
            'nombre' => 'Paella',
            'stock' => 10,
            'precioCompra' => 8,
            'disponible' => true
        ]);

        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Paella valenciana'
        ]);

        // 2. Modificamos el producto
        $producto = Producto::where('cod', 'C001')->first();
        $producto->update([
            'pvp' => 14.99,
            'stock' => 5,
            'disponible' => false
        ]);

        // 3. Verificamos que los cambios se aplican correctamente
        $this->assertDatabaseHas('productos', [
            'cod' => 'C001',
            'pvp' => 14.99,
            'stock' => 5,
            'disponible' => 1
        ]);

        // 4. La comida sigue existiendo y relacionada con el producto modificado
        $comida = Comida::where('cod', 'C001')->first();
        $this->assertNotNull($comida);
        $this->assertEquals('Paella valenciana', $comida->descripcion);

        // 5. Verificamos que la relación sigue funcionando después de la modificación
        if (method_exists($comida, 'producto')) {
            $productoRelacionado = $comida->producto;
            $this->assertNotNull($productoRelacionado);
            $this->assertEquals(14.99, $productoRelacionado->pvp);
        }
    }

    /** @test */
    public function eliminar_producto_debe_eliminar_entidad_relacionada()
    {
        // 1. Creamos producto y bebida relacionados
        Producto::create([
            'cod' => 'B001',
            'pvp' => 3.50,
            'nombre' => 'Cerveza',
            'stock' => 20,
            'precioCompra' => 2,
            'disponible' => true
        ]);

        Bebida::create([
            'cod' => 'B001',
            'tamanyo' => 'Caña',
            'tipoBebida' => 'Cerveza'
        ]);

        // 2. Verificamos que ambos existen
        $this->assertDatabaseHas('productos', ['cod' => 'B001']);
        $this->assertDatabaseHas('bebidas', ['cod' => 'B001']);

        // 3. Eliminamos el producto
        $producto = Producto::where('cod', 'B001')->first();
        $producto->delete();

        // 4. Verificamos que tanto el producto como la bebida han sido eliminados
        // Esto depende de si tienes configurada la eliminación en cascada en la base de datos
        $this->assertDatabaseMissing('productos', ['cod' => 'B001']);
        
        // Si tienes onDelete('cascade') en la relación de Eloquent o en la base de datos,
        // la bebida también debería haber sido eliminada
        $this->assertDatabaseMissing('bebidas', ['cod' => 'B001']);
    }
}