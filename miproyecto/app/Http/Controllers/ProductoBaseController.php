<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

/**
 * Controlador base para todos los productos
 * Implementa funcionalidad común que será heredada por los controladores específicos
 */
abstract class ProductoBaseController extends Controller
{
    // Propiedades que deben ser definidas por las clases hijas
    protected $modelClass;       // Clase del modelo específico (Bebida, Comida, etc.)
    protected $viewPrefix;       // Prefijo para las vistas
    protected $routePrefix;      // Prefijo para las rutas
    protected $requiredFields;   // Campos requeridos para validación

    /**
     * Reglas de validación base para todos los productos
     */
    protected $baseValidationRules = [
        'cod' => 'required',
        'pvp' => 'required|numeric',
        'stock' => 'required|integer|min:0',
        'precioCompra' => 'required|numeric'
    ];

    /**
     * Método para crear un nuevo producto base
     */
    protected function createProductoBase(Request $request, $nombre)
    {
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        return $producto;
    }

    /**
     * Constructor que verifica que las propiedades requeridas estén definidas
     */
    public function __construct()
    {
        if (!isset($this->modelClass)) {
            throw new \Exception('Debe definir la propiedad $modelClass en la clase hija');
        }

        if (!isset($this->viewPrefix)) {
            throw new \Exception('Debe definir la propiedad $viewPrefix en la clase hija');
        }

        if (!isset($this->routePrefix)) {
            throw new \Exception('Debe definir la propiedad $routePrefix en la clase hija');
        }

        if (!isset($this->requiredFields)) {
            throw new \Exception('Debe definir la propiedad $requiredFields en la clase hija');
        }
    }

    /**
     * Mostrar el formulario para crear un nuevo producto
     */
    public function create()
    {
        return view($this->viewPrefix . '.create');
    }

    /**
     * Método abstracto que debe ser implementado por las clases hijas
     * para crear un producto específico
     */
    abstract public function store(Request $request);

    /**
     * Mostrar el formulario para editar un producto específico
     */
    public function edit($cod)
    {
        $model = $this->modelClass::findOrFail($cod);
        // Obtener también los datos del producto base si es necesario
        $producto = Producto::findOrFail($cod);
        
        return view($this->viewPrefix . '.edit', [
            'modelo' => $model,
            'producto' => $producto
        ]);
    }

    /**
     * Método abstracto que debe ser implementado por las clases hijas
     * para actualizar un producto específico
     */
    abstract public function update(Request $request, $cod);

    /**
     * Eliminar un producto específico y su producto base asociado
     */
    public function destroy($cod)
    {
        $model = $this->modelClass::findOrFail($cod);
        $model->delete();
        
        // También eliminamos el producto base
        $producto = Producto::find($cod);
        if ($producto) {
            $producto->delete();
        }
        
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Eliminado exitosamente');
    }

    /**
     * Listar todos los productos de este tipo
     */
    public function index()
    {
        $items = $this->modelClass::all();
        return view($this->viewPrefix . '.index', ['items' => $items]);
    }

    /**
     * Ver detalle de un producto específico
     */
    public function show($cod)
    {
        $model = $this->modelClass::findOrFail($cod);
        // Obtener también los datos del producto base
        $producto = Producto::findOrFail($cod);
        
        return view($this->viewPrefix . '.show', [
            'modelo' => $model,
            'producto' => $producto
        ]);
    }
}