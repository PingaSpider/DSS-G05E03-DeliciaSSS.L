<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Producto;
class ProductoController extends Controller
{
   
    public function create()
    {
        return view('producto.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|unique:productos',
            'pvp' => 'required',
            'nombre' => 'required',
            'stock' => 'required',
            'precioCompra' => 'required',
        ]);


        $producto = new Producto();

        $producto->cod = $request->cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;


        $producto->save();

        return "Producto creado exitosamente";
    }

    //Funcion de modificar
    public function edit($cod)
    {
        $producto = Producto::findOrFail($cod);
        return view('producto.edit', compact('producto'));
    }

    //Funcion de actualizar
    public function update(Request $request, $cod)
    {
        $request->validate([
            'pvp' => 'required',
            'nombre' => 'required',
            'stock' => 'required',
            'precioCompra' => 'required',
        ]);

        $producto = Producto::findOrFail($cod);

        $producto->pvp = $request->pvp;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;

        $producto->save();

        return "Producto actualizado exitosamente";
    }

    //Funcion de eliminar
    public function destroy($cod)
    {
        $producto = Producto::findOrFail($cod);
        $producto->delete();
        return "Producto eliminado exitosamente";
    }

}