<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        Usuario::create([
            'nombre' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password123'),
            'telefono' => '000000000',
            'rol' => 'admin',
        ]);
        Usuario::create([
            'nombre' => 'alvaro',
            'email' => 'alvaro@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '111222333',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'beatriz',
            'email' => 'beatriz@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '444555666',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'carlos',
            'email' => 'carlos@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '777888999',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'daniela',
            'email' => 'daniela@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '123321123',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'esteban',
            'email' => 'esteban@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '321123321',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'fabiana',
            'email' => 'fabiana@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '456654456',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'gonzalo',
            'email' => 'gonzalo@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '654456654',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'helena',
            'email' => 'helena@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '789987789',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'ignacio',
            'email' => 'ignacio@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '987789987',
            'rol' => 'user',
        ]);

        Usuario::create([
            'nombre' => 'jose',
            'email' => 'jose@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '159951159'
        ]);

        Usuario::create([
            'nombre' => 'karina',
            'email' => 'karina@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '951159951'
        ]);

        Usuario::create([
            'nombre' => 'luis',
            'email' => 'luis@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '753357753'
        ]);

        Usuario::create([
            'nombre' => 'marta',
            'email' => 'marta@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '357753357'
        ]);

        Usuario::create([
            'nombre' => 'nicolas',
            'email' => 'nicolas@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '258852258'
        ]);

        Usuario::create([
            'nombre' => 'olivia',
            'email' => 'olivia@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '852258852'
        ]);

        Usuario::create([
            'nombre' => 'pedro',
            'email' => 'pedro@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '147741147'
        ]);

        Usuario::create([
            'nombre' => 'quique',
            'email' => 'quique@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '741147741'
        ]);

        Usuario::create([
            'nombre' => 'rosario',
            'email' => 'rosario@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '369963369'
        ]);

        Usuario::create([
            'nombre' => 'santiago',
            'email' => 'santiago@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '963369963'
        ]);

        Usuario::create([
            'nombre' => 'teresa',
            'email' => 'teresa@example.com',
            'password' => Hash::make('123456'),
            'telefono' => '789123456'
        ]);
    }
}
