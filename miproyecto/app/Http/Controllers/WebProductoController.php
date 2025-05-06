<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class WebProductoController extends Controller
{
    /**
     * Muestra la página de detalle de un producto o la lista de productos
     * 
     * @param string|null $cod Código del producto (opcional)
     * @return \Illuminate\View\View
     */
    public function show($cod = null)
    {
        if ($cod === null) {
            // Paginación para todos los productos (14 por página)
            $todosLosProductos = Producto::where('disponible', true)
                ->paginate(10, ['*'], 'todos_page');
            
            // Paginación para cada categoría (14 por página)
            $bebidas = Producto::where('disponible', true)
                ->whereHas('bebida')
                ->paginate(5, ['*'], 'bebidas_page');
            
            $comidas = Producto::where('disponible', true)
                ->whereHas('comida')
                ->paginate(5, ['*'], 'comidas_page');
            
            $menus = Producto::where('disponible', true)
                ->whereHas('menu')
                ->paginate(5, ['*'], 'menus_page');
            
            // Paginación para subcategorías
            $desayunos = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where('nombre', 'like', '%Desayuno%')
                ->paginate(5, ['*'], 'desayunos_page');
            
            $hamburguesas = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where('nombre', 'like', '%Burger%')
                ->paginate(5, ['*'], 'hamburguesas_page');
            
            $pizzas = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where('nombre', 'like', '%Pizza%')
                ->paginate(5, ['*'], 'pizzas_page');
            
            $postres = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where(function($query) {
                    $query->where('nombre', 'like', '%Tarta%')
                        ->orWhere('nombre', 'like', '%Helado%');
                })
                ->paginate(5, ['*'], 'postres_page');
            
            return view('productos-lista', [
                'productos' => $todosLosProductos,
                'bebidas' => $bebidas,
                'comidas' => $comidas,
                'menus' => $menus,
                'desayunos' => $desayunos,
                'hamburguesas' => $hamburguesas,
                'pizzas' => $pizzas,
                'postres' => $postres,
                'footer' => $this->getFooterData()
            ]);
        }

        // Buscar el producto por su código
        $producto = Producto::where('cod', $cod)->firstOrFail();

        // Si el producto es una comida, cargar detalles específicos
        if ($producto->comida) {
            $producto->descripcion = $producto->comida->descripcion ?? 'Descripción no disponible';
        }

        // Generar rating aleatorio entre 3 y 5
        $producto->rating = rand(3, 5);
        $producto->reviews_count = rand(10, 100); // Reviews aleatorios

        // Buscar productos similares (de la misma categoría)
        $similarProducts = Producto::where('cod', '!=', $cod)
            ->where('disponible', true)
            ->limit(5)
            ->get()
            ->map(function($similar) {
                $similar->rating = rand(3, 5); // Rating aleatorio para cada producto similar
                return $similar;
            });

        // Reviews de ejemplo con datos más realistas
        $reviews = collect([
            (object)[
                'usuario' => (object)[
                    'nombre' => 'María García',
                    'avatar' => asset('assets/images/avatars/user-1.jpg')
                ],
                'fecha' => now()->subDays(5),
                'rating' => rand(4, 5),
                'comentario' => 'Excelente producto, muy recomendado. El sabor es increíble y la presentación impecable.'
            ],
            (object)[
                'usuario' => (object)[
                    'nombre' => 'Carlos Martínez',
                    'avatar' => asset('assets/images/avatars/user-2.jpg')
                ],
                'fecha' => now()->subDays(12),
                'rating' => rand(3, 5),
                'comentario' => 'Muy buena calidad. El precio es justo para lo que ofrece. Definitivamente volveré a pedir.'
            ],
            (object)[
                'usuario' => (object)[
                    'nombre' => 'Ana López',
                    'avatar' => asset('assets/images/avatars/user-3.jpg')
                ],
                'fecha' => now()->subDays(20),
                'rating' => rand(4, 5),
                'comentario' => 'Me encantó el servicio y la calidad del producto. Lo recomiendo ampliamente.'
            ]
        ]);

        // Pasar datos a la vista
        return view('producto', [
            'producto' => $producto,
            'similarProducts' => $similarProducts,
            'reviews' => $reviews,
            'footer' => $this->getFooterData()
        ]);
    }

    /**
     * Añadir/quitar de favoritos
     * 
     * @param Request $request
     * @param string $productoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleWishlist(Request $request, $productoId)
    {
        // Implementación de ejemplo para wishlist
        $wishlist = $request->session()->get('wishlist', []);
        
        $key = array_search($productoId, $wishlist);
        
        if ($key !== false) {
            // Quitar de favoritos
            unset($wishlist[$key]);
        } else {
            // Añadir a favoritos
            $wishlist[] = $productoId;
        }
        
        $request->session()->put('wishlist', $wishlist);
        
        return response()->json([
            'success' => true,
            'inWishlist' => $key === false
        ]);
    }

    /**
     * Obtener datos del footer
     * 
     * @return object
     */
    private function getFooterData()
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

    public function buscar(Request $request)
    {
        try {
            // Validar la solicitud
            $request->validate([
                'q' => 'required|string|min:2'
            ]);
            
            $query = $request->input('q');
            
            // Buscar productos que coincidan con la consulta
            $productos = Producto::where('nombre', 'like', "%{$query}%")
                ->where('disponible', true)
                ->with(['comida', 'bebida']) // Cargar relaciones
                ->get(); // Obtener todos los campos
            
            // Transformar los productos para incluir descripción
            $productos = $productos->map(function ($producto) {
                // Crear un nuevo objeto con solo los campos necesarios
                return [
                    'cod' => $producto->cod,
                    'nombre' => $producto->nombre,
                    'precio' => $producto->pvp,
                    'imagen_url' => $producto->imagen_url,
                    'descripcion' => $this->obtenerDescripcion($producto)
                ];
            });
            
            // Devolver JSON
            return response()->json($productos, 200, [
                'Content-Type' => 'application/json; charset=utf-8',
            ]);
        } catch (\Exception $e) {
            // Registrar el error
            \Log::error('Error en búsqueda de productos: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Devolver respuesta de error
            return response()->json([
                'error' => true,
                'message' => 'Error al buscar productos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la descripción del producto según su tipo
     */
    private function obtenerDescripcion($producto)
    {
        if ($producto->comida) {
            return $producto->comida->descripcion ?? '';
        } elseif ($producto->bebida) {
            return ($producto->bebida->tipoBebida ?? '') . ' - ' . ($producto->bebida->tamanyo ?? '');
        }
        
        return '';
    }
}