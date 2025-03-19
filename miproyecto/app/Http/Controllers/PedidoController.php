<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pedido;

class PedidoController extends Controller{
    
        public function create()
        {
            return view('pedido.create');
        }
    
        public function store(Request $request)
        {
            $request->validate([
                'cod' => 'required|unique:pedidos',
                'fecha' => 'required',
                'estado' => 'required',
                'usuario_id' => 'required',
            ]);
    
            //Crear el pedido
            $pedido = new Pedido();
            $pedido->cod = $request->cod;
            $pedido->fecha = $request->fecha;
            $pedido->estado = $request->estado;
            $pedido->usuario_id = $request->usuario_id;
            $pedido->save();
    
            return "Pedido creado exitosamente";
        }
    
        //Funcion de modificar
        public function edit($cod)
        {
            $pedido = Pedido::findOrFail($cod);
            return view('pedido.edit', compact('pedido'));
        }
    
        //Funcion de actualizar
        public function update(Request $request, $cod)
        {
            $request->validate([
                'fecha' => 'required',
                'estado' => 'required',
                'usuario_id' => 'required',
            ]);
    
            $pedido = Pedido::findOrFail($cod);
            $pedido->fecha = $request->fecha;
            $pedido->estado = $request->estado;
            $pedido->usuario_id = $request->usuario_id;
            $pedido->save();
    
            return "Pedido actualizado exitosamente";
        }
    
        //Funcion de eliminar
        public function destroy($cod)
        {
            $pedido = Pedido::findOrFail($cod);
            $pedido->delete();
            return "Pedido eliminado exitosamente";
        }

}