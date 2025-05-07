<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\MenuProducto;
use Illuminate\Support\Facades\DB;

class WebMenuController extends Controller
{
    /**
     * Muestra la página principal del menú
     */
    public function index()
    {
        // Definir categorías para el menú
        $categoriasSecciones = [
            ['id' => 'desayunos', 'nombre' => 'Desayunos', 'titulo' => 'Desayunos', 'items' => $this->getProductosCategoria('desayunos')],
            ['id' => 'combinados', 'nombre' => 'Combinados', 'titulo' => 'Combinados', 'items' => $this->getProductosCategoria('combinados')],
        ];

        // Obtener el menú del día
        $menuDelDia = $this->getMenuDelDia();

        // Productos recomendados
        $recomendados = $this->getProductosRecomendados();

        // Información del footer
        $footer = $this->getFooterInfo();

        return view('menu', compact(
            'categoriasSecciones',
            'menuDelDia',
            'recomendados',
            'footer'
        ));
    }

    /**
     * Obtiene el menú del día
     */
    private function getMenuDelDia()
    {
        // Puedes tener un campo en la tabla Menu que indique que es el menú del día
        // O simplemente obtener un menú específico o el primero
        $menu = Menu::join('productos', 'menus.cod', '=', 'productos.cod')
            ->select('menus.*', 'productos.nombre', 'productos.pvp as precio')
            ->first();

        if (!$menu) {
            // Crear un objeto con datos por defecto si no hay menú
            return (object)[
                'precio' => '12.99€',
                'nota' => 'Se puede elegir 2 platos',
                'cursos' => $this->getMenuDelDiaCursos()
            ];
        }

        // Agregar los cursos al objeto de menú
        $menu->cursos = $this->getMenuDelDiaCursos($menu->cod);
        $menu->nota = 'Se puede elegir 2 platos';

        return $menu;
    }

