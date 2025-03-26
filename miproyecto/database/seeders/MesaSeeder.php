<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mesa;

class MesaSeeder extends Seeder
{
    public function run()
    {
        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M001',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M002',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 6,
            'codMesa' => 'M003',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M004',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M005',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 6,
            'codMesa' => 'M006',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 8,
            'codMesa' => 'M007',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 10,
            'codMesa' => 'M008',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M009',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M010',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 6,
            'codMesa' => 'M011',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 8,
            'codMesa' => 'M012',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 10,
            'codMesa' => 'M013',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M014',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M015',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 6,
            'codMesa' => 'M016',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 8,
            'codMesa' => 'M017',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 10,
            'codMesa' => 'M018',
            'ocupada' => true
        ]);

        Mesa::create([
            'cantidadMesa' => 2,
            'codMesa' => 'M019',
            'ocupada' => false
        ]);

        Mesa::create([
            'cantidadMesa' => 4,
            'codMesa' => 'M020',
            'ocupada' => true
        ]);

    }
}
