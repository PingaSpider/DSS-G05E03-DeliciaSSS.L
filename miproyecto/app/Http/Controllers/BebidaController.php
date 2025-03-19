<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bebida;
use App\Models\Producto;

class BebidaController extends ProductoBaseController
{
    protected $modelClass = Bebida::class;
    protected $viewPrefix = 'bebida';
    protected $routePrefix = 'bebidas';
    protected $requiredFields = ['tamanyo', 'tipoBebida'];

    /**
     * Constructor para bebidas
     */
    public function __construct()
    {
        // Agregar reglas de validación específicas para Bebida
        $this->baseValidationRules['tamanyo'] = 'required';
        $this->baseValidationRules['tipoBebida'] = 'required';
        
        // Asegurar que el código sea único en la tabla de bebidas
        $this->baseValidationRules['cod'] = 'required|unique:bebidas';
        
        parent::__construct();
    }

    /**
     * Guardar una nueva bebida
     */
    public function store(Request $request)
    {
        // Validar los campos
        $validatedData = $request->validate($this->baseValidationRules);

        // Crear nombre para el producto base
        $nombre = $request->tipoBebida . ' (' . $request->tamanyo . ')';
        
        // Crear el producto base utilizando el método heredado
        $producto = $this->createProductoBase($request, $nombre);

        // Crear la bebida asociada
        $bebida = new Bebida();
        $bebida->cod = $producto->cod;
        $bebida->tamanyo = $request->tamanyo;
        $bebida->tipoBebida = $request->tipoBebida;
        $bebida->save();

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Bebida creada exitosamente');
    }

    /**
     * Actualizar una bebida existente
     */
    public function update(Request $request, $cod)
    {
        // Validar solo los campos específicos de la bebida
        $request->validate([
            'tamanyo' => 'required',
            'tipoBebida' => 'required',
        ]);

        // Obtener la bebida
        $bebida = Bebida::findOrFail($cod);
        $bebida->tamanyo = $request->tamanyo;
        $bebida->tipoBebida = $request->tipoBebida;
        $bebida->save();

        // Actualizar el nombre del producto base si es necesario
        $nombre = $request->tipoBebida . ' (' . $request->tamanyo . ')';
        $producto = Producto::find($cod);
        if ($producto) {
            $producto->nombre = $nombre;
            
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
            ->with('success', 'Bebida actualizada exitosamente');
    }

    /**
     * Eliminar una bebida específica
     */
    public function destroy($cod){
        // Eliminar la bebida
        $bebida = Bebida::findOrFail($cod);
        $bebida->delete();
        
        // También eliminar el producto base asociado
        $producto = Producto::find($cod);
        if ($producto) {
            $producto->delete();
        }
        
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Bebida eliminada exitosamente');
    }
}