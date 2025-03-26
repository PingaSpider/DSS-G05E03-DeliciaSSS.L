<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class MesaController extends Controller
{
    public function create()
    {
        return view('mesa.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'codMesa' => 'required|unique:mesas|max:5',
                'cantidadMesa' => 'required|numeric|min:1',
                'ocupada' => 'required|boolean',
            ]);

            $mesa = new Mesa();
            $mesa->codMesa = $request->codMesa;
            $mesa->cantidadMesa = $request->cantidadMesa;
            $mesa->ocupada = $request->ocupada;

            $mesa->save();

            return redirect()->route('mesas.paginate')
                ->with('success', 'Mesa creada exitosamente');
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Hubo un error al crear la mesa: ' . $e->getMessage());
        }
    }

    public function edit($codMesa)
    {
        try {
            $mesa = Mesa::findOrFail($codMesa);
            return view('mesa.edit', compact('mesa'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('mesas.paginate')
                ->with('error', 'Mesa no encontrada');
        }
    }

    public function update(Request $request, $codMesa)
    {
        try {
            $request->validate([
                'cantidadMesa' => 'required|numeric|min:1',
                'ocupada' => 'required|boolean',
            ]);

            $mesa = Mesa::findOrFail($codMesa);
            $mesa->cantidadMesa = $request->cantidadMesa;
            $mesa->ocupada = $request->ocupada;

            $mesa->save();

            return redirect()->route('mesas.paginate')
                ->with('success', 'Mesa actualizada exitosamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('mesas.paginate')
                ->with('error', 'Mesa no encontrada');
        } catch (Exception $e) {
            return redirect()->route('mesas.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($codMesa)
    {
        try {
            $mesa = Mesa::findOrFail($codMesa);
            $mesa->delete();

            return redirect()->route('mesas.paginate')
                ->with('success', 'Mesa eliminada exitosamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('mesas.paginate')
                ->with('error', 'Mesa no encontrada');
        } catch (Exception $e) {
            return redirect()->route('mesas.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function show($codMesa)
    {
        try {
            $mesa = Mesa::findOrFail($codMesa);
            return view('mesa.show', compact('mesa'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('mesas.paginate')
                ->with('error', 'Mesa no encontrada');
        }
    }

    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $query = Mesa::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('codMesa', 'like', "%{$search}%")
                  ->orWhere('cantidadMesa', 'like', "%{$search}%");
            });
        }
        
        $mesas = $query->paginate($perPage);
        
        return view('mesa.paginate', ['mesas' => $mesas]);
    }

    public function verificarCodigo(Request $request)
    {
        $codigo = $request->input('codMesa');
        $exists = Mesa::where('codMesa', $codigo)->exists();
            
        return response()->json(['exists' => $exists]);
    }

    // Mantener este método para compatibilidad
    public function index()
    {
        return $this->paginate(request());
    }
}