    /**
     * Obtiene los cursos para el menú del día
     */
    private function getMenuDelDiaCursos($menuCod = null)
    {
        if (!$menuCod) {
            // Devolver datos por defecto
            return [
                ['titulo' => 'Primero a Elegir', 'platos' => [
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto']
                ]],
                ['titulo' => 'Segundo a Elegir', 'platos' => [
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto']
                ]],
                ['titulo' => 'Postre', 'platos' => [
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto']
                ]]
            ];
        }

        // Obtener productos del menú y organizarlos por tipo
        $productos = MenuProducto::where('menu_cod', $menuCod)
            ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
            ->select('menu_producto.*', 'productos.nombre', 'productos.pvp', 'productos.cod')
            ->get();

        $primeros = [];
        $segundos = [];
        $postres = [];

        foreach ($productos as $producto) {
            //dd($producto);
            $cod = $producto->cod;
            //dd($cod);
            $tipo = substr($cod, 0, 1); // C para comidas, B para bebida
            $productoDetalles = Producto::where('cod', $cod)->first(); // Obtener el producto completo por cod

            if ($productoDetalles) {
                $imagen = $productoDetalles->imagen_url;
            } else {
                $imagen = 'assets/images/comida/placeholder.jpg'; 
            }
            $plato = [
                'imagen' => $imagen,
                'descripcion' => $producto->nombre
            ];
            //dd($plato);
            //dd($producto); 
            //dd(vars: $tipo);

            // Determinar la categoría del plato basándose en el código o descripción
            if ($tipo === 'C') {

                $comida = Comida::find($cod);
                if ($comida) {
                    $plato['descripcion'] .= ' - ' . $comida->descripcion;
                }

                // Asignar a primeros o segundos basado en alguna lógica, por ejemplo el precio
                if (strpos(strtolower($producto->nombre), 'postre') !== false || 
                    strpos(strtolower($producto->nombre), 'tarta') !== false ||
                    strpos(strtolower($producto->nombre), 'helado') !== false) {
                    $postres[] = $plato;
                } elseif ($producto->pvp > 10) {
                    $primeros[] = $plato;
                } else {
                    $segundos[] = $plato;
                }
            } elseif ($tipo === 'B') {
                $bebida = Bebida::find($cod);
                if ($bebida) {
                    $plato['descripcion'] .= ' - ' . $bebida->tipoBebida;
                }
                // Las bebidas pueden ir en una categoría aparte o con los primeros
                $segundos[] = $plato;
            } elseif ($tipo === 'P'){
                //dd(vars: $tipo);
                $comida = Comida::find($cod);
                if ($comida) {
                    $plato['descripcion'] .= ' - ' . $comida->descripcion;
                }
                $postres[] = $plato;
                //dd($plato);
            } else {
                // Si no es ni comida ni bebida, lo ponemos en segundos por defecto
                $segundos[] = $plato;
            }
        }

        // Asegurar que haya al menos un elemento en cada categoría
        if (empty($primeros)) {
            $primeros = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Ensalada mixta'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Sopa del día']
            ];
        }
        if (empty($segundos)) {
            $segundos = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Filete de ternera'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Paella valenciana']
            ];
        }
        if (empty($postres)) {
            $postres = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Tarta de chocolate'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Helado variado']
            ];
        }

        return [
            ['titulo' => 'Primero a Elegir', 'platos' => $primeros],
            ['titulo' => 'Segundo a Elegir', 'platos' => $segundos],
            ['titulo' => 'Postre', 'platos' => $postres]
        ];
    }

    /**
     * Obtiene productos recomendados
     */
    private function getProductosRecomendados()
    {
        // Obtener algunos productos destacados o los más vendidos
        // Usando una consulta compatible con todas las versiones de MySQL
        try {
            $productos = Producto::where(function($query) {
                    $query->where('cod', 'like', 'C%')
                          ->orWhere('cod', 'like', 'B%');
                })
                ->orderBy(DB::raw('RAND()'))
                ->limit(4)
                ->get();

            $recomendados = [];
            foreach ($productos as $producto) {
                $recomendados[] = [
                    'imagen' => $producto->imagen_url,
                    'nombre' => $producto->nombre,
                    'rating' => rand(3, 5), // Rating aleatorio para demostración
                    'precio' => $producto->pvp . '€'
                ];
            }
        } catch (\Exception $e) {
            // Si hay un error en la consulta, usar datos por defecto
            $recomendados = [];
        }

        if (empty($recomendados)) {
            // Datos por defecto si no hay productos
            $recomendados = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 3, 'precio' => '13€'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 4, 'precio' => '9.50€'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 5, 'precio' => '7.95€'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 3, 'precio' => '12.50€']
            ];
        }

        return (object)[
            'titulo' => 'Productos recomendados',
            'descripcion' => 'Nuestras delicias más populares seleccionadas especialmente para ti.',
            'productos' => $recomendados
        ];
    }

    /**
     * Obtiene productos para una categoría específica
     */
    private function getProductosCategoria($categoria)
    {
        // Mapeo de categorías a tipos de productos y condiciones
        $categoriaCondiciones = [
            'desayunos' => function($query) {
                $query->where('cod', 'like', 'C%')
                      ->where(function($q) {
                          $q->where('nombre', 'like', '%desayuno%')
                            ->orWhere('nombre', 'like', '%tostada%')
                            ->orWhere('nombre', 'like', '%croissant%');
                      });
            },
            'bebidas' => function($query) {
                $query->where('cod', 'like', 'B%');
            },
            'hamburguesas' => function($query) {
                $query->where('cod', 'like', 'C%')
                      ->where('nombre', 'like', '%hamburguesa%');
            },
            'pizzas' => function($query) {
                $query->where('cod', 'like', 'C%')
                      ->where('nombre', 'like', '%pizza%');
            },
            'combinados' => function($query) {
                $query->where('cod', 'like', 'M%');
            },
            'postres' => function($query) {
                $query->where('cod', 'like', 'C%')
                      ->where(function($q) {
                          $q->where('nombre', 'like', '%postre%')
                            ->orWhere('nombre', 'like', '%tarta%')
                            ->orWhere('nombre', 'like', '%helado%');
                      });
            }
        ];

        $productos = [];

        try {
            if (isset($categoriaCondiciones[$categoria])) {
                $query = Producto::query();
                $categoriaCondiciones[$categoria]($query);
                $items = $query->limit(3)->get();

                foreach ($items as $item) {
                    $descripcion = '';
                    $tipo = substr($item->cod, 0, 1);

                    if ($tipo === 'C') {
                        $comida = Comida::find($item->cod);
                        if ($comida) {
                            $descripcion = $comida->descripcion;
                        }
                    } elseif ($tipo === 'B') {
                        $bebida = Bebida::find($item->cod);
                        if ($bebida) {
                            $descripcion = $bebida->tipoBebida . ' ' . $bebida->tamanyo;
                        }
                    } elseif ($tipo === 'M') {
                        $menu = Menu::find($item->cod);
                        if ($menu) {
                            $descripcion = $menu->descripcion;
                        }
                    }

                    $productos[] = [
                        'imagen' => 'assets/images/comida/placeholder.jpg', //$producto->imagen_url
                        'nombre' => $item->nombre,
                        'descripcion' => $descripcion,
                        'precio' => $item->pvp . '€'
                    ];
                }
            }
        } catch (\Exception $e) {
            // Si hay un error en la consulta, usar datos por defecto
            $productos = [];
        }

        // Si no hay productos para esta categoría, devolver datos por defecto
        if (empty($productos)) {
            switch ($categoria) {
                case 'desayunos':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Tostada con Tomate', 'descripcion' => 'Pan artesanal con tomate rallado y aceite de oliva', 'precio' => '3.50€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Croissant con Jamón y Queso', 'descripcion' => 'Croissant recién horneado con jamón serrano y queso', 'precio' => '4.25€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Café con Bollería', 'descripcion' => 'Café a elegir con bollería del día', 'precio' => '3.75€']
                    ];
                case 'bebidas':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Coca Cola', 'descripcion' => 'Refresco de cola 330ml', 'precio' => '2.50€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Agua Mineral', 'descripcion' => 'Agua mineral 500ml', 'precio' => '1.50€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Cerveza', 'descripcion' => 'Cerveza de barril 330ml', 'precio' => '3.00€']
                    ];
                case 'hamburguesas':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Hamburguesa Clásica', 'descripcion' => 'Carne de ternera, lechuga, tomate y queso', 'precio' => '7.50€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Hamburguesa Especial', 'descripcion' => 'Doble carne, bacon, queso, huevo y salsa especial', 'precio' => '9.50€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Hamburguesa Vegana', 'descripcion' => 'Burger plant-based con aguacate y salsa de yogur', 'precio' => '8.50€']
                    ];
                case 'pizzas':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Pizza Margarita', 'descripcion' => 'Tomate, mozzarella y albahaca', 'precio' => '8.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Pizza Cuatro Quesos', 'descripcion' => 'Mozzarella, gorgonzola, parmesano y fontina', 'precio' => '10.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Pizza Pepperoni', 'descripcion' => 'Tomate, mozzarella y pepperoni', 'precio' => '9.95€']
                    ];
                case 'combinados':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Combo Especial', 'descripcion' => 'Hamburguesa, patatas y bebida a elegir', 'precio' => '9.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Combo Familiar', 'descripcion' => '2 hamburguesas, patatas grandes, 4 nuggets y 2 bebidas', 'precio' => '19.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Combo Pizza', 'descripcion' => 'Pizza mediana, patatas y bebida', 'precio' => '12.95€']
                    ];
                case 'postres':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Tarta de Chocolate', 'descripcion' => 'Tarta casera con chocolate belga', 'precio' => '4.50€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Helado Variado', 'descripcion' => 'Tres bolas de helado a elegir', 'precio' => '3.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Flan de Huevo', 'descripcion' => 'Flan casero con caramelo', 'precio' => '3.50€']
                    ];
                default:
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Producto 1', 'descripcion' => 'Descripción del producto', 'precio' => '5.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Producto 2', 'descripcion' => 'Descripción del producto', 'precio' => '6.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Producto 3', 'descripcion' => 'Descripción del producto', 'precio' => '7.95€']
                    ];
            }
        }

        return $productos;
    }

    /**
     * Obtiene información para el footer
     */
    private function getFooterInfo()
    {
        // En una aplicación real, esto podría venir de la base de datos
        return (object)[
            'direccion' => (object)[
                'titulo' => 'Dirección',
                'calle' => 'Calle Los Dolores, 44',
                'ciudad' => '03110 Alicante'
            ],
            'horarios' => (object)[
                'titulo' => 'Horarios',
                'semana' => 'Lun-Vie: 12:30 - 17:00',
                'finde' => 'Sáb-Dom: 12:30 - 24:00'
            ],
            'contacto' => (object)[
                'titulo' => 'Contáctanos',
                'email' => 'delicias@gmail.com',
                'telefono' => 'Tel: 678-45-20-16'
            ]
        ];
    }
}