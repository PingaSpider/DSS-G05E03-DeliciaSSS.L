<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Otros métodos...

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar que la mesa esté disponible
        $mesa = Mesa::findOrFail($request->mesa_id);
        
        if (!$mesa->estaDisponible($request->fecha, $request->hora)) {
            return back()->withErrors(['mesa' => 'Esta mesa ya está reservada para la fecha y hora seleccionadas.']);
        }
        
        // Crear la reserva si está disponible
        Reserva::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'mesa_id' => $request->mesa_id,
            'codReserva' => $request->codReserva,
            'cantPersona' => $request->cantPersona,
            'usuario_id' => auth()->id(), // Asume que el usuario está autenticado
            // Otros campos...
        ]);
        
        return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente.');
    }

    // Otros métodos...
}