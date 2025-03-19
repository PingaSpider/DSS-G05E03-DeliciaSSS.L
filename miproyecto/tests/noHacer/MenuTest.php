<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Menu;
use App\Models\Producto;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_menu()
    {
        //Crear primero el producto
        Producto::create([
            'cod' => 'M001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);

        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú Especial'
        ]);

        $this->assertDatabaseHas('menus', ['cod' => 'M001']);
    }

    /** @test */
    public function puede_modificar_un_menu()
    {
        //Crear primero el producto
        Producto::create([
            'cod' => 'M001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);
        // Primero crear el menú
        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú Original'
        ]);

        // Luego actualizar
        $menu = Menu::where('cod', 'M001')->first();
        $menu->update([
            'descripcion' => 'Menú Actualizado'
        ]);

        // Verificar actualización
        $this->assertDatabaseHas('menus', [
            'cod' => 'M001',
            'descripcion' => 'Menú Actualizado'
        ]);
    }

    /** @test */
    public function puede_eliminar_un_menu()
    {
        //Crear primero el producto
        Producto::create([
            'cod' => 'M001',
            'pvp' => 10.99,
            'nombre' => 'Producto Test',
            'stock' => 20,
            'disponible' => true,
            'precioCompra' => 5
        ]);
        // Primero crear el menú
        Menu::create([
            'cod' => 'M001',
            'descripcion' => 'Menú a Eliminar'
        ]);

        // Luego eliminar
        $menu = Menu::where('cod', 'M001')->first();
        $menu->delete();

        // Verificar eliminación
        $this->assertDatabaseMissing('menus', ['cod' => 'M001']);
    }
}