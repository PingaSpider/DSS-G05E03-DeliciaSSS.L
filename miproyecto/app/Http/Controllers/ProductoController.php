<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends ProductoBaseController
{
    protected $modelClass = Producto::class;
    protected $viewPrefix = 'producto';
    protected $routePrefix = 'productos';
    protected $requiredFields = ['nombre', 'pvp', 'stock', 'precioCompra'];

    /**
     * Constructor específico para productos genéricos
     */
    public function __construct()
    {
        $this->baseValidationRules['nombre'] = 'required';
        parent::__construct();
    }

    /**
     * Guardar un nuevo producto
     */
    public function store(Request $request)
    {
        // Validar los campos
        $validatedData = $request->validate($this->baseValidationRules);

        // Crear el producto directamente
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, $cod)
    {
        // Eliminar la regla 'required' para el código, ya que no se debe modificar
        $validationRules = array_diff_key($this->baseValidationRules, ['cod' => '']);
        $validatedData = $request->validate($validationRules);

        $producto = Producto::findOrFail($cod);
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    // Función para eliminar un producto
    public function destroy($cod)
    {
        $producto = Producto::findOrFail($cod);
        $producto->delete();
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}