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
        // Iniciar consulta
        $query = Mesa::query();
        
        // Filtrar por búsqueda si existe
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codMesa', 'like', "%{$search}%")
                ->orWhere('cantidadMesa', $search);
            });
        }
        
        // Obtener parámetros de ordenación
        $sortBy = $request->get('sort_by', 'codMesa');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Lista de campos permitidos para ordenar
        $allowedSortFields = ['codMesa', 'cantidadMesa', 'ocupada'];
        
        // Verificar que el campo de ordenación sea válido
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Ordenación por defecto
            $query->orderBy('codMesa', 'asc');
        }
        
        // Paginar resultados
        $mesas = $query->paginate(10);
        
        // Mostrar vista con resultados
        return view('mesa.paginate', compact('mesas'));
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