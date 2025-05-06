<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsuarioSeeder;
use Database\Seeders\MesasSeeder;
use Database\Seeders\ReservasSeeder;
use Database\Seeders\ProductosSeeder;
use Database\Seeders\PedidosSeeder;
use Database\Seeders\MenusSeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Llamamos a los seeders específicos para cada entidad
        $this->call(UsuarioSeeder::class);
        $this->call(MesaSeeder::class);
        $this->call(ReservaSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(PedidoSeeder::class);
        $this->call(MenusSeeder::class);
    }
}
