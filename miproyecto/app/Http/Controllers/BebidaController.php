<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bebida;
use App\Models\Producto;

class BebidaController extends Controller
{

    public function create()
    {
        return view('bebida.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|unique:bebidas',
            'tamayo' => 'required',
            'tipoBebida' => 'required',
        ]);

        //Crear la bebida como un producto
        $producto = new Producto();
        $producto->cod = $request->cod;
        $producto->pvp = $request->pvp;
        $producto->nombre = $request->tipoBebida . ' ' . '(' . $request->tamanyo .')';
        $producto->stock = $request->stock;
        $producto->precioCompra = $request->precioCompra;
        $producto->save();

        //Crear la bebida asociada al codigo del producto creado
        $bebida = new Bebida();
        $bebida->cod = producto->cod;
        $bebida->tamanyo = $request->tamanyo;
        $bebida->tipoBebida = $request->tipoBebida;

        //Guardar la bebida
        $bebida->save();

        return "Bebida creada exitosamente";
    }

    //Funcion de modificar
    public function edit($cod)
    {
        $bebida = Bebida::findOrFail($cod);
        return view('bebida.edit', compact('bebida'));
    }

    //Funcion de actualizar
    public function update(Request $request, $cod)
    {
        $request->validate([
            'tamanyo' => 'required',
            'tipoBebida' => 'required',
        ]);

        $bebida = Bebida::findOrFail($cod);

        $bebida->tamanyo = $request->tamanyo;
        $bebida->tipoBebida = $request->tipoBebida;

        $bebida->save();

        return "Bebida actualizada exitosamente";
    }

    //Funcion de eliminar
    public function destroy($cod)
    {
        $bebida = Bebida::findOrFail($cod);
        $bebida->delete();

        return "Bebida eliminada exitosamente";
    }
}