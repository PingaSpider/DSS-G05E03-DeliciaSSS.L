<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Menu;
use App\Models\MenuProducto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MenuController extends ProductoController
{
    protected $modelClass = Menu::class;
    protected $viewPrefix = 'producto.menu';
    protected $routePrefix = 'menus';
    protected $requiredFields = ['nombre', 'pvp', 'stock', 'precioCompra', 'descripcion'];

    /**
     * Mostrar formulario para crear un nuevo menú
     */
    public function create()
    {
        // Obtener productos de tipo comida (C) y bebida (B) para el menú
        $productos = Producto::where(function($query) {
                $query->where('cod', 'like', 'C%')
                      ->orWhere('cod', 'like', 'B%');
            })
            ->orderBy('nombre')
            ->get();
            
        return view('producto.menu.create', ['productos' => $productos]);
    }
    
    /**
     * Alias para create() para mantener compatibilidad
     */
    public function create_get()
    {
        return $this->create();
    }

    /**
     * Crear un nuevo menú (proceso POST)
     */
    public function create_post(Request $request)
    {
        // Generar código automático para menú (M)
        $cod = $this->generarCodigoAutomatico('M');
        
        // Crear el producto base
        $producto = new Producto();
        $producto->cod = $cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        // Crear el menú específico
        $menu = new Menu();
        $menu->cod = $cod;
        $menu->descripcion = $request->descripcion;
        $menu->save();

        // Asociar productos al menú
        if ($request->has('producto_ids') && $request->has('cantidades')) {
            $productoIds = $request->producto_ids;
            $cantidades = $request->cantidades;
            $descripciones = $request->descripciones ?? [];

            foreach ($productoIds as $index => $productoId) {
                if (isset($cantidades[$index]) && $cantidades[$index] > 0) {
                    $menuProducto = new MenuProducto();
                    $menuProducto->menu_cod = $cod;
                    $menuProducto->producto_cod = $productoId;
                    $menuProducto->cantidad = $cantidades[$index];
                    $menuProducto->descripcion = isset($descripciones[$index]) ? $descripciones[$index] : '';
                    $menuProducto->save();
                }
            }
        }

        // Obtener los productos relacionados para mostrar en la vista
        $menuProductos = MenuProducto::where('menu_cod', $cod)
            ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
            ->select('menu_producto.*', 'productos.nombre', 'productos.pvp')
            ->get();

        return view('producto.menu.show', [
            'menu' => $menu, 
            'producto' => $producto,
            'menuProductos' => $menuProductos
        ]);
    }

    /**
     * Mostrar detalles de un menú específico
     */
    public function show($cod)
    {
        try {
            $menu = Menu::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            
            // Obtener los productos que componen el menú
            $menuProductos = MenuProducto::where('menu_cod', $cod)
                ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
                ->select('menu_producto.*', 'productos.nombre', 'productos.pvp')
                ->get();
                
            return view('producto.menu.show', [
                'menu' => $menu, 
                'producto' => $producto,
                'menuProductos' => $menuProductos
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('productos.paginate')
                ->with('error', 'Menú no encontrado');
        }
    }
    
    /**
     * Alias para show() para mantener compatibilidad
     */
    public function show_get($cod)
    {
        return $this->show($cod);
    }

    /**
     * Mostrar detalles de un menú mediante POST
     */
    public function show_post(Request $request)
    {
        $cod = $request->cod;
        return $this->show($cod);
    }
    
    /**
     * Almacenar un nuevo menú en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
                'descripcion' => 'required',
                'producto_ids' => 'array|required',
                'cantidades' => 'array|required',
            ]);

            // Verificar que hay al menos un producto en el menú
            if (empty($request->producto_ids) || count($request->producto_ids) == 0) {
                return back()->withInput()
                    ->with('error', 'El menú debe tener al menos un producto');
            }

            // Iniciar transacción de base de datos
            DB::beginTransaction();
            
            try {
                // Generar código automático para menú (M)
                $cod = $this->generarCodigoAutomatico('M');
                
                // Crear el producto base
                $producto = new Producto();
                $producto->cod = $cod;
                $producto->pvp = $request->pvp;
                $producto->nombre = $request->nombre;
                $producto->stock = $request->stock;
                $producto->precioCompra = $request->precioCompra;
                $producto->save();

                // Crear el menú
                $menu = new Menu();
                $menu->cod = $cod;
                $menu->descripcion = $request->descripcion;
                $menu->save();

                // Asociar productos al menú
                if ($request->has('producto_ids') && $request->has('cantidades')) {
                    $productoIds = $request->producto_ids;
                    $cantidades = $request->cantidades;
                    $descripciones = $request->descripciones ?? [];

                    foreach ($productoIds as $index => $productoId) {
                        if (isset($cantidades[$index]) && $cantidades[$index] > 0) {
                            $menuProducto = new MenuProducto();
                            $menuProducto->menu_cod = $cod;
                            $menuProducto->producto_cod = $productoId;
                            $menuProducto->cantidad = $cantidades[$index];
                            $menuProducto->descripcion = isset($descripciones[$index]) ? $descripciones[$index] : '';
                            $menuProducto->save();
                        }
                    }
                }
                
                // Confirmar la transacción
                DB::commit();

                return redirect()->route('productos.paginate')
                    ->with('success', 'Menú creado exitosamente con código: ' . $cod);
                
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el menú: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar lista paginada de menús
     */
    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perpage', 10);
        
        // Consulta base que une menus con productos
        $query = Menu::join('productos', 'menus.cod', '=', 'productos.cod')
                     ->select('menus.*', 'productos.nombre', 'productos.pvp', 'productos.stock', 'productos.precioCompra');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('menus.cod', 'like', "%{$search}%")
                  ->orWhere('productos.nombre', 'like', "%{$search}%")
                  ->orWhere('menus.descripcion', 'like', "%{$search}%");
            });
        }
        
        $menus = $query->paginate($perPage);
        
        return view('producto.menu.paginate', ['menus' => $menus]);
    }

    /**
     * Mantener este método para compatibilidad
     */
    public function index()
    {
        return $this->paginate(request());
    }

    /**
     * Mostrar formulario para editar un menú
     */
    public function edit(Request $request, $cod)
    {
        try {
            $menu = Menu::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            
            // Obtener los productos que componen el menú
            $menuProductos = MenuProducto::where('menu_cod', $cod)
                ->join('productos', 'menu_producto.producto_cod', '=', 'productos.cod')
                ->select('menu_producto.*', 'productos.nombre', 'productos.pvp')
                ->get();
            
            // Obtener productos disponibles para agregar al menú (solo comidas y bebidas)
            $productos = Producto::where(function($query) {
                    $query->where('cod', 'like', 'C%')
                          ->orWhere('cod', 'like', 'B%');
                })
                ->orderBy('nombre')
                ->get();
            
            return view('producto.menu.edit', [
                'menu' => $menu, 
                'producto' => $producto,
                'menuProductos' => $menuProductos,
                'productos' => $productos
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('productos.paginate')
                ->with('error', 'Menú no encontrado');
        }
    }

    /**
     * Actualizar un menú existente
     */
    public function update(Request $request, $cod)
    {
        try {
            $menu = Menu::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            
            $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
                'descripcion' => 'required',
                'producto_ids' => 'array',
                'cantidades' => 'array',
            ]);

            // Verificar que hay al menos un producto en el menú
            if (empty($request->producto_ids) || count($request->producto_ids) == 0) {
                return back()->withInput()
                    ->with('error', 'El menú debe tener al menos un producto');
            }

            // Iniciar transacción de base de datos
            DB::beginTransaction();
            
            try {
                // Actualizar producto base
                $producto->pvp = $request->pvp;
                $producto->nombre = $request->nombre;
                $producto->stock = $request->stock;
                $producto->precioCompra = $request->precioCompra;
                $producto->save();
                
                // Actualizar menú
                $menu->descripcion = $request->descripcion;
                $menu->save();
                
                // Eliminar las relaciones anteriores
                MenuProducto::where('menu_cod', $cod)->delete();
                
                // Recrear las relaciones con los productos
                if ($request->has('producto_ids') && $request->has('cantidades')) {
                    $productoIds = $request->producto_ids;
                    $cantidades = $request->cantidades;
                    $descripciones = $request->descripciones ?? [];

                    foreach ($productoIds as $index => $productoId) {
                        if (isset($cantidades[$index]) && $cantidades[$index] > 0) {
                            $menuProducto = new MenuProducto();
                            $menuProducto->menu_cod = $cod;
                            $menuProducto->producto_cod = $productoId;
                            $menuProducto->cantidad = $cantidades[$index];
                            $menuProducto->descripcion = isset($descripciones[$index]) ? $descripciones[$index] : '';
                            $menuProducto->save();
                        }
                    }
                }
                
                // Confirmar la transacción
                DB::commit();

                return redirect()->route('productos.paginate')
                    ->with('success', 'Menú actualizado exitosamente');
                
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                throw $e;
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('productos.paginate')
                ->with('error', 'Menú no encontrado');
        } catch (Exception $e) {
            return redirect()->route('productos.paginate')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Eliminar un menú
     */
    public function destroy($cod)
    {
        try {
            $menu = Menu::findOrFail($cod);
            
            // Iniciar transacción de base de datos
            DB::beginTransaction();
            
            try {
                // Eliminar primero las relaciones con los productos
                MenuProducto::where('menu_cod', $cod)->delete();
                
                // Luego eliminar el menú
                $menu->delete();
                
                // El producto base se eliminará automáticamente por la restricción de clave foránea con onDelete('cascade')
                
                // Confirmar la transacción
                DB::commit();
                
                return redirect()->route('productos.paginate')
                    ->with('success', 'Menú eliminado exitosamente');
                
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                DB::rollBack();
                throw $e;
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('productos.paginate')
                ->with('error', 'Menú no encontrado');
        } catch (Exception $e) {
            return redirect()->route('productos.paginate')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Alias para método destroy
     */
    public function delete($cod)
    {
        return $this->destroy($cod);
    }

    /**
     * Buscar menús por nombre
     */
    public function search(Request $request)
    {
        $nombre = $request->nombre;
        $menus = Menu::join('productos', 'menus.cod', '=', 'productos.cod')
                     ->where('productos.nombre', 'like', "%$nombre%")
                     ->select('menus.*', 'productos.nombre', 'productos.pvp', 'productos.stock', 'productos.precioCompra')
                     ->get();
        return view('producto.menu.search', ['menus' => $menus]);
    }
    
    /**
     * Verificar si ya existe un código de menú
     */
    public function verificarCodigo(Request $request)
    {
        $cod = $request->input('cod');
        $menuCod = $request->input('menuCod');
        
        $exists = Producto::where('cod', $cod)
            ->where('cod', '!=', $menuCod)
            ->exists();
            
        return response()->json(['exists' => $exists]);
    }

    /**
     * Agregar un producto al menú mediante AJAX
     */
    public function agregarProducto(Request $request)
    {
        $menuCod = $request->menu_cod;
        $productoCod = $request->producto_cod;
        $cantidad = $request->cantidad;
        $descripcion = $request->descripcion ?? '';
        
        try {
            // Verificar que existan tanto el menú como el producto
            $menu = Menu::findOrFail($menuCod);
            $producto = Producto::findOrFail($productoCod);
            
            // Verificar si ya existe esta relación
            $existe = MenuProducto::where('menu_cod', $menuCod)
                                ->where('producto_cod', $productoCod)
                                ->exists();
            
            if ($existe) {
                // Actualizar la cantidad si ya existe
                $menuProducto = MenuProducto::where('menu_cod', $menuCod)
                                        ->where('producto_cod', $productoCod)
                                        ->first();
                $menuProducto->cantidad = $cantidad;
                $menuProducto->descripcion = $descripcion;
                $menuProducto->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Producto actualizado en el menú',
                    'menuProducto' => $menuProducto,
                    'action' => 'update'
                ]);
            } else {
                // Crear nueva relación si no existe
                $menuProducto = new MenuProducto();
                $menuProducto->menu_cod = $menuCod;
                $menuProducto->producto_cod = $productoCod;
                $menuProducto->cantidad = $cantidad;
                $menuProducto->descripcion = $descripcion;
                $menuProducto->save();
                
                // Obtener información del producto para la respuesta
                $productoInfo = Producto::select('nombre', 'pvp')->where('cod', $productoCod)->first();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Producto agregado al menú',
                    'menuProducto' => $menuProducto,
                    'productoInfo' => $productoInfo,
                    'action' => 'create'
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menú o producto no encontrado',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el producto: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un producto del menú
     */
    public function eliminarProducto(Request $request)
    {
        $menuCod = $request->menu_cod;
        $productoCod = $request->producto_cod;
        
        try {
            // Verificar que exista la relación
            $menuProducto = MenuProducto::where('menu_cod', $menuCod)
                                    ->where('producto_cod', $productoCod)
                                    ->first();
            
            if (!$menuProducto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado en el menú',
                ], 404);
            }
            
            // Eliminar la relación
            $menuProducto->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del menú',
                'producto_cod' => $productoCod,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Sobrescribir el método generarCodigoAutomatico para menús
     * Esta implementación garantiza que los menús siempre tengan código M
     */
    protected function generarCodigoAutomatico($tipo = 'M')
    {
        // Para menús, forzamos a que siempre sea M
        $tipo = 'M';
        
        return parent::generarCodigoAutomatico($tipo);
    }
}