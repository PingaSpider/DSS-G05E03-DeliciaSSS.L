<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Menu;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        // BEBIDAS - En orden ascendente bb01, bb02, etc.
        
        // 1. Coca-Cola - bb01
        Producto::create([
            'cod' => 'B0001',
            'pvp' => 2.50,
            'nombre' => 'Coca-Cola',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70',
            'imagen_url' => 'bebida/bb01.png'
        ]);

        Bebida::create([
            'cod' => 'B0001',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Gaseosa'
        ]);

        // 2. Coca-Cola Zero - bb02
        Producto::create([
            'cod' => 'B0012',
            'pvp' => 2.50,
            'nombre' => 'Coca-Cola Zero',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70',
            'imagen_url' => 'bebida/bb02.png'        
        ]);

        Bebida::create([
            'cod' => 'B0012',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Gaseosa'
        ]);

        // 3. Sprite - bb03
        Producto::create([
            'cod' => 'B0013',
            'pvp' => 2.50,
            'nombre' => 'Sprite',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70',
            'imagen_url' => 'bebida/bb03.png'
        ]);

        Bebida::create([
            'cod' => 'B0013',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Gaseosa'
        ]);

        // 4. Fanta Naranja - bb04
        Producto::create([
            'cod' => 'B0014',
            'pvp' => 2.50,
            'nombre' => 'Fanta Naranja',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.70',
            'imagen_url' => 'bebida/bb04.png'
        ]);

        Bebida::create([
            'cod' => 'B0014',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Gaseosa'
        ]);

        // 5. San Miguel - bb05
        Producto::create([
            'cod' => 'B0015',
            'pvp' => 3.50,
            'nombre' => 'San Miguel',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '1.20',
            'imagen_url' => 'bebida/bb05.png'
        ]);

        Bebida::create([
            'cod' => 'B0015',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Cerveza'
        ]);

        // 6. Estrella Damm - bb06
        Producto::create([
            'cod' => 'B0016',
            'pvp' => 3.50,
            'nombre' => 'Estrella Damm',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '1.20',
            'imagen_url' => 'bebida/bb06.png'
        ]);

        Bebida::create([
            'cod' => 'B0016',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Cerveza'
        ]);

        // 7. Estrella Galicia - bb07
        Producto::create([
            'cod' => 'B0017',
            'pvp' => 3.50,
            'nombre' => 'Estrella Galicia',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '1.20',
            'imagen_url' => 'bebida/bb07.png'
        ]);

        Bebida::create([
            'cod' => 'B0017',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Cerveza'
        ]);

        // 8. Solán de Cabras - bb08
        Producto::create([
            'cod' => 'B0018',
            'pvp' => 2.00,
            'nombre' => 'Solán de Cabras',
            'stock' => 60,
            'disponible'=> true,
            'precioCompra'=> '0.50',
            'imagen_url' => 'bebida/bb08.png'
        ]);

        Bebida::create([
            'cod' => 'B0018',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Agua'
        ]);

        // 9. Evian - bb09
        Producto::create([
            'cod' => 'B0019',
            'pvp' => 2.50,
            'nombre' => 'Evian',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '0.80',
            'imagen_url' => 'bebida/bb09.png'
        ]);

        Bebida::create([
            'cod' => 'B0019',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Agua'
        ]);

        // 10. Casillero del Diablo (Vino Tinto) - bb10
        Producto::create([
            'cod' => 'B0020',
            'pvp' => 8.50,
            'nombre' => 'Casillero del Diablo Cabernet Sauvignon',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '5.00',
            'imagen_url' => 'bebida/bb10.png'
        ]);

        Bebida::create([
            'cod' => 'B0020',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Vino'
        ]);

        // 11. Izadi Larrosa (Vino Rosado) - bb11
        Producto::create([
            'cod' => 'B0021',
            'pvp' => 7.50,
            'nombre' => 'Izadi Larrosa',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '4.50',
            'imagen_url' => 'bebida/bb11.png'
        ]);

        Bebida::create([
            'cod' => 'B0021',
            'tamanyo' => 'Grande',
            'tipoBebida' => 'Vino'
        ]);

        // 12. Espresso - bb12
        Producto::create([
            'cod' => 'B0022',
            'pvp' => 1.50,
            'nombre' => 'Espresso',
            'stock' => 100,
            'disponible'=> true,
            'precioCompra'=> '0.30',
            'imagen_url' => 'bebida/bb12.png'
        ]);

        Bebida::create([
            'cod' => 'B0022',
            'tamanyo' => 'Pequeño',
            'tipoBebida' => 'Café'
        ]);

        // 13. Café con Leche - bb13
        Producto::create([
            'cod' => 'B0023',
            'pvp' => 2.00,
            'nombre' => 'Café con Leche',
            'stock' => 100,
            'disponible'=> true,
            'precioCompra'=> '0.50',
            'imagen_url' => 'bebida/bb13.png'
        ]);

        Bebida::create([
            'cod' => 'B0023',
            'tamanyo' => 'Mediano',
            'tipoBebida' => 'Café'
        ]);

        // DESAYUNOS - En orden ascendente de01, de02, etc.
        
        // Tostada con Aguacate y Huevo - de01
        Producto::create([
            'cod' => 'C0021',
            'pvp' => 6.50,
            'nombre' => 'Desayuno Tostada con Aguacate',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '3.00',
            'imagen_url' => 'desayuno/de01.png'
        ]);

        Comida::create([
            'cod' => 'C0021',
            'descripcion' => 'Tostada con aguacate fresco, huevo poché y rúcula'
        ]);

        // Desayuno Americano - de02
        Producto::create([
            'cod' => 'C0022',
            'pvp' => 8.95,
            'nombre' => 'Desayuno Americano',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '4.50',
            'imagen_url' => 'desayuno/de02.png'
        ]);

        Comida::create([
            'cod' => 'C0022',
            'descripcion' => 'Dos huevos fritos, bacon crujiente y tostadas'
        ]);

        // Tortilla de Champiñones - de03
        Producto::create([
            'cod' => 'C0023',
            'pvp' => 7.50,
            'nombre' => 'Desayuno Tortilla de Champiñones',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '3.80',
            'imagen_url' => 'desayuno/de03.png'
        ]);

        Comida::create([
            'cod' => 'C0023',
            'descripcion' => 'Tortilla francesa rellena de champiñones y queso con ensalada'
        ]);

        // Café y Croissant - de04
        Producto::create([
            'cod' => 'C0024',
            'pvp' => 4.50,
            'nombre' => 'Desayuno Café y Croissant',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '2.00',
            'imagen_url' => 'desayuno/de04.png'
        ]);

        Comida::create([
            'cod' => 'C0024',
            'descripcion' => 'Café recién hecho con croissant de mantequilla'
        ]);

        // Pancakes con Frutos Rojos - de05
        Producto::create([
            'cod' => 'C0025',
            'pvp' => 7.95,
            'nombre' => 'Desayuno Pancakes con Frutos Rojos',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '4.00',
            'imagen_url' => 'desayuno/de05.png'
        ]);

        Comida::create([
            'cod' => 'C0025',
            'descripcion' => 'Pancakes esponjosos con sirope, frambuesas y menta fresca'
        ]);

        // HAMBURGUESAS - En orden ascendente hb01, hb02, etc.
        
        // Hamburguesa de Pollo Crispy - hb01
        Producto::create([
            'cod' => 'C0026',
            'pvp' => 8.95,
            'nombre' => 'Crispy Chicken Burger',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '5.00',
            'imagen_url' => 'hamburguesa/hb01.png'
        ]);

        Comida::create([
            'cod' => 'C0026',
            'descripcion' => 'Hamburguesa de pollo crujiente con lechuga, tomate y salsa especial'
        ]);

        // Hamburguesa Clásica con Queso - hb02
        Producto::create([
            'cod' => 'C0027',
            'pvp' => 9.50,
            'nombre' => 'Cheeseburger Clásica',
            'stock' => 30,
            'disponible'=> true,
            'precioCompra'=> '5.50',
            'imagen_url' => 'hamburguesa/hb02.png'
        ]);

        Comida::create([
            'cod' => 'C0027',
            'descripcion' => 'Hamburguesa de ternera con queso cheddar, lechuga, tomate y pepinillos'
        ]);

        // Hamburguesa Doble - hb03
        Producto::create([
            'cod' => 'C0028',
            'pvp' => 11.95,
            'nombre' => 'Double Burger',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '7.00',
            'imagen_url' => 'hamburguesa/hb03.png'
        ]);

        Comida::create([
            'cod' => 'C0028',
            'descripcion' => 'Doble carne de ternera con doble queso, lechuga, tomate y salsa especial'
        ]);

        // Hamburguesa Vegetariana - hb04
        Producto::create([
            'cod' => 'C0029',
            'pvp' => 8.75,
            'nombre' => 'Veggie Burger',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '4.50',
            'imagen_url' => 'hamburguesa/hb04.png'
        ]);

        Comida::create([
            'cod' => 'C0029',
            'descripcion' => 'Hamburguesa vegetariana con queso, lechuga, tomate y mayonesa'
        ]);

        // Hamburguesa BBQ - hb05
        Producto::create([
            'cod' => 'C0030',
            'pvp' => 10.50,
            'nombre' => 'BBQ Burger',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '6.00',
            'imagen_url' => 'hamburguesa/hb05.png'
        ]);

        Comida::create([
            'cod' => 'C0030',
            'descripcion' => 'Hamburguesa con salsa BBQ, cebolla caramelizada, bacon y queso cheddar'
        ]);

        // Hamburguesa Black Angus - hb06
        Producto::create([
            'cod' => 'C0031',
            'pvp' => 12.95,
            'nombre' => 'Black Angus Burger',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '8.00',
            'imagen_url' => 'hamburguesa/hb06.png'
        ]);

        Comida::create([
            'cod' => 'C0031',
            'descripcion' => 'Hamburguesa de Black Angus premium con pan negro, queso, lechuga y cebolla morada'
        ]);

        // Hamburguesa Big Mac Style - hb07
        Producto::create([
            'cod' => 'C0032',
            'pvp' => 11.50,
            'nombre' => 'Big Tower Burger',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '6.50',
            'imagen_url' => 'hamburguesa/hb07.png'
        ]);

        Comida::create([
            'cod' => 'C0032',
            'descripcion' => 'Hamburguesa triple con salsa especial, lechuga, queso y pepinillos en pan con semillas'
        ]);

        // Patatas Fritas - hb08
        Producto::create([
            'cod' => 'C0033',
            'pvp' => 3.50,
            'nombre' => 'Patatas Fritas Deluxe para Burguer',
            'stock' => 50,
            'disponible'=> true,
            'precioCompra'=> '1.00',
            'imagen_url' => 'hamburguesa/hb08.png'
        ]);

        Comida::create([
            'cod' => 'C0033',
            'descripcion' => 'Patatas fritas crujientes doradas, perfectas como acompañamiento'
        ]);

        // PIZZAS - En orden ascendente pz01, pz02, etc.
        
        // Pizza Carbonara - pz01
        Producto::create([
            'cod' => 'C0034',
            'pvp' => 12.50,
            'nombre' => 'Pizza Carbonara',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '6.00',
            'imagen_url' => 'pizza/pz01.png'
        ]);

        Comida::create([
            'cod' => 'C0034',
            'descripcion' => 'Pizza con salsa carbonara, bacon, cebolla y queso mozzarella'
        ]);

        // Pizza Vegetariana - pz02
        Producto::create([
            'cod' => 'C0035',
            'pvp' => 11.95,
            'nombre' => 'Pizza Vegetariana',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '5.50',
            'imagen_url' => 'pizza/pz02.png'
        ]);

        Comida::create([
            'cod' => 'C0035',
            'descripcion' => 'Pizza con pimientos, champiñones, jalapeños, aceitunas negras y queso'
        ]);

        // Pizza Margherita - pz03
        Producto::create([
            'cod' => 'C0036',
            'pvp' => 10.50,
            'nombre' => 'Pizza Margherita',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '5.00',
            'imagen_url' => 'pizza/pz03.png'
        ]);

        Comida::create([
            'cod' => 'C0036',
            'descripcion' => 'Pizza clásica con tomate, mozzarella di bufala y albahaca fresca'
        ]);

        // Pizza Funghi - pz04
        Producto::create([
            'cod' => 'C0037',
            'pvp' => 11.50,
            'nombre' => 'Pizza Funghi',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '5.50',
            'imagen_url' => 'pizza/pz04.png'
        ]);

        Comida::create([
            'cod' => 'C0037',
            'descripcion' => 'Pizza con champiñones frescos, cebolla, tomates cherry y queso mozzarella'
        ]);

        // Pizza Pepperoni - pz05
        Producto::create([
            'cod' => 'C0038',
            'pvp' => 12.95,
            'nombre' => 'Pizza Pepperoni',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '6.50',
            'imagen_url' => 'pizza/pz05.png'
        ]);

        Comida::create([
            'cod' => 'C0038',
            'descripcion' => 'Pizza clásica americana con abundante pepperoni y extra queso'
        ]);

        // Pizza Campesina - pz06
        Producto::create([
            'cod' => 'C0039',
            'pvp' => 13.50,
            'nombre' => 'Pizza Campesina',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '7.00',
            'imagen_url' => 'pizza/pz06.png'
        ]);

        Comida::create([
            'cod' => 'C0039',
            'descripcion' => 'Pizza con jamón, tomate, champiñones, bacon y aceitunas negras'
        ]);
        
        // POSTRES - En orden ascendente po01, po02, etc.
        
        // Tarta de Chocolate - po01
        Producto::create([
            'cod' => 'C0040',
            'pvp' => 5.50,
            'nombre' => 'Tarta de Chocolate',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '2.50',
            'imagen_url' => 'postre/po01.png'
        ]);

        Comida::create([
            'cod' => 'C0040',
            'descripcion' => 'Tarta de chocolate con tres capas y cobertura de chocolate negro'
        ]);

        // Cheesecake de Frutos Rojos - po02
        Producto::create([
            'cod' => 'C0041',
            'pvp' => 5.95,
            'nombre' => 'Tarta Cheesecake de Frutos Rojos',
            'stock' => 15,
            'disponible'=> true,
            'precioCompra'=> '3.00',
            'imagen_url' => 'postre/po02.png'
        ]);

        Comida::create([
            'cod' => 'C0041',
            'descripcion' => 'Tarta de queso con base de galleta y cobertura de frutos rojos'
        ]);

        // Helado de Stracciatella - po03
        Producto::create([
            'cod' => 'C0042',
            'pvp' => 4.50,
            'nombre' => 'Helado Stracciatella',
            'stock' => 25,
            'disponible'=> true,
            'precioCompra'=> '2.00',
            'imagen_url' => 'postre/po03.png'
        ]);

        Comida::create([
            'cod' => 'C0042',
            'descripcion' => 'Helado artesanal de vainilla con trocitos de chocolate negro'
        ]);

        // Helado de Chocolate con Avellanas - po04
        Producto::create([
            'cod' => 'C0043',
            'pvp' => 4.95,
            'nombre' => 'Helado de Chocolate y Avellanas',
            'stock' => 20,
            'disponible'=> true,
            'precioCompra'=> '2.50',
            'imagen_url' => 'postre/po04.png'
        ]);

        Comida::create([
            'cod' => 'C0043',
            'descripcion' => 'Helado de chocolate con sirope de chocolate y avellanas caramelizadas'
        ]);
    }
}