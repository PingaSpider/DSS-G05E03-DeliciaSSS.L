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
            // Obtener productos agrupados por categoría
            $bebidas = Producto::where('disponible', true)
                ->whereHas('bebida')
                ->get();
                
            $comidas = Producto::where('disponible', true)
                ->whereHas('comida')
                ->get();
                
            $menus = Producto::where('disponible', true)
                ->whereHas('menu')
                ->get();
            
            // También podríamos clasificar las comidas por subcategorías
            $desayunos = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where('nombre', 'like', '%Desayuno%')
                ->get();
                
            $hamburguesas = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where('nombre', 'like', '%Burger%')
                ->get();
                
            $pizzas = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where('nombre', 'like', '%Pizza%')
                ->get();
                
            $postres = Producto::where('disponible', true)
                ->whereHas('comida')
                ->where(function($query) {
                    $query->where('nombre', 'like', '%Tarta%')
                          ->orWhere('nombre', 'like', '%Helado%');
                })
                ->get();
            
            return view('productos-lista', [
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
     * Añadir un producto al carrito
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $producto = Producto::findOrFail($request->input('producto_id'));
        
        // Lógica para añadir al carrito
        $cart = $request->session()->get('cart', []);
        
        $cart[] = [
            'id' => $producto->cod,
            'nombre' => $producto->nombre,
            'precio' => $producto->pvp
        ];
        
        $request->session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Producto añadido al carrito',
            'cart' => $cart
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
}