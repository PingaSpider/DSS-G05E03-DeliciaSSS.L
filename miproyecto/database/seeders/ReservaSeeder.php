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
        ]);

        Reserva::create([
            'fecha' => now()->addDays(3),
            'hora' => '21:00:00',
            'codReserva' => 1002,
            'cantPersona' => 4,
            'reservaConfirmada' => true,
            'mesa_id' => 'M002',  
            'usuario_id' => 2,
        ]);

        Reserva::create([
            'fecha' => now()->addDays(4),
            'hora' => '20:30:00',
            'codReserva' => 1003,
            'cantPersona' => 6,
            'reservaConfirmada' => false,
            'mesa_id' => 'M003',  
            'usuario_id' => 5,
        ]);

        Reserva::create([
            'fecha' => now()->addDays(5),
            'hora' => '18:45:00',
            'codReserva' => 1004,
            'cantPersona' => 3,
            'reservaConfirmada' => true,
            'mesa_id' => 'M004',  
            'usuario_id' => 8,
        ]);

        Reserva::create([
            'fecha' => now()->addDays(6),
            'hora' => '19:15:00',
            'codReserva' => 1005,
            'cantPersona' => 5,
            'reservaConfirmada' => true,
            'mesa_id' => 'M005',  
            'usuario_id' => 10,
        ]);

        Reserva::create([
            'fecha' => now()->addDays(7),
            'hora' => '22:00:00',
            'codReserva' => 1006,
            'cantPersona' => 8,
            'reservaConfirmada' => false,
            'mesa_id' => 'M006',  
            'usuario_id' => 12,
        ]);

        Reserva::create([
            'fecha' => now()->addDays(8),
            'hora' => '20:00:00',
            'codReserva' => 1007,
            'cantPersona' => 2,
            'reservaConfirmada' => true,
            'mesa_id' => 'M007',  
            'usuario_id' => 14,
        ]);

        Reserva::create([
            'fecha' => now()->addDays(9),
            'hora' => '21:30:00',
            'codReserva' => 1008,
            'cantPersona' => 4,
            'reservaConfirmada' => false,
            'mesa_id' => 'M008',  
            'usuario_id' => 15,
        ]);
    }
}
