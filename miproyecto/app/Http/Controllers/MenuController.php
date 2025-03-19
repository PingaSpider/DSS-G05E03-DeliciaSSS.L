<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Producto;

class MenuController extends ProductoBaseController
{
    protected $modelClass = Menu::class;
    protected $viewPrefix = 'menu';
    protected $routePrefix = 'menus';
    protected $requiredFields = ['descripcion', 'productos'];

    /**
     * Constructor para menús
     */
    public function __construct()
    {
        // Agregar reglas de validación específicas para Menu
        $this->baseValidationRules['descripcion'] = 'required';
        $this->baseValidationRules['productos'] = 'sometimes|array';
        
        // Asegurar que el código sea único en la tabla de menus
        $this->baseValidationRules['cod'] = 'required|unique:menus';

        $this->baseValidationRules['pvp'] = 'sometimes|numeric';
        $this->baseValidationRules['stock'] = 'sometimes|integer';
        $this->baseValidationRules['precioCompra'] = 'sometimes|numeric';
        
        parent::__construct();
    }

    /**
     * Mostrar formulario de creación con lista de productos disponibles
     */
    public function create()
    {
        $productos = Producto::whereNotIn('cod', function($query) {
            $query->select('cod')->from('menus');
        })->get();
        
        return view($this->viewPrefix . '.create', compact('productos'));
    }

    /**
     * Guardar un nuevo menú
     */
    public function store(Request $request)
    {
        // Validar los campos
        $validatedData = $request->validate($this->baseValidationRules);

        // Crear un producto base con precio y stock especiales para menús
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->nombre = $request->descripcion;
        
        
        $producto->pvp = $request->pvp ?? 0; // Precio del menú
        $producto->stock = 0; // Los menús no tienen stock físico
        $producto->precioCompra = 0; // Los menús no tienen precio de compra directo
        $producto->save();

        // Crear el menú asociado
        $menu = new Menu();
        $menu->cod = $producto->cod;
        $menu->descripcion = $request->descripcion;
        $menu->save();

        // Asociar los productos al menú si se han seleccionado
        if ($request->has('productos') && is_array($request->productos)) {
            $menu->productos()->attach($request->productos);
        }

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Menú creado exitosamente');
    }

    /**
     * Mostrar formulario de edición con productos disponibles
     */
    public function edit($cod)
    {
        $menu = Menu::findOrFail($cod);
        
        // Obtener todos los productos que no son menús
        $productos = Producto::whereNotIn('cod', function($query) {
            $query->select('cod')->from('menus');
        })->get();
        
        // Obtener los IDs de los productos ya asociados al menú
        $productosSeleccionados = $menu->productos->pluck('cod')->toArray();
        
        return view($this->viewPrefix . '.edit', compact('menu', 'productos', 'productosSeleccionados'));
    }

    /**
     * Actualizar un menú existente
     */
    public function update(Request $request, $cod)
    {
        // Validar solo los campos específicos del menú
        $request->validate([
            'descripcion' => 'required',
            'productos' => 'sometimes|array',
        ]);

        // Obtener el menú
        $menu = Menu::findOrFail($cod);
        $menu->descripcion = $request->descripcion;
        $menu->save();

        // Actualizar productos asociados al menú
        if ($request->has('productos')) {
            $menu->productos()->sync($request->productos);
            
            // Recalcular el precio del menú basado en los productos asociados
            $pvpTotal = 0;
            foreach ($request->productos as $productoId) {
                $prod = Producto::find($productoId);
                if ($prod) {
                    $pvpTotal += $prod->pvp;
                }
            }
            // Aplicar descuento por ser menú
            $pvpTotal = $pvpTotal * 0.9; // 10% de descuento
            
            // Actualizar el producto base
            $producto = Producto::find($cod);
            if ($producto) {
                $producto->nombre = $request->descripcion;
                $producto->pvp = $pvpTotal;
                $producto->save();
            }
        }

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Menú actualizado exitosamente');
    }

    /**
     * Eliminar un menú
     * Sobreescribimos el método destroy para desasociar primero los productos
     */
    public function destroy($cod)
    {
        $menu = Menu::findOrFail($cod);
        
        // Desasociar todos los productos antes de eliminar
        $menu->productos()->detach();
        
        // Eliminar el menú
        $menu->delete();
        
        // Eliminar el producto base
        $producto = Producto::find($cod);
        if ($producto) {
            $producto->delete();
        }

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Menú eliminado exitosamente');
    }

    /**
     * Mostrar detalles de un menú con sus productos
     */
    public function show($cod)
    {
        $menu = Menu::with('productos')->findOrFail($cod);
        $producto = Producto::find($cod);
        
        return view($this->viewPrefix . '.show', compact('menu', 'producto'));
    }
}