<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Comida;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ComidaController extends ProductoController
{
    protected $modelClass = Comida::class;
    protected $viewPrefix = 'comida';
    protected $routePrefix = 'comida'; // Singular para coincidir con web.php
    protected $requiredFields = ['nombre', 'pvp', 'stock', 'precioCompra', 'descripcion'];

    
    public function create_get()
    {
        return view('producto.comida.create');
    }

  
    public function create_post(Request $request)
    {
        // Generar código automático para comida (C)
        $cod = $this->generarCodigoAutomatico('C');
        
        // Crear el producto base
        $producto = new Producto();
        $producto->cod = $cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        // Crear la comida específica
        $comida = new Comida();
        $comida->cod = $cod;
        $comida->descripcion = $request->descripcion;
        $comida->save();

        return view('producto.comida.show', ['comida' => $comida, 'producto' => $producto]);
    }

  
    public function show_get($cod)
    {
        try {
            $comida = Comida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            return view('producto.comida.show', ['comida' => $comida, 'producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "comida cod = $cod not found"], 404);
        }
    }

    
    public function show_post(Request $request)
    {
        $cod = $request->cod;
        try {
            $comida = Comida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            return view('producto.comida.show', ['comida' => $comida, 'producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "comida cod = $cod not found"], 404);
        }
    }
    
 
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
                'descripcion' => 'required',
                'imagen_url' => 'required|string|max:255',
            ]);

            // Generar código automático para comida (C)
            $cod = $this->generarCodigoAutomatico('C');

            // Crear el producto base
            $producto = new Producto();
            $producto->cod = $cod;
            $producto->pvp = $request->pvp;
            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->precioCompra = $request->precioCompra;
            $producto->imagen_url = $request->imagen_url; // Asignar la URL de la imagen
            $producto->save();

            // Crear la comida
            $comida = new Comida();
            $comida->cod = $cod;
            $comida->descripcion = $request->descripcion;
            $comida->save();

            return redirect()->route('productos.paginate')
                ->with('success', 'Comida creada exitosamente con código: ' . $cod);
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear la comida: ' . $e->getMessage());
        }
    }

  
    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perpage', 10);
        
        // Consulta base que une comidas con productos
        $query = Comida::join('productos', 'comidas.cod', '=', 'productos.cod')
                       ->select('comidas.*', 'productos.nombre', 'productos.pvp', 'productos.stock', 'productos.precioCompra');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('comidas.cod', 'like', "%{$search}%")
                  ->orWhere('productos.nombre', 'like', "%{$search}%")
                  ->orWhere('comidas.descripcion', 'like', "%{$search}%");
            });
        }
        
        $comidas = $query->paginate($perPage);
        
        return view('producto.paginate', ['productos' => $comidas]);
    }

   
    public function edit(Request $request, $cod)
    {
        try {
            $comida = Comida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            return view('producto.comida.edit', ['comida' => $comida, 'producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "comida cod = $cod not found"], 404);
        }
    }

 
    public function update(Request $request, $cod)
    {
        try {
            $comida = Comida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            
            $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
                'descripcion' => 'required',
            ]);

            // Actualizar producto base
            $producto->pvp = $request->pvp;
            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->precioCompra = $request->precioCompra;
            $producto->save();
            
            // Actualizar comida
            $comida->descripcion = $request->descripcion;
            $comida->save();

            return redirect()->route('productos.paginate')
                ->with('success', 'Comida actualizada exitosamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('productos.paginate')
                ->with('error', 'Comida no encontrada');
        } catch (Exception $e) {
            return redirect()->route('productos.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($cod)
    {
        try {
            $comida = Comida::findOrFail($cod);
            $comida->delete();
            
            // El producto base se eliminará automáticamente por la restricción de clave foránea con onDelete('cascade')
            
            return redirect()->route('productos.paginate')
                ->with('success', 'Comida eliminada exitosamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('productos.paginate')
                ->with('error', 'Comida no encontrada');
        } catch (Exception $e) {
            return redirect()->route('productos.paginate')
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
        $comidas = Comida::join('productos', 'comidas.cod', '=', 'productos.cod')
                         ->where('productos.nombre', 'like', "%$nombre%")
                         ->select('comidas.*', 'productos.nombre', 'productos.pvp', 'productos.stock', 'productos.precioCompra')
                         ->get();
        return view('producto.comida.search', ['comidas' => $comidas]);
    }
    
   
    public function verificarCodigo(Request $request)
    {
        $cod = $request->input('cod');
        $comidaCod = $request->input('comidaCod');
        
        $exists = Producto::where('cod', $cod)
            ->where('cod', '!=', $comidaCod)
            ->exists();
            
        return response()->json(['exists' => $exists]);
    }
    

    protected function generarCodigoAutomatico($tipo = 'C')
    {
        // Para comidas, forzamos a que siempre sea C
        $tipo = 'C';
        
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

    public function getImagesByCategory($category)
    {
        // Validar categoría
        $validCategories = ['hamburguesa', 'pizza', 'desayuno', 'postre', 'comida'];
        if (!in_array($category, $validCategories)) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no válida'
            ], 400);
        }
        
        $images = [];
        $imagePath = public_path("assets/images/comida/{$category}");
        
        if (file_exists($imagePath)) {
            $files = scandir($imagePath);
            foreach($files as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']) && $file != '.' && $file != '..') {
                    $imageName = pathinfo($file, PATHINFO_FILENAME);
                    $images[] = [
                        'name' => $imageName,
                        'url' => asset("assets/images/comida/{$category}/{$file}")
                    ];
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'category' => $category,
            'images' => $images
        ]);
    }
}


//Entrega2