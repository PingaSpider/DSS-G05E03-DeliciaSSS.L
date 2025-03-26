<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class ProductoController extends ProductoBaseController
{
    protected $modelClass = Producto::class;
    protected $viewPrefix = 'producto';
    protected $routePrefix = 'productos';
    protected $requiredFields = ['nombre', 'pvp', 'stock', 'precioCompra'];

    /**
     * Constructor específico para productos genéricos
     */
    public function create_get()
    {
        return view('producto.create');
    }

    public function create_post(Request $request)
    {
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        return view('producto.show', ['producto' => $producto]);
    }

    public function show_get($cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            return view('producto.show', ['producto' => $producto]);
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
            $validator = $request->validate([
                'cod' => 'required|unique:productos',
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
            ]);

            $producto = new Producto();
            $producto->cod = $request->cod;
            $producto->pvp = $request->pvp;
            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->precioCompra = $request->precioCompra;
            $producto->save();

            return redirect()->route('productos.paginate')
                ->with('success', 'Producto creado exitosamente');
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perpage', 10);
        
        $query = Producto::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('cod', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%");
            });
        }
        
        $productos = $query->paginate($perPage);
        
        return view('producto.paginate', ['productos' => $productos]);
    }

    public function edit(Request $request, $cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            return view('producto.edit', ['producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "producto cod = $cod not found"], 404);
        }
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, $cod)
    {
        try {
            $producto = Producto::findOrFail($cod);
            
            $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
            ]);

            $producto->pvp = $request->pvp;
            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->precioCompra = $request->precioCompra;
            $producto->save();

            return redirect()->route($this->routePrefix . '.paginate')
                ->with('success', 'Producto actualizado exitosamente');
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
            $producto->delete();
            
            return redirect()->route($this->routePrefix . '.paginate')
                ->with('success', 'Producto eliminado exitosamente');
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
}