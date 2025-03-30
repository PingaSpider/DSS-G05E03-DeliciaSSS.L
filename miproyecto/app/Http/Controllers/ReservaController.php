<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Usuario;
use App\Models\Mesa;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function create()
    {
        $usuarios = Usuario::all();
        $mesas = Mesa::all();
        return view('reserva.create', compact('usuarios', 'mesas'));
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'codReserva' => 'required|integer|unique:reservas',
                'fecha' => 'required|date',
                'hora' => 'required',
                'cantPersona' => 'required|integer|min:1',
                'reservaConfirmada' => 'boolean',
                'mesa_id' => 'required|exists:mesas,codMesa',
                'usuario_id' => 'required|exists:usuarios,id',
            ]);
    
            DB::beginTransaction();
    
            $reserva = new Reserva();
            $reserva->codReserva = $request->codReserva;
            $reserva->fecha = $request->fecha;
            $reserva->hora = $request->hora;
            $reserva->cantPersona = $request->cantPersona;
            $reserva->reservaConfirmada = $request->reservaConfirmada ?? false;
            $reserva->mesa_id = $request->mesa_id;
            $reserva->usuario_id = $request->usuario_id;
            $reserva->save();
    
            DB::commit();
            return redirect()->route('reservas.paginate')
                ->with('success', 'Reserva creada exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }
    
    public function edit($codReserva)
    {
        try {
            $reserva = Reserva::findOrFail($codReserva);
            $usuarios = Usuario::all();
            $mesas = Mesa::all();
            return view('reserva.edit', compact('reserva', 'usuarios', 'mesas'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada');
        }
    }
    
    public function update(Request $request, $codReserva)
    {
        try {
            $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required',
                'cantPersona' => 'required|integer|min:1',
                'reservaConfirmada' => 'boolean',
                'mesa_id' => 'required|exists:mesas,codMesa',
                'usuario_id' => 'required|exists:usuarios,id',
            ]);
    
            DB::beginTransaction();
    
            $reserva = Reserva::findOrFail($codReserva);
            $reserva->fecha = $request->fecha;
            $reserva->hora = $request->hora;
            $reserva->cantPersona = $request->cantPersona;
            $reserva->reservaConfirmada = $request->reservaConfirmada ?? false;
            $reserva->mesa_id = $request->mesa_id;
            $reserva->usuario_id = $request->usuario_id;
            $reserva->save();
    
            DB::commit();
            return redirect()->route('reservas.paginate')
                ->with('success', 'Reserva actualizada exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la reserva: ' . $e->getMessage());
        }
    }
    
    public function destroy($codReserva)
    {
        try {
            DB::beginTransaction();
    
            $reserva = Reserva::findOrFail($codReserva);
            $reserva->delete();
    
            DB::commit();
            return redirect()->route('reservas.paginate')
                ->with('success', 'Reserva eliminada exitosamente');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Error al eliminar la reserva: ' . $e->getMessage());
        }
    }
    
    public function show($codReserva)
    {
        try {
            $reserva = Reserva::with('usuario', 'mesa')->findOrFail($codReserva);
            return view('reserva.show', compact('reserva'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada');
        }
    }
    
    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
    
        $query = Reserva::with('usuario', 'mesa');
    
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('codReserva', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($query) use ($search) {
                      $query->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('mesa', function($query) use ($search) {
                      $query->where('codMesa', 'like', "%{$search}%");
                  });
            });
        }
    
        $reservas = $query->paginate($perPage);
    
        return view('reserva.paginate', ['reservas' => $reservas]);
    }
    
    public function verificarCodigo(Request $request)
    {
        $codigo = $request->input('codReserva');
        $exists = Reserva::where('codReserva', $codigo)->exists();
            
        return response()->json(['exists' => $exists]);
    }
}
