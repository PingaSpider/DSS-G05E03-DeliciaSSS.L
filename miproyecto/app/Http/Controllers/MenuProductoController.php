<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuProducto;
use App\Models\Producto;
use App\Models\Menu;

/**
 * Controlador para gestionar las relaciones entre menús y productos
 * No extiende de ProductoBaseController porque no es un tipo de producto,
 * sino una relación entre menú y producto
 */
class MenuProductoController extends Controller
{
    /**
     * Mostrar formulario para agregar un producto a un menú con cantidad personalizada
     */
    public function create()
    {
        $menus = Menu::all();
        $productos = Producto::whereNotIn('cod', function($query) {
            $query->select('cod')->from('menus');
        })->get();
        
        return view('menuProducto.create', compact('menus', 'productos'));
    }

    /**
     * Guardar una nueva relación entre menú y producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_cod' => 'required|exists:menus,cod',
            'producto_id' => 'required|exists:productos,cod',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'required',
        ]);

        // Verificar que el menú existe
        $menu = Menu::findOrFail($request->menu_cod);
        
        // Verificar que el producto existe
        $producto = Producto::findOrFail($request->producto_id);
        
        // Evitar duplicados en la relación
        $existente = MenuProducto::where('menu_cod', $request->menu_cod)
                                ->where('producto_id', $request->producto_id)
                                ->first();
        
        if ($existente) {
            return redirect()->back()
                ->with('error', 'Este producto ya está asociado al menú')
                ->withInput();
        }

        // Crear la relación con cantidad personalizada
        $menuProducto = new MenuProducto();
        $menuProducto->menu_cod = $request->menu_cod;
        $menuProducto->producto_id = $request->producto_id;
        $menuProducto->cantidad = $request->cantidad;
        $menuProducto->descripcion = $request->descripcion;
        $menuProducto->save();

        // Actualizar el precio del menú
        $this->actualizarPrecioMenu($request->menu_cod);

        return redirect()->route('menus.show', $request->menu_cod)
            ->with('success', 'Producto agregado al menú exitosamente');
    }

    /**
     * Mostrar formulario para editar una relación entre menú y producto
     */
    public function edit($id)
    {
        $menuProducto = MenuProducto::findOrFail($id);
        $menus = Menu::all();
        $productos = Producto::whereNotIn('cod', function($query) {
            $query->select('cod')->from('menus');
        })->orWhere('cod', $menuProducto->producto_id)->get();
        
        return view('menuProducto.edit', compact('menuProducto', 'menus', 'productos'));
    }

    /**
     * Actualizar una relación entre menú y producto
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'required',
        ]);

        $menuProducto = MenuProducto::findOrFail($id);
        $oldMenuCod = $menuProducto->menu_cod;
        
        // No permitimos cambiar el menú o producto, solo la cantidad y descripción
        $menuProducto->cantidad = $request->cantidad;
        $menuProducto->descripcion = $request->descripcion;
        $menuProducto->save();

        // Actualizar el precio del menú
        $this->actualizarPrecioMenu($oldMenuCod);

        return redirect()->route('menus.show', $menuProducto->menu_cod)
            ->with('success', 'Relación menú-producto actualizada exitosamente');
    }

    /**
     * Eliminar una relación entre menú y producto
     */
    public function destroy($id)
    {
        $menuProducto = MenuProducto::findOrFail($id);
        $menuCod = $menuProducto->menu_cod;
        $menuProducto->delete();

        // Actualizar el precio del menú
        $this->actualizarPrecioMenu($menuCod);

        return redirect()->route('menus.show', $menuCod)
            ->with('success', 'Producto eliminado del menú exitosamente');
    }

    /**
     * Método para calcular y actualizar el precio de un menú
     * basado en los productos asociados y sus cantidades
     */
    private function actualizarPrecioMenu($menuCod)
    {
        $menu = Menu::findOrFail($menuCod);
        $pvpTotal = 0;
        
        // Calcular el precio total basado en los productos y sus cantidades
        foreach ($menu->menuProductos as $menuProducto) {
            $producto = Producto::find($menuProducto->producto_id);
            if ($producto) {
                $pvpTotal += $producto->pvp * $menuProducto->cantidad;
            }
        }
        
        // Aplicar descuento por ser menú
        $pvpTotal = $pvpTotal * 0.9; // 10% de descuento
        
        // Actualizar el precio del producto base
        $producto = Producto::find($menuCod);
        if ($producto) {
            $producto->pvp = $pvpTotal;
            $producto->save();
        }
        
        return $pvpTotal;
    }
}