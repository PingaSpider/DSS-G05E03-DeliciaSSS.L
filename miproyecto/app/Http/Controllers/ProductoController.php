<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Menu;
use App\Models\MenuProducto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProductoController
{
    protected $modelClass = Producto::class;
    protected $viewPrefix = 'producto';
    protected $routePrefix = 'productos';
    protected $requiredFields = ['nombre', 'pvp', 'stock', 'precioCompra'];

 
    public function create_get()
    {
        return view('producto.create');
    }

   
    public function create_post(Request $request)
    {
        // Generar código automático según el tipo
        $cod = $this->generarCodigoAutomatico($request->tipo);
        
        $producto = new Producto();
        $producto->cod = $cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        // Crear el producto específico según su tipo
        if ($request->tipo == 'C') {
            $comida = new Comida();
            $comida->cod = $cod;
            $comida->descripcion = $request->descripcion;
            $comida->save();
        } elseif ($request->tipo == 'B') {
            $bebida = new Bebida();
            $bebida->cod = $cod;
            $bebida->tamanio = $request->tamanio;
            $bebida->tipoBebida = $request->tipoBebida;
            $bebida->save();
        } elseif ($request->tipo == 'M') {
            $menu = new Menu();
            $menu->cod = $cod;
            $menu->descripcion = $request->descripcion_menu;
            $menu->save();
            
            // Agregar productos al menú
            if ($request->has('menu_productos')) {
                foreach ($request->menu_productos as $item) {
                    $menuProducto = new MenuProducto();
                    $menuProducto->menu_cod = $cod;
                    $menuProducto->producto_cod = $item['cod'];
                    $menuProducto->cantidad = $item['cantidad'];
                    $menuProducto->descripcion = $item['descripcion'] ?? '';
                    $menuProducto->save();
                }
            }
        }

        return view('producto.show', ['producto' => $producto]);
    }

    public function show_get($cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            
            // Determinar el tipo de producto basado en el prefijo del código
            $tipo = substr($cod, 0, 1);
            $datos_adicionales = null;
            
            if ($tipo == 'C') {
                $datos_adicionales = Comida::findOrFail($cod);
                $view = 'producto.show';
                return view($view, ['producto' => $producto, 'comida' => $datos_adicionales]);
            } elseif ($tipo == 'B') {
                $datos_adicionales = Bebida::findOrFail($cod);
                $view = 'producto.show';
                return view($view, ['producto' => $producto, 'bebida' => $datos_adicionales]);
            } elseif ($tipo == 'M') {
                $datos_adicionales = Menu::findOrFail($cod);
                // Obtener los productos del menú
                $menuProductos = MenuProducto::where('menu_cod', $cod)
                    ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
                    ->select('menu_producto.*', 'productos.nombre', 'productos.pvp')
                    ->get();
                $view = 'producto.show';
                return view($view, [
                    'producto' => $producto, 
                    'menu' => $datos_adicionales,
                    'menuProductos' => $menuProductos
                ]);
            } else {
                $view = 'producto.show';
                return view($view, ['producto' => $producto]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "producto cod = $cod not found"], 404);
        }
    }

   
    public function show_post(Request $request)
    {
        $cod = $request->cod;
        try {
            $producto = Producto::findOrFail($cod);
            return view('producto.show', ['producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "producto cod = $cod not found"], 404);
        }
    }
    
 
    public function store(Request $request)
    {
        try {
            // Validación básica para todos los productos
            $validacion = [
                'tipo' => 'required|in:C,B,M',
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
            ];
            
            // Validaciones específicas según el tipo
            if ($request->tipo == 'C') {
                $validacion['descripcion'] = 'required';
            } elseif ($request->tipo == 'B') {
                $validacion['tamanio'] = 'required';
                $validacion['tipoBebida'] = 'required';
            } elseif ($request->tipo == 'M') {
                $validacion['descripcion_menu'] = 'required';
                // Validar que hay al menos un producto en el menú
                if (!$request->has('menu_productos') || empty($request->menu_productos)) {
                    return back()->withInput()
                        ->with('error', 'El menú debe tener al menos un producto');
                }
            }
            
            $validator = $request->validate($validacion);

            // Iniciar transacción de base de datos
            DB::beginTransaction();
            
            try {
                // Generar código automático según el tipo
                $cod = $this->generarCodigoAutomatico($request->tipo);
                
                // Crear el producto base
                $producto = new Producto();
                $producto->cod = $cod;
                $producto->pvp = $request->pvp;
                $producto->nombre = $request->nombre;
                $producto->stock = $request->stock;
                $producto->precioCompra = $request->precioCompra;
                $producto->save();
                
                // Crear el producto específico según su tipo
                if ($request->tipo == 'C') {
                    $comida = new Comida();
                    $comida->cod = $cod;
                    $comida->descripcion = $request->descripcion;
                    $comida->save();
                } elseif ($request->tipo == 'B') {
                    $bebida = new Bebida();
                    $bebida->cod = $cod;
                    $bebida->tamanyo = $request->tamanio;
                    $bebida->tipoBebida = $request->tipoBebida;
                    $bebida->save();
                } elseif ($request->tipo == 'M') {
                    $menu = new Menu();
                    $menu->cod = $cod;
                    $menu->descripcion = $request->descripcion_menu;
                    $menu->save();
                    
                    // Agregar productos al menú
                    if ($request->has('menu_productos')) {
                        foreach ($request->menu_productos as $item) {
                            $menuProducto = new MenuProducto();
                            $menuProducto->menu_cod = $cod;
                            $menuProducto->producto_cod = $item['cod'];
                            $menuProducto->cantidad = $item['cantidad'];
                            $menuProducto->descripcion = $item['descripcion'] ?? '';
                            $menuProducto->save();
                        }
                    }
                }
                
                // Confirmar la transacción
                DB::commit();
                
                return redirect()->route('productos.paginate')
                    ->with('success', 'Producto creado exitosamente con código: ' . $cod);
                    
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function paginate(Request $request)
    {
        $query = Producto::query();
        
        // Filtrar por búsqueda si existe
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('cod', 'like', "%{$search}%")
                ->orWhere('nombre', 'like', "%{$search}%");
        }
        
        // Ordenar resultados
        $sortBy = $request->get('sort_by', 'cod');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Lista de campos permitidos para ordenar
        $allowedSortFields = ['cod', 'nombre', 'pvp', 'stock', 'precioCompra'];
        
        // Verificar que el campo de ordenación sea válido
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('cod', 'asc');
        }
        
        $productos = $query->paginate(10);
        
        return view('producto.paginate', compact('productos'));
    }

    public function edit(Request $request, $cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            
            // Determinar el tipo de producto basado en el prefijo del código
            $tipo = substr($cod, 0, 1);
            $datos_adicionales = null;
            
            if ($tipo == 'C') {
                $datos_adicionales = Comida::findOrFail($cod);
                return view('producto.edit', ['producto' => $producto, 'comida' => $datos_adicionales, 'tipo' => 'C']);
            } elseif ($tipo == 'B') {
                $datos_adicionales = Bebida::findOrFail($cod);
                return view('producto.edit', ['producto' => $producto, 'bebida' => $datos_adicionales, 'tipo' => 'B']);
            } elseif ($tipo == 'M') {
                $menu = Menu::findOrFail($cod);
                
                // Obtener los productos que componen el menú
                $menuProductos = MenuProducto::where('menu_cod', $cod)
                    ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
                    ->select('menu_producto.*', 'productos.nombre', 'productos.pvp')
                    ->get();
                
                return view('producto.edit', [
                    'producto' => $producto, 
                    'menu' => $menu,
                    'menuProductos' => $menuProductos,
                    'tipo' => 'M'
                ]);
            } else {
                return view('producto.edit', ['producto' => $producto]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "producto cod = $cod not found"], 404);
        }
    }


    public function update(Request $request, $cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            
            // Determinar el tipo de producto basado en el prefijo del código
            $tipo = substr($cod, 0, 1);
            
            // Validación básica para todos los productos
            $validacion = [
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
            ];
            
            // Validaciones específicas según el tipo
            if ($tipo == 'C') {
                $validacion['descripcion'] = 'required';
            } elseif ($tipo == 'B') {
                $validacion['tamanio'] = 'required';
                $validacion['tipoBebida'] = 'required';
            } elseif ($tipo == 'M') {
                $validacion['descripcion_menu'] = 'required';
            }
            
            $request->validate($validacion);
            
            // Iniciar transacción de base de datos
            DB::beginTransaction();
            
            try {
                // Actualizar el producto base
                $producto->pvp = $request->pvp;
                $producto->nombre = $request->nombre;
                $producto->stock = $request->stock;
                $producto->precioCompra = $request->precioCompra;
                $producto->save();
                
                // Actualizar el producto específico según su tipo
                if ($tipo == 'C') {
                    $comida = Comida::findOrFail($cod);
                    $comida->descripcion = $request->descripcion;
                    $comida->save();
                } elseif ($tipo == 'B') {
                    $bebida = Bebida::findOrFail($cod);
                    $bebida->tamanio = $request->tamanio;
                    $bebida->tipoBebida = $request->tipoBebida;
                    $bebida->alcoholica = $request->has('alcoholica') ? 1 : 0;
                    $bebida->save();
                } elseif ($tipo == 'M') {
                    $menu = Menu::findOrFail($cod);
                    $menu->descripcion = $request->descripcion_menu;
                    $menu->save();
                }
                
                // Confirmar la transacción
                DB::commit();
                
                return redirect()->route($this->routePrefix . '.paginate')
                    ->with('success', 'Producto actualizado exitosamente');
                    
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                throw $e;
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route($this->routePrefix . '.paginate')
                ->with('error', 'Producto no encontrado');
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . '.paginate')
                ->with('error', $e->getMessage());
        }
    }

    // Función para eliminar un producto
    public function destroy($cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            $tipo = substr($cod, 0, 1);
            
            // Iniciar transacción de base de datos
            DB::beginTransaction();
            
            try {
                // Eliminar primero el producto específico
                if ($tipo == 'C') {
                    Comida::where('cod', $cod)->delete();
                } elseif ($tipo == 'B') {
                    Bebida::where('cod', $cod)->delete();
                } elseif ($tipo == 'M') {
                    // Eliminar primero las relaciones con los productos
                    MenuProducto::where('menu_cod', $cod)->delete();
                    Menu::where('cod', $cod)->delete();
                }
                
                // Finalmente eliminar el producto base
                $producto->delete();
                
                // Confirmar la transacción
                DB::commit();
                
                return redirect()->route($this->routePrefix . '.paginate')
                    ->with('success', 'Producto eliminado exitosamente');
                    
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                throw $e;
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route($this->routePrefix . '.paginate')
                ->with('error', 'Producto no encontrado');
        } catch (Exception $e) {
            return redirect()->route($this->routePrefix . '.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function delete($cod)
    {
        return $this->destroy($cod);
    }

    public function search(Request $request)
    {
        $nombre = $request->nombre;
        $productos = Producto::where('nombre', 'like', "%$nombre%")->get();
        return view('producto.search', ['productos' => $productos]);
    }
    
    public function verificarCodigo(Request $request)
    {
        $cod = $request->input('cod');
        $productoCod = $request->input('productoCod');
        
        $exists = Producto::where('cod', $cod)
            ->where('cod', '!=', $productoCod)
            ->exists();
            
        return response()->json(['exists' => $exists]);
    }
    
  
    protected function generarCodigoAutomatico($tipo)
    {
        // Obtener el último código con el prefijo indicado
        $ultimoCodigo = DB::table('productos')
            ->where('cod', 'like', $tipo . '%')
            ->orderBy('cod', 'desc')
            ->value('cod');
        
        if ($ultimoCodigo) {
            // Extraer la parte numérica del código
            $numero = (int) substr($ultimoCodigo, 1);
            // Incrementar en 1 y formatear con ceros a la izquierda
            $nuevoCodigo = $tipo . str_pad($numero + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Si no hay códigos previos, empezar desde 0001
            $nuevoCodigo = $tipo . '0001';
        }
        
        return $nuevoCodigo;
    }
  
    public function buscarProductos(Request $request)
    {
        $search = $request->input('q', ''); // Hacemos que la búsqueda sea opcional
        $tipo = $request->input('tipo'); // B para bebidas, C para comidas
        
        // Consulta base
        $query = Producto::select('cod', 'nombre', 'pvp');
        
        // Filtrar por tipo si se especifica
        if (!empty($tipo) && in_array($tipo, ['B', 'C'])) {
            $query->where('cod', 'like', $tipo . '%');
        } else {
            // Si no se especifica tipo, buscar solo comidas y bebidas
            $query->where(function($q) {
                $q->where('cod', 'like', 'C%')
                ->orWhere('cod', 'like', 'B%');
            });
        }
        
        // Aplicar filtro de búsqueda si existe
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('cod', 'like', "%{$search}%");
            });
        }
        
        // Ordenar por nombre y limitar a 50 resultados para no sobrecargar
        $productos = $query->orderBy('nombre')->limit(50)->get();
        
        return response()->json($productos);
    }

  
    public function getDatosEspecificos(Request $request, $cod)
    {
        try {
            $tipo = $request->input('tipo');
            
            if ($tipo === 'comida') {
                $comida = Comida::findOrFail($cod);
                return response()->json([
                    'success' => true,
                    'comida' => $comida
                ]);
            } elseif ($tipo === 'bebida') {
                $bebida = Bebida::findOrFail($cod);
                return response()->json([
                    'success' => true,
                    'bebida' => $bebida
                ]);
            } elseif ($tipo === 'menu') {
                $menu = Menu::findOrFail($cod);
                
                // Opcionalmente, puedes incluir los productos del menú
                $menuProductos = MenuProducto::where('menu_cod', $cod)
                    ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
                    ->select('menu_producto.*', 'productos.nombre', 'productos.pvp')
                    ->get();
                    
                return response()->json([
                    'success' => true,
                    'menu' => $menu,
                    'menuProductos' => $menuProductos
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tipo de producto no válido'
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], 500);
        }
    }
}

//Entrega2