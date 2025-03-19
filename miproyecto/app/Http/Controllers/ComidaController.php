<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Comida;
use App\Models\Producto;

class ComidaController extends Controller
{

    public function create()
    {
        return view('comida.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|unique:comidas',
            'descripcion' => 'required',
        ]);

        //Crear la comida como un producto
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->descripcion;
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        //Crear la comida asociada al codigo del producto creado
        $comida = new Comida();
        $comida->cod = $producto->cod;
        $comida->descripcion = $request->descripcion;

        //Guardar la comida
        $comida->save();

        return "Comida creada exitosamente";
    }

    //Funcion de modificar
    public function edit($cod)
    {
        $comida = Comida::findOrFail($cod);
        return view('comida.edit', compact('comida'));
    }

    //Funcion de actualizar
    public function update(Request $request, $cod)
    {
        $request->validate([
            'descripcion' => 'required',
        ]);

        $comida = Comida::findOrFail($cod);
        $comida->descripcion = $request->descripcion;
        $comida->save();

        return "Comida actualizada exitosamente";
    }

    //Funcion de eliminar
    public function destroy($cod)
    {
        $comida = Comida::findOrFail($cod);
        $comida->delete();

        return "Comida eliminada exitosamente";
    }
}