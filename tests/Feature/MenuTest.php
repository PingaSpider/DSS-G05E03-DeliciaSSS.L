<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Menu;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_menu()
    {
        Menu::create([
            'descripcion' => 'Menú Especial'
        ]);

        $this->assertDatabaseHas('menus', ['descripcion' => 'Menú Especial']);
    }
}
