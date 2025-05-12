<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\MenuProducto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

        // Obtener el día actual
        $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $diaNumero = Carbon::now()->dayOfWeek; // 0 = domingo, 6 = sábado
        $diaActual = $diasSemana[$diaNumero];

        // Array de menús según el día de la semana
        $menusPorDia = [
            0 => 'M0003', // Domingo
            1 => 'M0001', // Lunes
            2 => 'M0002', // Martes
            3 => 'M0003', // Miércoles
            4 => 'M0004', // Jueves
            5 => 'M0001', // Viernes
            6 => 'M0002', // Sábado
        ];

        // Obtener el menú del día actual
        $menuDelDiaCod = $menusPorDia[$diaNumero];
        $menuDelDia = $this->getMenuEspecifico($menuDelDiaCod);

        // Obtener todos los menús para el modal
        $menus = $this->getTodosLosMenus();

        // Preparar los menús de la semana para el modal
        $menusSemana = [];
        foreach ($diasSemana as $index => $dia) {
            $menusSemana[] = [
                'dia' => $dia,
                'menu' => $menusPorDia[$index],
                'isToday' => $index === $diaNumero
            ];
        }

        // Productos recomendados
        $recomendados = $this->getProductosRecomendados();

        // Información del footer
        $footer = $this->getFooterInfo();

        // Pasar el estado de autenticación y el código del menú del día
        $isAuthenticated = \Illuminate\Support\Facades\Auth::check();

        return view('menu', compact(
            'categoriasSecciones',
            'menuDelDia',
            'menus',
            'menusSemana',
            'diaActual',
            'recomendados',
            'footer',
            'isAuthenticated',
            'menuDelDiaCod'
        ));
    }

    /**
     * Obtiene un menú específico por código
     */
    private function getMenuEspecifico($menuCod)
    {
        $menu = Menu::join('productos', 'menus.cod', '=', 'productos.cod')
            ->select('menus.*', 'productos.nombre', 'productos.pvp as precio')
            ->where('menus.cod', $menuCod)
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
     * Obtiene todos los menús con sus cursos
     */
    private function getTodosLosMenus()
    {
        $menus = Menu::join('productos', 'menus.cod', '=', 'productos.cod')
            ->select('menus.*', 'productos.nombre', 'productos.pvp as precio')
            ->get();

        foreach ($menus as $menu) {
            $menu->cursos = $this->getMenuDelDiaCursos($menu->cod);
        }

        return $menus;
    }

    /**
     * Obtiene los cursos para el menú del día
     */
    private function getMenuDelDiaCursos($menuCod = null)
    {
        if (!$menuCod) {
            // Devolver datos por defecto
            return [
                ['titulo' => 'Principal', 'platos' => [
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Plato Principal 1'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Plato Principal 2'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Plato Principal 3']
                ]],
                ['titulo' => 'Bebida', 'platos' => [
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Bebida 1'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Bebida 2']
                ]],
                ['titulo' => 'Postre', 'platos' => [
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Postre 1'],
                    ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Descripción del Producto', 'nombre' => 'Postre 2']
                ]]
            ];
        }

        // Obtener productos del menú y organizarlos por tipo
        $productos = MenuProducto::where('menu_cod', $menuCod)
            ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
            ->select('menu_producto.*', 'productos.nombre', 'productos.pvp', 'productos.cod')
            ->get();

        $principal = [];
        $bebidas = [];
        $postres = [];

        foreach ($productos as $producto) {
            $cod = $producto->cod;
            $tipo = substr($cod, 0, 1); // C para comidas, B para bebida, P para postre
            $productoDetalles = Producto::where('cod', $cod)->first();

            if ($productoDetalles) {
                $imagen = $productoDetalles->imagen_url;
            } else {
                $imagen = 'assets/images/comida/placeholder.jpg'; 
            }
            
            $plato = [
                'imagen' => $imagen,
                'descripcion' => $producto->nombre,
                'nombre' => $producto->nombre
            ];

            // Clasificar según el tipo de producto
            if ($tipo === 'C') {
                $comida = Comida::find($cod);
                if ($comida) {
                    $plato['descripcion'] .= ' - ' . $comida->descripcion;
                }

                // Determinar si es postre por el nombre
                if (strpos(strtolower($producto->nombre), 'postre') !== false || 
                    strpos(strtolower($producto->nombre), 'tarta') !== false ||
                    strpos(strtolower($producto->nombre), 'helado') !== false ||
                    strpos(strtolower($producto->nombre), 'flan') !== false ||
                    strpos(strtolower($producto->nombre), 'natilla') !== false ||
                    strpos(strtolower($producto->nombre), 'tiramisu') !== false) {
                    $postres[] = $plato;
                } else {
                    // Todas las demás comidas van a principal
                    $principal[] = $plato;
                }
            } elseif ($tipo === 'B') {
                $bebida = Bebida::find($cod);
                if ($bebida) {
                    $plato['descripcion'] .= ' - ' . $bebida->tipoBebida;
                }
                $bebidas[] = $plato;
            } elseif ($tipo === 'P'){
                $comida = Comida::find($cod);
                if ($comida) {
                    $plato['descripcion'] .= ' - ' . $comida->descripcion;
                }
                $postres[] = $plato;
            } else {
                // Si no es ni comida ni bebida ni postre, lo ponemos en principal
                $principal[] = $plato;
            }
        }

        // Asegurar que haya al menos un elemento en cada categoría
        if (empty($principal)) {
            $principal = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Filete de ternera', 'nombre' => 'Filete de ternera'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Paella valenciana', 'nombre' => 'Paella valenciana']
            ];
        }
        if (empty($bebidas)) {
            $bebidas = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Agua mineral', 'nombre' => 'Agua mineral'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Refresco de cola', 'nombre' => 'Coca Cola']
            ];
        }
        if (empty($postres)) {
            $postres = [
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Tarta de chocolate', 'nombre' => 'Tarta de chocolate'],
                ['imagen' => 'assets/images/comida/placeholder.jpg', 'descripcion' => 'Helado variado', 'nombre' => 'Helado variado']
            ];
        }

        return [
            ['titulo' => 'Principal', 'platos' => $principal],
            ['titulo' => 'Bebida', 'platos' => $bebidas],
            ['titulo' => 'Postre', 'platos' => $postres]
        ];
    }

    /**
     * Obtiene productos recomendados
     */
    private function getProductosRecomendados()
    {
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
                    'rating' => rand(3, 5),
                    'precio' => $producto->pvp . '€'
                ];
            }
        } catch (\Exception $e) {
            $recomendados = [];
        }

        if (empty($recomendados)) {
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
                        'imagen' => 'assets/images/comida/placeholder.jpg',
                        'nombre' => $item->nombre,
                        'descripcion' => $descripcion,
                        'precio' => $item->pvp . '€'
                    ];
                }
            }
        } catch (\Exception $e) {
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
                case 'combinados':
                    return [
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Combo Especial', 'descripcion' => 'Hamburguesa, patatas y bebida a elegir', 'precio' => '9.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Combo Familiar', 'descripcion' => '2 hamburguesas, patatas grandes, 4 nuggets y 2 bebidas', 'precio' => '19.95€'],
                        ['imagen' => 'assets/images/comida/placeholder.jpg', 'nombre' => 'Combo Pizza', 'descripcion' => 'Pizza mediana, patatas y bebida', 'precio' => '12.95€']
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