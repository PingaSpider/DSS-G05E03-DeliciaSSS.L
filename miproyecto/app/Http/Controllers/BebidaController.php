<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Bebida;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BebidaController extends ProductoController
{
    protected $modelClass = Bebida::class;
    protected $viewPrefix = 'producto.bebida';
    protected $routePrefix = 'bebidas';
    protected $requiredFields = ['nombre', 'pvp', 'stock', 'precioCompra', 'tamanio', 'tipo'];
    
    /**
     * Mostrar formulario para crear una nueva bebida
     */
    public function create()
    {
        return view('producto.bebida.create');
    }
    
    /**
     * Alias para create() para mantener compatibilidad
     */
    public function create_get()
    {
        return $this->create();
    }
    
    /**
     * Almacenar una nueva bebida en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
                'tamanio' => 'required',
                'tipo' => 'required|in:agua,refresco,vino,cerveza',
            ]);
            
            // Generar código automático para bebida (B)
            $cod = $this->generarCodigoAutomatico('B');
            
            // Usamos una transacción para asegurar la consistencia de datos
            DB::beginTransaction();
            
            // Primero creamos el producto base
            $producto = new Producto();
            $producto->cod = $cod;
            $producto->pvp = $request->pvp;
            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->precioCompra = $request->precioCompra;
            $producto->save();
            
            // Luego creamos la bebida que hereda del producto
            $bebida = new Bebida();
            $bebida->cod = $cod;
            $bebida->tamanyo = $request->tamanio;
            $bebida->tipoBebida = $request->tipo;
            $bebida->save();
            
            DB::commit();
            
            return redirect()->route('productos.paginate')
                ->with('success', 'Bebida creada exitosamente con código: ' . $cod);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la bebida: ' . $e->getMessage());
        }
    }
    
    /**
     * Crear una nueva bebida mediante POST (método alternativo para compat.)
     */
    public function create_post(Request $request)
    {
        // Generar código automático para bebida (B)
        $cod = $this->generarCodigoAutomatico('B');
        
        // Crear el producto base
        $producto = new Producto();
        $producto->cod = $cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        // Crear la bebida específica
        $bebida = new Bebida();
        $bebida->cod = $cod;
        $bebida->tamanyo = $request->tamanio;
        $bebida->tipoBebida = $request->tipo;
        $bebida->save();

        return view('producto.bebida.show', ['bebida' => $bebida, 'producto' => $producto]);
    }
    
    /**
     * Mostrar formulario para editar una bebida
     */
    public function edit(Request $request, $cod)
    {
        try {
            $bebida = Bebida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            return view('producto.bebida.edit', ['bebida' => $bebida, 'producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('producto.paginate')
                ->with('error', 'Bebida no encontrada');
        }
    }
    
    /**
     * Actualizar una bebida existente
     */
    public function update(Request $request, $cod)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'pvp' => 'required|numeric',
                'stock' => 'required|numeric',
                'precioCompra' => 'required|numeric',
                'tamanio' => 'required',
                'tipo' => 'required|in:agua,refresco,vino,cerveza',
            ]);
            
            DB::beginTransaction();
            
            $bebida = Bebida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            
            // Actualizar producto base
            $producto->pvp = $request->pvp;
            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->precioCompra = $request->precioCompra;
            $producto->save();
            
            // Actualizar bebida
            $bebida->tamanyo = $request->tamanio;
            $bebida->tipoBebida = $request->tipo;
            $bebida->save();
            
            DB::commit();
            
            return redirect()->route('productos.paginate')
                ->with('success', 'Bebida actualizada exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('productos.paginate')
                ->with('success', 'Bebida actualizada exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la bebida: ' . $e->getMessage());
        }
    }
    
    /**
     * Eliminar una bebida
     */
    public function destroy($cod)
    {
        try {
            DB::beginTransaction();
            
            $bebida = Bebida::findOrFail($cod);
            $bebida->delete();
            
            // El producto base se eliminará automáticamente por la restricción de clave foránea con onDelete('cascade')
            
            DB::commit();
            
            return redirect()->route('productos.paginate')
                ->with('success', 'Bebida eliminada exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('productos.paginate')
                ->with('success', 'Bebida eliminada exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('productos.paginate')
                ->with('error', 'Bebida no encontrada');
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
     * Mostrar detalles de una bebida específica
     */
    public function show($cod)
    {
        try {
            $bebida = Bebida::findOrFail($cod);
            $producto = Producto::findOrFail($cod);
            return view('producto.bebida.show', ['bebida' => $bebida, 'producto' => $producto]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('producto.paginate')
                ->with('error', 'Bebida no encontrada');
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
     * Mostrar detalles de una bebida mediante POST
     */
    public function show_post(Request $request)
    {
        $cod = $request->cod;
        return $this->show($cod);
    }
    
    /**
     * Mostrar lista paginada de bebidas
     */
    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perpage', 10);
        
        // Consulta base que une bebidas con productos
        $query = Bebida::join('productos', 'bebidas.cod', '=', 'productos.cod')
                       ->select('bebidas.*', 'productos.nombre', 'productos.pvp', 'productos.stock', 'productos.precioCompra');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('bebidas.cod', 'like', "%{$search}%")
                  ->orWhere('productos.nombre', 'like', "%{$search}%")
                  ->orWhere('bebidas.tamanyo', 'like', "%{$search}%")
                  ->orWhere('bebidas.tipoBebida', 'like', "%{$search}%");
            });
        }
        
        $bebidas = $query->paginate($perPage);
        
        return view('producto.paginate', ['productos' => $bebidas]);
    }
    
    /**
     * Mantener este método para compatibilidad
     */
    public function index()
    {
        return $this->paginate(request());
    }
    
    /**
     * Verificar si ya existe un código de bebida
     */
    public function verificarCodigo(Request $request)
    {
        $cod = $request->input('cod');
        $bebidaCod = $request->input('bebidaCod');
        
        $exists = Producto::where('cod', $cod)
            ->where('cod', '!=', $bebidaCod)
            ->exists();
            
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Buscar bebidas por nombre
     */
    public function search(Request $request)
    {
        $nombre = $request->nombre;
        $bebidas = Bebida::join('productos', 'bebidas.cod', '=', 'productos.cod')
                         ->where('productos.nombre', 'like', "%$nombre%")
                         ->select('bebidas.*', 'productos.nombre', 'productos.pvp', 'productos.stock', 'productos.precioCompra')
                         ->get();
        return view('producto.bebida.search', ['bebidas' => $bebidas]);
    }
    
    /**
     * Generar código automático para bebidas
     */
    protected function generarCodigoAutomatico($tipo = 'B')
    {
        // Para bebidas, forzamos a que siempre sea B
        $tipo = 'B';
        
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
}