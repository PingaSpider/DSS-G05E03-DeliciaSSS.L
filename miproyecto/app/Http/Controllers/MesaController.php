<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;

class MesaController extends Controller
{
    public function create()
    {
        return view('mesa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codMesa' => 'required|unique:mesas',
            'cantidadMesa' => 'required', // Corregido de cantidadMeesa
            'ocupada' => 'required',
        ]);

        $mesa = new Mesa();
        $mesa->codMesa = $request->codMesa;
        $mesa->cantidadMesa = $request->cantidadMesa;
        $mesa->ocupada = $request->ocupada;

        $mesa->save();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa creada exitosamente');
    }

    public function edit($codMesa) // Cambiado a $codMesa para consistencia
    {
        $mesa = Mesa::findOrFail($codMesa);
        return view('mesa.edit', compact('mesa'));
    }

    public function update(Request $request, $codMesa) // Cambiado para consistencia
    {
        $request->validate([
            'cantidadMesa' => 'required',
        ]);

        $mesa = Mesa::findOrFail($codMesa); // Corregido de $codMesa a $codMesa
        $mesa->cantidadMesa = $request->cantidadMesa;
        $mesa->ocupada = $request->ocupada;

        $mesa->save();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa actualizada exitosamente');
    }

    // Cambiado a destroy para seguir convención de Laravel
    public function destroy($codMesa)
    {
        $mesa = Mesa::findOrFail($codMesa);
        $mesa->delete();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa eliminada exitosamente');
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

    public function delete($codMesa)
    {
        $mesa = Mesa::findOrFail($codMesa);
        return view('mesa.delete', compact('mesa'));
    }
}