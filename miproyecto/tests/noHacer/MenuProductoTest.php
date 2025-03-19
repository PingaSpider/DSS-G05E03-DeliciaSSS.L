<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Menu;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\MenuProducto;

class MenuProductoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_relacion_menu_producto()
    {
        // Crear producto (comida)
        Producto::create([
            'cod' => 'C001',
            'pvp' => 12.99,
            'nombre' => 'Pasta Boloñesa',
            'stock' => 10,
            'precioCompra' => 8,
            'disponible' => true
        ]);

        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Pasta con salsa boloñesa'
        ]);

        // Crear menú
        Producto::create([
            'cod' => 'M001',
            'pvp' => 19.99,
            'nombre' => 'Menú Italiano',
            'stock' => 5,
            'precioCompra' => 12,
            'disponible' => true
        ]);

        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú especial italiano'
        ]);

        // Crear relación
        MenuProducto::create([
            'menu_cod' => 'M001',
            'producto_cod' => 'C001',
            'cantidad' => 1,
            'descripcion' => 'Plato principal'
        ]);

        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'C001'
        ]);
    }

    /** @test */
    public function puede_modificar_una_relacion_menu_producto()
    {
        // Crear producto (bebida)
        Producto::create([
            'cod' => 'B001',
            'pvp' => 3.50,
            'nombre' => 'Vino Tinto',
            'stock' => 20,
            'precioCompra' => 2,
            'disponible' => true
        ]);

        Bebida::create([
            'cod' => 'B001',
            'tamanyo' => 'Copa',
            'tipoBebida' => 'Vino'
        ]);

        // Crear menú
        Producto::create([
            'cod' => 'M001',
            'pvp' => 25.99,
            'nombre' => 'Menú Degustación',
            'stock' => 8,
            'precioCompra' => 15,
            'disponible' => true
        ]);

        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú degustación premium'
        ]);

        // Crear relación
        MenuProducto::create([
            'menu_cod' => 'M001',
            'producto_cod' => 'B001',
            'cantidad' => 1,
            'descripcion' => 'Bebida estándar'
        ]);

        // Modificar relación
        $menuProducto = MenuProducto::where([
            'menu_cod' => 'M001',
            'producto_cod' => 'B001'
        ])->first();

        $menuProducto->update([
            'cantidad' => 2,
            'descripcion' => 'Bebida premium (doble)'
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'B001',
            'cantidad' => 2,
            'descripcion' => 'Bebida premium (doble)'
        ]);
    }

    /** @test */
    public function puede_eliminar_una_relacion_menu_producto()
    {
        // Crear producto
        Producto::create([
            'cod' => 'C001',
            'pvp' => 8.99,
            'nombre' => 'Postre',
            'stock' => 15,
            'precioCompra' => 5,
            'disponible' => true
        ]);

        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Postre del día'
        ]);

        // Crear menú
        Producto::create([
            'cod' => 'M001',
            'pvp' => 22.50,
            'nombre' => 'Menú Completo',
            'stock' => 10,
            'precioCompra' => 14,
            'disponible' => true
        ]);

        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú completo con postre'
        ]);

        // Crear relación
        MenuProducto::create([
            'menu_cod' => 'M001',
            'producto_cod' => 'C001',
            'cantidad' => 1,
            'descripcion' => 'Postre incluido'
        ]);

        // Eliminar relación
        $menuProducto = MenuProducto::where([
            'menu_cod' => 'M001',
            'producto_cod' => 'C001'
        ])->first();

        $menuProducto->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'C001'
        ]);
    }

    /** @test */
    public function puede_crear_un_menu_completo_con_varios_productos()
    {
        // Crear menú
        Producto::create([
            'cod' => 'M001',
            'pvp' => 29.99,
            'nombre' => 'Menú Deluxe',
            'stock' => 5,
            'precioCompra' => 18,
            'disponible' => true
        ]);

        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú completo deluxe'
        ]);

        // Crear primer plato
        Producto::create([
            'cod' => 'C001',
            'pvp' => 9.99,
            'nombre' => 'Ensalada',
            'stock' => 15,
            'precioCompra' => 6,
            'disponible' => true
        ]);

        Comida::create([
            'cod' => 'C001',
            'descripcion' => 'Ensalada fresca'
        ]);

        // Crear segundo plato
        Producto::create([
            'cod' => 'C002',
            'pvp' => 15.99,
            'nombre' => 'Filete',
            'stock' => 8,
            'precioCompra' => 10,
            'disponible' => true
        ]);

        Comida::create([
            'cod' => 'C002',
            'descripcion' => 'Filete con patatas'
        ]);

        // Crear bebida
        Producto::create([
            'cod' => 'B001',
            'pvp' => 4.50,
            'nombre' => 'Agua mineral',
            'stock' => 25,
            'precioCompra' => 2,
            'disponible' => true
        ]);

        Bebida::create([
            'cod' => 'B001',
            'tamanyo' => 'Botella',
            'tipoBebida' => 'Agua'
        ]);

        // Crear relaciones con el menú
        MenuProducto::create([
            'menu_cod' => 'M001',
            'producto_cod' => 'C001',
            'cantidad' => 1,
            'descripcion' => 'Primer plato'
        ]);

        MenuProducto::create([
            'menu_cod' => 'M001',
            'producto_cod' => 'C002',
            'cantidad' => 1,
            'descripcion' => 'Segundo plato'
        ]);

        MenuProducto::create([
            'menu_cod' => 'M001',
            'producto_cod' => 'B001',
            'cantidad' => 1,
            'descripcion' => 'Bebida incluida'
        ]);

        // Verificar todas las relaciones
        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'C001'
        ]);

        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'C002'
        ]);

        $this->assertDatabaseHas('menu_producto', [
            'menu_cod' => 'M001',
            'producto_cod' => 'B001'
        ]);
    }
}