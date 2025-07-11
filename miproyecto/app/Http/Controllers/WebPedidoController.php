<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class WebPedidoController extends Controller
{
    /**
     * Mostrar el carrito actual del usuario
     */
    public function viewCart()
    {
        try {
            $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
            
            // Obtener o crear el carrito actual
            $carrito = Pedido::carritoActual($usuario->id)->with('lineasPedido.producto')->first();
            
            if (!$carrito) {
                // Crear un nuevo carrito si no existe
                $carrito = new Pedido();
                $carrito->cod = Pedido::generarSiguienteCodigo();
                $carrito->fecha = now();
                $carrito->estado = Pedido::ESTADO_EN_CARRITO;
                $carrito->usuario_id = $usuario->id;
                $carrito->save();
            }

            //Prepara los datos del cliente
            $cliente = (object)[
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'telefono' => $usuario->telefono
            ];
            
            // Datos del footer
            $footer = $this->getFooterData();
            
            return view('carrito', compact('carrito', 'footer','cliente'))
                ->with('success', 'Carrito cargado con éxito');
            
        } catch (Exception $e) {
            return redirect()->route('producto.show')
                ->with('error', 'Error al cargar el carrito: ' . $e->getMessage());
        }
    }

    /**
     * Procesar el checkout del carrito
     */
    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
            $carrito = Pedido::carritoActual($usuario->id)->with('lineasPedido')->firstOrFail();
            
            // Validar que el carrito tenga items
            if ($carrito->lineasPedido->count() == 0) {
                throw new Exception('El carrito está vacío');
            }
            
            // Validar datos de pago
            $request->validate([
                'payment_method' => 'required|in:tarjeta,paypal,efectivo',
                'card_number' => 'required_if:payment_method,tarjeta',
                'expiry' => 'required_if:payment_method,tarjeta',
                'cvv' => 'required_if:payment_method,tarjeta',
            ]);
            
            
            // Cambiar el estado del pedido a "preparando"
            $carrito->cambiarEstado(Pedido::ESTADO_PREPARANDO);
            
            DB::commit();
            
            // Redirigir a la página de confirmación con el ID del pedido
            return redirect()->route('carrito.confirmacion', ['pedidoId' => $carrito->cod])
                ->with('success', 'Pedido realizado con éxito. Tu pedido está siendo preparado.');
                
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar datos de envío
     */
    public function updateShippingDetails(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email',
                'telefono' => 'required|string|max:20',
                'direccion' => 'required|string|max:255',
                'ciudad' => 'required|string|max:100',
                'cp' => 'required|string|max:10',
                'instrucciones' => 'nullable|string|max:500'
            ]);
            
            // Aquí podrías guardar los datos de envío en la sesión o en la BD
            session(['shipping_details' => $request->all()]);
            
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Obtener datos del footer
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

    /**
     * Mostrar confirmación del pedido
     */
    public function confirmacion($pedidoId)
    {
        try {
            $usuario = Usuario::where('email', Auth::user()->email)->firstOrFail();
            
            // Buscar el pedido con sus líneas y productos
            $pedido = Pedido::where('cod', $pedidoId)
                ->where('usuario_id', $usuario->id)
                ->with('lineasPedido.producto', 'usuario')
                ->firstOrFail();
            
            // Preparar datos del mensaje
            $mensaje = (object)[
                'titulo' => '¡Gracias por tu pedido!',
                'subtitulo' => 'Te enviaremos una confirmación por email a ' . $usuario->email
            ];
            
            // Datos del footer
            $footer = $this->getFooterData();
            
            return view('confirmarpedido', compact('pedido', 'mensaje', 'footer'));
            
        } catch (Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Error al mostrar la confirmación del pedido');
        }
    }
}