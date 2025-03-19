<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MenuProducto;
use App\Models\Producto;
use App\Models\Menu;


class MenuProductoController extends Controller
{
    public function create()
    {
        return view('menuProducto.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|unique:menuProductos',
            'menu_cod' => 'required',
            'producto_id' => 'required',
            'cantidad' => 'required',
            'descripcion' => 'required',
        ]);

        //Crear el menuProducto
        $menuProducto = new MenuProducto();
        $menuProducto->cod = $request->cod;
        $menuProducto->menu_cod = $request->menu_cod;
        $menuProducto->producto_id = $request->producto_id;
        $menuProducto->cantidad = $request->cantidad;
        $menuProducto->descripcion = $request->descripcion;
        $menuProducto->save();

        //Asociar el menuProducto con el menu
        $menu = Menu::findOrFail($request->menu_cod);
        $menu->menuProductos()->attach($menuProducto->cod);

        //Asociar el menuProducto con el producto
        $producto = Producto::findOrFail($request->producto_id);
        $producto->menuProductos()->attach($menuProducto->cod);


        return "MenuProducto creado exitosamente";
    }

    //Funcion de modificar
    public function edit($cod)
    {
        $menuProducto = MenuProducto::findOrFail($cod);
        return view('menuProducto.edit', compact('menuProducto'));
    }

    //Funcion de actualizar
    public function update(Request $request, $cod)
    {
        $request->validate([
            'menu_cod' => 'required',
            'producto_id' => 'required',
            'cantidad' => 'required',
            'descripcion' => 'required',
        ]);

        $menuProducto = MenuProducto::findOrFail($cod);
        $menuProducto->menu_cod = $request->menu_cod;
        $menuProducto->producto_id = $request->producto_id;
        $menuProducto->cantidad = $request->cantidad;
        $menuProducto->descripcion = $request->descripcion;
        $menuProducto->save();

        return "MenuProducto actualizado exitosamente";
    }

    //Funcion de eliminar
    public function destroy($cod)
    {
        $menuProducto = MenuProducto::findOrFail($cod);
        $menuProducto->delete();
        return "MenuProducto eliminado exitosamente";
    }

}