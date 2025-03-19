<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{

    public function create()
    {
        // Obtener todas las mesas
        $mesas = Mesa::all();
        // Mostrar la vista para crear una reserva
        return view('reservas.create', compact('mesas'));
    }

    public function index()
    {
        // Obtener todas las reservas
        $reservas = Reserva::all();
        // Mostrar la vista con todas las reservas
        return view('reservas.index', compact('reservas'));
    }

    // Listar todas las reservas
    public function store(Request $request)
    {
        // Validar que la mesa esté disponible
        $mesa = Mesa::findOrFail($request->mesa_id);
        
        // Si la mesa no está disponible, mostrar un mensaje de error
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

        ]);
        // Redirigir a la lista de reservas con un mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente.');
    }

    //Modificar una reserva
    public function edit($id)
    {
        // Obtener la reserva por su id
        $reserva = Reserva::findOrFail($id);
        // Obtener todas las mesas
        $mesas = Mesa::all();
        // Mostrar la vista para editar la reserva
        return view('reservas.edit', compact('reserva', 'mesas'));
    }

    //Actualizar una reserva
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required',
            'hora' => 'required',
            'mesa_id' => 'required',
            'codReserva' => 'required',
            'cantPersona' => 'required',
        ]);
        // Actualizar la reserva con los nuevos datos
        $reserva = Reserva::findOrFail($id);
        $reserva->fecha = $request->fecha;
        $reserva->hora = $request->hora;
        $reserva->mesa_id = $request->mesa_id;
        $reserva->codReserva = $request->codReserva;
        $reserva->cantPersona = $request->cantPersona;
        $reserva->save();
        // Redirigir a la lista de reservas con un mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada exitosamente.');
    }

    //Eliminar una reserva
    public function destroy($id)
    {
        // Encontrar la reserva por su id
        $reserva = Reserva::findOrFail($id);
        // Eliminar la reserva
        $reserva->delete();
        // Redirigir a la lista de reservas con un mensaje de éxito
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada exitosamente.');
    }
}