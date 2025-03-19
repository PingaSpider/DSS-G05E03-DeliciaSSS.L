<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\LineaPedido;

class LineaPedidoController extends Controller
{
    public function create()
    {
        return view('lineaPedido.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|unique:lineaPedidos',
            'cantidad' => 'required',
            'precio' => 'required',
            'pedido_id' => 'required',
            'producto_id' => 'required',
        ]);

        //Crear la linea de pedido
        $lineaPedido = new LineaPedido();
        $lineaPedido->cod = $request->cod;
        $lineaPedido->cantidad = $request->cantidad;
        $lineaPedido->precio = $request->precio;
        $lineaPedido->pedido_id = $request->pedido_id;
        $lineaPedido->producto_id = $request->producto_id;
        $lineaPedido->save();

        //Asociar la linea de pedido con el pedido
        $pedido = Pedido::findOrFail($request->pedido_id);
        $pedido->lineaPedidos()->attach($lineaPedido->cod);

        //Asociar la linea de pedido con el producto
        $producto = Producto::findOrFail($request->producto_id);
        $producto->lineaPedidos()->attach($lineaPedido->cod);


        return "Linea de pedido creada exitosamente";
    }

    //Funcion de modificar
    public function edit($cod)
    {
        $lineaPedido = LineaPedido::findOrFail($cod);
        return view('lineaPedido.edit', compact('lineaPedido'));
    }

    //Funcion de actualizar
    public function update(Request $request, $cod)
    {
        $request->validate([
            'cantidad' => 'required',
            'precio' => 'required',
            'pedido_id' => 'required',
            'producto_id' => 'required',
        ]);

        $lineaPedido = LineaPedido::findOrFail($cod);
        $lineaPedido->cantidad = $request->cantidad;
        $lineaPedido->precio = $request->precio;
        $lineaPedido->pedido_id = $request->pedido_id;
        $lineaPedido->producto_id = $request->producto_id;
        $lineaPedido->save();

        return "Linea de pedido actualizada exitosamente";
    }

    //Funcion de eliminar
    public function destroy($cod)
    {
        $lineaPedido = LineaPedido::findOrFail($cod);
        $lineaPedido->delete();
        return "Linea de pedido eliminada exitosamente";
    }
}