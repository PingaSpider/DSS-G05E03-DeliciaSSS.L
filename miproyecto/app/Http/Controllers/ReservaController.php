<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Usuario;
use App\Models\Mesa;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function create()
    {
        // Cargar solo mesas que no estén ocupadas
        $mesas = Mesa::where('ocupada', 0)->get();
        $usuarios = Usuario::orderBy('nombre')->get();
        return view('reserva.create', compact('usuarios', 'mesas'));
    }
    
    public function store(Request $request)
    {
        try {
            // Validación de datos
            $request->validate([
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => [
                    'required',
                    'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/',
                ],
                'cantPersona' => 'required|integer|min:1',
                'mesa_id' => 'required|exists:mesas,codMesa',
                'usuario_id' => 'required|exists:usuarios,id',
                'reservaConfirmada' => 'boolean',
            ], [
                'fecha.after_or_equal' => 'La fecha de la reserva debe ser hoy o una fecha futura.',
                'hora.regex' => 'El formato de hora no es válido. Use el formato 24h (ej: 20:00).',
                'cantPersona.min' => 'La cantidad de personas debe ser al menos 1.',
                'mesa_id.required' => 'Debe seleccionar una mesa para la reserva.',
                'usuario_id.required' => 'Debe seleccionar un cliente para la reserva.',
            ]);

            // Verificar que la mesa seleccionada tenga suficiente capacidad
            $mesa = Mesa::findOrFail($request->mesa_id);
            if ($mesa->cantidadMesa < $request->cantPersona) {
                return back()->withInput()->with('error', 'La mesa seleccionada no tiene capacidad suficiente para el número de personas indicado.');
            }
            
            // Verificar disponibilidad de la mesa en la fecha y hora seleccionadas
            $fechaHoraReserva = Carbon::parse($request->fecha . ' ' . $request->hora);
            
            // Buscar reservas existentes para la misma mesa en un rango de +-2 horas
            $reservasExistentes = Reserva::where('mesa_id', $request->mesa_id)
                ->whereDate('fecha', $request->fecha)
                ->get();
                
            foreach ($reservasExistentes as $reservaExistente) {
                $horaExistente = Carbon::parse($reservaExistente->fecha . ' ' . $reservaExistente->hora);
                $diferenciaHoras = abs($fechaHoraReserva->diffInHours($horaExistente));
                
                if ($diferenciaHoras < 2) {
                    return back()->withInput()->with('error', 
                        'La mesa seleccionada ya está reservada a una hora cercana. Por favor, elija otra mesa o cambie la hora.');
                }
            }

            DB::beginTransaction();
            
            // Generar código de reserva automáticamente
            $ultimaReserva = Reserva::orderBy('codReserva', 'desc')->first();
            $nuevoCodigo = $ultimaReserva ? $ultimaReserva->codReserva + 1 : 1001;
            
            $reserva = new Reserva();
            $reserva->codReserva = $nuevoCodigo;
            $reserva->fecha = $request->fecha;
            $reserva->hora = $request->hora;
            $reserva->cantPersona = $request->cantPersona;
            $reserva->reservaConfirmada = $request->has('reservaConfirmada') ? true : false;
            $reserva->mesa_id = $request->mesa_id;
            $reserva->usuario_id = $request->usuario_id;
            $reserva->save();
    
            DB::commit();
            
            $estadoReserva = $reserva->reservaConfirmada ? 'confirmada' : 'pendiente de confirmación';
            return redirect()->route('reservas.paginate')
                ->with('success', "Reserva #{$nuevoCodigo} creada exitosamente. Estado: {$estadoReserva}");
                
        } catch (Exception $e) {
            DB::rollBack();
            // Mensaje de error más amigable para el usuario
            $mensaje = 'Error al crear la reserva. Por favor, verifique los datos e intente nuevamente.';
            
            // Si estamos en entorno de desarrollo, añadir detalles técnicos
            if (config('app.debug')) {
                $mensaje .= ' Detalles: ' . $e->getMessage();
            }
            
            return back()->withInput()->with('error', $mensaje);
        }
    }
    
    public function edit($codReserva)
    {
        try {
            $reserva = Reserva::findOrFail($codReserva);
            // Obtener todas las mesas, incluyendo la actualmente asignada aunque esté ocupada
            $mesas = Mesa::get();
            $usuarios = Usuario::orderBy('nombre')->get();
            return view('reserva.edit', compact('reserva', 'usuarios', 'mesas'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada. Verifique el código e intente nuevamente.');
        }
    }
    
    public function update(Request $request, $codReserva)
    {
        try {
            // Validación similar a store pero permitiendo la fecha actual
            $request->validate([
                'fecha' => 'required|date',
                'hora'=> 'required',
                'cantPersona' => 'required|integer|min:1',
                'mesa_id' => 'required|exists:mesas,codMesa',
                'usuario_id' => 'required|exists:usuarios,id',
                'reservaConfirmada' => 'boolean',
            ], [
                'hora.regex' => 'El formato de hora no es válido. Use el formato 24h (ej: 20:00).',
                'cantPersona.min' => 'La cantidad de personas debe ser al menos 1.',
                'mesa_id.required' => 'Debe seleccionar una mesa para la reserva.',
                'usuario_id.required' => 'Debe seleccionar un cliente para la reserva.',
            ]);

            // Verificar que la mesa seleccionada tenga suficiente capacidad
            $mesa = Mesa::findOrFail($request->mesa_id);
            if ($mesa->cantidadMesa < $request->cantPersona) {
                return back()->withInput()->with('error', 'La mesa seleccionada no tiene capacidad suficiente para el número de personas indicado.');
            }
            
            // Encontrar la reserva actual
            $reserva = Reserva::findOrFail($codReserva);
            
            // Si cambia la mesa, fecha u hora, verificar disponibilidad
            if ($reserva->mesa_id != $request->mesa_id || 
                $reserva->fecha != $request->fecha || 
                $reserva->hora != $request->hora) {
                
                $fechaHoraReserva = Carbon::parse($request->fecha . ' ' . $request->hora);
                
                // Buscar reservas existentes para la misma mesa en un rango de +-2 horas
                $reservasExistentes = Reserva::where('mesa_id', $request->mesa_id)
                    ->where('codReserva', '!=', $codReserva) // Excluir la reserva actual
                    ->whereDate('fecha', $request->fecha)
                    ->get();
                    
                foreach ($reservasExistentes as $reservaExistente) {
                    $horaExistente = Carbon::parse($reservaExistente->fecha . ' ' . $reservaExistente->hora);
                    $diferenciaHoras = abs($fechaHoraReserva->diffInHours($horaExistente));
                    
                    if ($diferenciaHoras < 2) {
                        return back()->withInput()->with('error', 
                            'La mesa seleccionada ya está reservada a una hora cercana. Por favor, elija otra mesa o cambie la hora.');
                    }
                }
            }

            DB::beginTransaction();
    
            $cambiosRealizados = [];
            
            // Registrar cambios significativos para el mensaje de éxito
            if ($reserva->fecha != $request->fecha || $reserva->hora != $request->hora) {
                $cambiosRealizados[] = "fecha/hora";
            }
            
            if ($reserva->mesa_id != $request->mesa_id) {
                $cambiosRealizados[] = "mesa";
            }
            
            if ($reserva->reservaConfirmada != ($request->has('reservaConfirmada') ? true : false)) {
                $cambiosRealizados[] = "estado de confirmación";
            }
            
            // Actualizar los datos de la reserva
            $reserva->fecha = $request->fecha;
            $reserva->hora = $request->hora;
            $reserva->cantPersona = $request->cantPersona;
            $reserva->reservaConfirmada = $request->has('reservaConfirmada') ? true : false;
            $reserva->mesa_id = $request->mesa_id;
            $reserva->usuario_id = $request->usuario_id;
            $reserva->save();
    
            DB::commit();
            
            $mensajeCambios = count($cambiosRealizados) > 0 
                ? " Se actualizó: " . implode(", ", $cambiosRealizados) . "."
                : "";
                
            $estadoReserva = $reserva->reservaConfirmada ? 'confirmada' : 'pendiente de confirmación';
            
            return redirect()->route('reservas.paginate')
                ->with('success', "Reserva #{$codReserva} actualizada exitosamente.{$mensajeCambios} Estado: {$estadoReserva}");
                
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada. Verifique el código e intente nuevamente.');
        } catch (Exception $e) {
            DB::rollBack();
            // Mensaje de error más amigable para el usuario
            $mensaje = 'Error al actualizar la reserva. Por favor, verifique los datos e intente nuevamente.';
            
            // Si estamos en entorno de desarrollo, añadir detalles técnicos
            if (config('app.debug')) {
                $mensaje .= ' Detalles: ' . $e->getMessage();
            }
            
            return back()->withInput()->with('error', $mensaje);
        }
    }
    
    public function destroy($codReserva)
    {
        try {
            DB::beginTransaction();
    
            $reserva = Reserva::findOrFail($codReserva);
            $fecha = Carbon::parse($reserva->fecha)->format('d/m/Y');
            $reserva->delete();
    
            DB::commit();
            return redirect()->route('reservas.paginate')
                ->with('success', "Reserva #{$codReserva} del {$fecha} eliminada exitosamente.");
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada. Verifique el código e intente nuevamente.');
        } catch (Exception $e) {
            DB::rollBack();
            // Mensaje de error más amigable
            $mensaje = 'Error al eliminar la reserva. Puede que tenga registros relacionados.';
            
            if (config('app.debug')) {
                $mensaje .= ' Detalles: ' . $e->getMessage();
            }
            
            return redirect()->route('reservas.paginate')
                ->with('error', $mensaje);
        }
    }
    
    public function show($codReserva)
    {
        try {
            $reserva = Reserva::findOrFail($codReserva);
            $usuario = Usuario::findOrFail($reserva->usuario_id);
            $mesa = Mesa::findOrFail($reserva->mesa_id);
            return view('reserva.show', [
                'reserva' => $reserva,
                'usuario' => $usuario,
                'mesa' => $mesa,
                'cantPersona' => $reserva->cantPersona,
                'hora' => $reserva->hora,
                'fecha' => $reserva->fecha,

            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada. Verifique el código e intente nuevamente.');
        }
    }
    
    public function paginate(Request $request)
    {
        // Obtener parámetros de ordenación y búsqueda
        $sortBy = $request->get('sort_by', 'fecha');
        $sortOrder = $request->get('sort_order', 'asc');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        // Iniciar consulta con relaciones
        $query = Reserva::with('usuario', 'mesa');

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('codReserva', 'like', "%{$search}%")
                ->orWhere('fecha', 'like', "%{$search}%")
                ->orWhere('hora', 'like', "%{$search}%")
                ->orWhere('cantPersona', 'like', "%{$search}%")
                ->orWhere('mesa_id', 'like', "%{$search}%")
                ->orWhereHas('usuario', function($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%");
                });
            });
        }

        // Lista de campos permitidos para ordenar
        $allowedSortFields = ['codReserva', 'fecha', 'hora', 'cantPersona', 'reservaConfirmada', 'mesa_id', 'usuario_id'];
        
        // Verificar que el campo de ordenación sea válido
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'usuario_id') {
                // Ordenar por nombre de usuario requiere un join
                $query->join('usuarios', 'reservas.usuario_id', '=', 'usuarios.id')
                    ->orderBy('usuarios.nombre', $sortOrder)
                    ->select('reservas.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            // Ordenación por defecto
            $query->orderBy('fecha', 'asc')->orderBy('hora', 'asc');
        }

        $reservas = $query->paginate($perPage);

        return view('reserva.paginate', compact('reservas'));
    }
    
    // Método para confirmar rápidamente una reserva
    public function confirmarReserva($codReserva)
    {
        try {
            DB::beginTransaction();
            
            $reserva = Reserva::findOrFail($codReserva);
            $reserva->reservaConfirmada = true;
            $reserva->save();
            
            DB::commit();
            
            return redirect()->route('reservas.paginate')
                ->with('success', "Reserva #{$codReserva} confirmada exitosamente.");
                
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada. Verifique el código e intente nuevamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Error al confirmar la reserva.');
        }
    }
    
    // Método para cancelar una reserva
    public function cancelarReserva($codReserva)
    {
        try {
            DB::beginTransaction();
            
            $reserva = Reserva::findOrFail($codReserva);
            $reserva->reservaConfirmada = false;
            $reserva->save();
            
            DB::commit();
            
            return redirect()->route('reservas.paginate')
                ->with('success', "Reserva #{$codReserva} marcada como pendiente de confirmación.");
                
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Reserva no encontrada. Verifique el código e intente nuevamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('reservas.paginate')
                ->with('error', 'Error al cambiar el estado de la reserva.');
        }
    }
    
    // Método para obtener mesas disponibles según fecha/hora
    public function getMesasDisponibles(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $hora = $request->input('hora');
            $cantPersonas = $request->input('cantPersonas', 1);
            
            if (!$fecha || !$hora) {
                return response()->json(['error' => 'Fecha y hora son requeridas'], 400);
            }
            
            $fechaHoraReserva = Carbon::parse($fecha . ' ' . $hora);
            
            // Obtener todas las mesas con capacidad suficiente
            $mesas = Mesa::where('cantidadMesa', '>=', $cantPersonas)
                          ->where('ocupada', 0)
                          ->get();
            
            // Filtrar las mesas que ya tienen reservas cercanas a la hora seleccionada
            $mesasDisponibles = $mesas->filter(function($mesa) use ($fecha, $fechaHoraReserva) {
                $reservasExistentes = Reserva::where('mesa_id', $mesa->codMesa)
                    ->whereDate('fecha', $fecha)
                    ->get();
                
                foreach ($reservasExistentes as $reserva) {
                    $horaExistente = Carbon::parse($reserva->fecha . ' ' . $reserva->hora);
                    $diferenciaHoras = abs($fechaHoraReserva->diffInHours($horaExistente));
                    
                    if ($diferenciaHoras < 2) {
                        return false; // Mesa no disponible
                    }
                }
                
                return true; // Mesa disponible
            });
            
            return response()->json([
                'mesas' => $mesasDisponibles->values(),
                'total' => $mesasDisponibles->count()
            ]);
            
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al buscar mesas disponibles'], 500);
        }
    }
    
    // Método para verificar código de reserva
    public function verificarCodigo(Request $request)
    {
        $codigo = $request->input('codReserva');
        $exists = Reserva::where('codReserva', $codigo)->exists();
            
        return response()->json(['exists' => $exists]);
    }
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'codMesa');
    }
}