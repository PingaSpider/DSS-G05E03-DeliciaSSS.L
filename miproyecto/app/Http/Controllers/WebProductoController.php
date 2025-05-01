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
        // Si no se proporciona un código, mostrar la lista de productos
        if ($cod === null) {
            $productos = Producto::where('disponible', true)->get();
            
            return view('producto', [
                'productos' => $productos,
                'footer' => $this->getFooterData()
            ]);
        }

        // Buscar el producto por su código
        $producto = Producto::where('cod', $cod)->firstOrFail();

        // Si el producto es una comida, cargar detalles específicos
        if ($producto->comida) {
            $producto->descripcion = $producto->comida->descripcion ?? 'Descripción no disponible';
        }

        // Convertir los atributos para que coincidan con la vista
        $producto->imagen = $producto->imagen ?? asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/especialburguer.png');
        $producto->rating = 5; // Valor por defecto
        $producto->reviews_count = 4; // Valor por defecto
        $producto->precio = $producto->pvp;

        // Buscar productos similares
        $similarProducts = Producto::where('cod', '!=', $cod)
            ->where('disponible', true)
            ->limit(5)
            ->get()
            ->map(function($similar) {
                $similar->rating = 3; // Valor por defecto
                $similar->precio = $similar->pvp;
                return $similar;
            });

        // Reviews de ejemplo
        $reviews = collect([
            (object)[
                'usuario' => (object)[
                    'nombre' => 'John Doe',
                    'avatar' => asset('user-avatar.jpg')
                ],
                'fecha' => now(),
                'rating' => 5,
                'comentario' => 'Excelente producto, muy recomendado.'
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