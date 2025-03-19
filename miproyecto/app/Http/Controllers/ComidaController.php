<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comida;
use App\Models\Producto;

class ComidaController extends ProductoBaseController
{
    protected $modelClass = Comida::class;
    protected $viewPrefix = 'comida';
    protected $routePrefix = 'comidas';
    protected $requiredFields = ['descripcion'];

    /**
     * Constructor para comidas
     */
    public function __construct()
    {
        // Agregar reglas de validación específicas para Comida
        $this->baseValidationRules['descripcion'] = 'required';
        
        // Asegurar que el código sea único en la tabla de comidas
        $this->baseValidationRules['cod'] = 'required|unique:comidas';
        
        parent::__construct();
    }

    /**
     * Guardar una nueva comida
     */
    public function store(Request $request)
    {
        // Validar los campos
        $validatedData = $request->validate($this->baseValidationRules);

        // En este caso, el nombre del producto es la descripción de la comida
        $nombre = $request->descripcion;
        
        // Crear el producto base utilizando el método heredado
        $producto = $this->createProductoBase($request, $nombre);

        // Crear la comida asociada
        $comida = new Comida();
        $comida->cod = $producto->cod;
        $comida->descripcion = $request->descripcion;
        $comida->save();

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Comida creada exitosamente');
    }

    /**
     * Actualizar una comida existente
     */
    public function update(Request $request, $cod)
    {
        // Validar solo los campos específicos de la comida
        $request->validate([
            'descripcion' => 'required',
        ]);

        // Obtener la comida
        $comida = Comida::findOrFail($cod);
        $comida->descripcion = $request->descripcion;
        $comida->save();

        // Actualizar el nombre del producto base (que es la descripción)
        $producto = Producto::find($cod);
        if ($producto) {
            $producto->nombre = $request->descripcion;
            
            // Si también están presentes los demás campos del producto, actualizarlos
            if ($request->filled('pvp')) {
                $producto->pvp = $request->pvp;
            }
            if ($request->filled('stock')) {
                $producto->stock = $request->stock;
            }
            if ($request->filled('precioCompra')) {
                $producto->precioCompra = $request->precioCompra;
            }
            
            $producto->save();
        }

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Comida actualizada exitosamente');
    }
}