<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Producto;
use App\Models\Menu;


class MenuController extends Controller
{
    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|unique:menus',
            'descripcion' => 'required',
        ]);
        

        //Crear un producto con el codigo del menu
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->pvp = 0;
        $producto->nombre = $request->descripcion;
        $producto->stock = 0;
        $producto->precioCompra = 0;
        $producto->save();
        
        //Crear el menu asociado al codigo del producto creado
        $menu = new Menu();
        $menu->cod = producto->cod;
        $menu->descripcion = $request->descripcion;

        
        //Aosciar productos al menu
        $menu->productos()->sync($request->productos);

        //Guardar el menu
        $menu->save();

        return "Menu creado exitosamente";

    }

    //Funcion de modificar
    public function edit($cod)
    {
        $menu = Menu::findOrFail($cod);
        return view('menu.edit', compact('menu'));
    }

    //Funcion de actualizar
    public function update(Request $request, $cod)
    {
        $request->validate([
            'descripcion' => 'required',
        ]);

        $menu = Menu::findOrFail($cod);

        $menu->descripcion = $request->descripcion;

        //Actualizar los productos asociados al menu
        $menu->productos()->sync($request->productos);

        $menu->save();

        return "Menu actualizado exitosamente";
    }


    //Funcion de eliminar
    public function destroy($cod)
    {
        $menu = Menu::findOrFail($cod);
        $menu->delete();
        return "Menu eliminado exitosamente";
    }

}

