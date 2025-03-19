<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mesa;


class MesaController extends Controller{
    
        public function create()
        {
            return view('mesa.create');
        }
    
        public function store(Request $request)
        {
            $request->validate([
                'codMesa' => 'required|unique:mesas',
                'cantidadMeesa' => 'required',
                'ocupada' => 'required',
            ]);
    
            $mesa = new Mesa();
            $mesa->codMesa = $request->codMesa;
            $mesa->cantidadMesa = $request->cantidadMesa;
            $mesa->ocupada = $request->ocupada;

    
            $mesa->save();
    
            return "Mesa creada exitosamente";
        }
    
        public function edit($cod)
        {
            $mesa = Mesa::findOrFail($cod);
            return view('mesa.edit', compact('mesa'));
        }
    
        public function update(Request $request, $cod)
        {
            $request->validate([
                'cantidadMesa' => 'required',
            ]);
    
            $mesa = Mesa::findOrFail($codMesa);
            $mesa->cantidadMesa = $request->cantidadMesa;
            $mesa->ocupada = $request->ocupada;
    
            $mesa->save();
    
            return "Mesa actualizada exitosamente";
        }
    
        public function delete($codMesa)
        {
            $mesa = Mesa::findOrFail($codMesa);
            $mesa->delete();
    
            return "Mesa eliminada exitosamente";
        }
    
        public function show($codMesa)
        {
            $mesa = Mesa::findOrFail($codMesa);
            return view('mesa.show', compact('mesa'));
        }
    
        public function index()
        {
            $mesas = Mesa::all();
            return view('mesa.index', compact('mesas'));
        }
}