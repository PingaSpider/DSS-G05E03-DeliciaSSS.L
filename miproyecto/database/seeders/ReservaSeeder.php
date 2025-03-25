<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;

class ReservaSeeder extends Seeder
{
    public function run()
    {
        Reserva::create([
            'fecha' => now()->addDays(2),
            'hora' => '19:00:00',
            'codReserva' => 1001,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 'M001',  
            'usuario_id' => 1,
            'usuario_email' => 'alvaro@example.com'
        ]);

        Reserva::create([
            'fecha' => now()->addDays(3),
            'hora' => '21:00:00',
            'codReserva' => 1002,
            'cantPersona' => 4,
            'reservaConfirmada' => true,
            'mesa_id' => 'M002',  
            'usuario_id' => 2,
            'usuario_email' => 'beatriz@example.com'
        ]);
    }
}
