<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsuariosSeeder;
use Database\Seeders\MesasSeeder;
use Database\Seeders\ReservasSeeder;
use Database\Seeders\ProductosSeeder;
use Database\Seeders\PedidosSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Llamamos a los seeders específicos para cada entidad
        $this->call(UsuariosSeeder::class);
        $this->call(MesasSeeder::class);
        $this->call(ReservasSeeder::class);
        $this->call(ProductosSeeder::class);
        $this->call(PedidosSeeder::class);
    }
}
