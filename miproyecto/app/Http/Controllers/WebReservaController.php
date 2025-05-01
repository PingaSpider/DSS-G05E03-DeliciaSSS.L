<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Mesa;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class WebReservaController extends Controller
{
    // Definir constantes de horarios
    const HORA_INICIO_SEMANA = '12:30';
    const HORA_FIN_SEMANA = '22:00';
    const HORA_INICIO_FINDE = '13:00';
    const HORA_FIN_FINDE = '23:30';
    const INTERVALO_MINUTOS = 15;

    /**
     * Muestra el formulario de reserva
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Registrar el inicio del método para depuración
        Log::info('Iniciando método index en WebReservaController');
        
        // Obtener el usuario autenticado (usando auth() en lugar de session)
        $usuario = auth()->user();
        
        Log::info('Estado de autenticación', [
            'auth_check' => auth()->check(),
            'usuario' => $usuario ? $usuario->email : 'No autenticado'
        ]);
        
        // Obtener fecha actual
        $fechaActual = Carbon::today();
        $esFindeSemana = $fechaActual->isWeekend();
        
        // Establecer horarios según día de la semana
        if ($esFindeSemana) {
            $horaInicio = Carbon::createFromTimeString(self::HORA_INICIO_FINDE);
            $horaFin = Carbon::createFromTimeString(self::HORA_FIN_FINDE);
        } else {
            $horaInicio = Carbon::createFromTimeString(self::HORA_INICIO_SEMANA);
            $horaFin = Carbon::createFromTimeString(self::HORA_FIN_SEMANA);
        }
        
        // Obtener las opciones de cantidad de personas para el dropdown
        $cantidadPersonas = [2, 4, 6, 8, 10];
        
        // Generar todas las franjas horarias disponibles
        $horas = [];
        $current = clone $horaInicio;
        
        while ($current <= $horaFin) {
            $horas[] = $current->format('H:i');
            $current->addMinutes(self::INTERVALO_MINUTOS);
        }
        
        // Definir hora seleccionada (la primera disponible)
        $horaSeleccionada = $horas[0] ?? '';
        
        // Determinar franjas horarias iniciales (primeras 4 disponibles)
        $franjasHorarias = array_slice($horas, 0, 4);
        
        // Cargar mesas disponibles
        $mesas = Mesa::orderBy('cantidadMesa')->get();
        
        // Horario del restaurante para la vista
        $horarios = (object)[
            'semana' => 'Lun-Vie: ' . self::HORA_INICIO_SEMANA . ' - ' . self::HORA_FIN_SEMANA,
            'finde' => 'Sab-Dom: ' . self::HORA_INICIO_FINDE . ' - ' . self::HORA_FIN_FINDE
        ];
        
        Log::info('Cargando vista reserva con datos', [
            'cantidadPersonas' => $cantidadPersonas,
            'horaSeleccionada' => $horaSeleccionada,
            'franjasHorarias' => $franjasHorarias,
            'fecha' => $fechaActual->format('Y-m-d'),
            'usuario' => $usuario ? $usuario->email : 'No autenticado',
            'mesas_count' => $mesas->count()
        ]);
        
        return view('reserva', [
            'cantidadPersonas' => $cantidadPersonas,
            'horas' => $horas,
            'horaSeleccionada' => $horaSeleccionada,
            'franjasHorarias' => $franjasHorarias,
            'fecha' => $fechaActual->format('Y-m-d'),
            'esFindeSemana' => $esFindeSemana,
            'horarios' => $horarios,
            'mesas' => $mesas,
            'usuario' => $usuario // Pasar el usuario a la vista
        ]);
    }
    
    /**
     * Almacena una nueva reserva
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Registrar los datos recibidos para depuración
            Log::info('Iniciando creación de reserva', [
                'request_data' => $request->all(),
                'auth_check' => auth()->check(),
                'user_id' => auth()->id()
            ]);
            
            // Validación de datos de entrada
            $validator = Validator::make($request->all(), [
                'personas' => 'required|integer|min:1',
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'required|date_format:H:i',
                'mesa_id' => 'required|exists:mesas,codMesa', // Importante: Ahora requerimos mesa_id
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validación fallida en creación de reserva', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return redirect()->route('reservaciones.index')
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Verificar que la hora esté dentro del horario del restaurante
            if (!$this->esHorarioValido($request->fecha, $request->hora)) {
                Log::warning('Hora fuera del horario válido', [
                    'fecha' => $request->fecha,
                    'hora' => $request->hora
                ]);
                
                return redirect()->route('reservaciones.index')
                    ->with('error', 'La hora seleccionada está fuera del horario del restaurante')
                    ->withInput();
            }
            
            // Comprobamos que el usuario esté autenticado usando auth() en lugar de session
            if (!auth()->check()) {
                Log::warning('Intento de reserva sin usuario autenticado');
                
                return redirect()->route('login')
                    ->with('error', 'Debes iniciar sesión para realizar una reserva');
            }
            
            $usuario = auth()->user();
            $usuarioId = auth()->id();
            
            Log::info('Usuario autenticado', [
                'id' => $usuarioId,
                'email' => $usuario->email
            ]);
            
            // Verificar que la mesa seleccionada tenga capacidad suficiente
            $mesa = Mesa::findOrFail($request->mesa_id);
            
            if ($mesa->cantidadMesa < $request->personas) {
                Log::warning('Mesa sin capacidad suficiente', [
                    'mesa_id' => $mesa->codMesa,
                    'capacidad_mesa' => $mesa->cantidadMesa,
                    'personas_solicitadas' => $request->personas
                ]);
                
                return redirect()->route('reservaciones.index')
                    ->with('error', 'La mesa seleccionada no tiene capacidad suficiente para el número de personas indicado')
                    ->withInput();
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
                    Log::warning('Mesa ya reservada en hora cercana', [
                        'mesa_id' => $mesa->codMesa,
                        'hora_solicitada' => $request->hora,
                        'hora_existente' => $reservaExistente->hora,
                        'diferencia_horas' => $diferenciaHoras
                    ]);
                    
                    return redirect()->route('reservaciones.index')
                        ->with('error', 'La mesa seleccionada ya está reservada a una hora cercana. Por favor, elija otra mesa o cambie la hora')
                        ->withInput();
                }
            }
            
            // Iniciar una transacción para asegurar integridad
            DB::beginTransaction();
            
            try {
                // Generar código de reserva
                $nuevoCodigo = $this->generarCodigoReserva();
                
                Log::info('Creando nueva reserva', [
                    'fecha' => $request->fecha,
                    'hora' => $request->hora,
                    'codigo' => $nuevoCodigo,
                    'personas' => $request->personas,
                    'mesa_id' => $request->mesa_id,
                    'usuario_id' => $usuarioId
                ]);
                
                // Crear la reserva
                $reserva = new Reserva();
                $reserva->fecha = $request->fecha;
                $reserva->hora = $request->hora;
                $reserva->codReserva = $nuevoCodigo;
                $reserva->cantPersona = $request->personas;
                $reserva->reservaConfirmada = true; // Confirmada automáticamente para el cliente
                $reserva->mesa_id = $request->mesa_id;
                $reserva->usuario_id = $usuarioId;
                
                $reserva->save();
                
                // Confirmar la transacción
                DB::commit();
                
                Log::info('Reserva creada exitosamente', ['id' => $reserva->id]);
                
                // Redirigir a la página de confirmación
                return redirect()->route('reservaciones.confirmacion', ['id' => $reserva->id])
                    ->with('success', '¡Tu reserva ha sido confirmada!');
            } catch (\Exception $e) {
                DB::rollBack();
                
                Log::error('Error al guardar la reserva', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->route('reservaciones.index')
                    ->with('error', 'Error al guardar la reserva: ' . $e->getMessage())
                    ->withInput();
            }
                
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            // Registrar el error para depuración
            Log::error('Error general al procesar reserva', [
                'request' => $request->all(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('reservaciones.index')
                ->with('error', 'Error al procesar la reserva: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Verifica si la hora está dentro del horario del restaurante
     * 
     * @param string $fecha
     * @param string $hora
     * @return boolean
     */
    private function esHorarioValido($fecha, $hora)
    {
        try {
            // Determinar si es fin de semana
            $fechaCarbon = Carbon::parse($fecha);
            $esFindeSemana = $fechaCarbon->isWeekend();
            
            // Hora seleccionada
            $horaSeleccionada = Carbon::createFromTimeString($hora);
            
            // Límites de horario según día de la semana
            if ($esFindeSemana) {
                $horaInicio = Carbon::createFromTimeString(self::HORA_INICIO_FINDE);
                $horaFin = Carbon::createFromTimeString(self::HORA_FIN_FINDE);
            } else {
                $horaInicio = Carbon::createFromTimeString(self::HORA_INICIO_SEMANA);
                $horaFin = Carbon::createFromTimeString(self::HORA_FIN_SEMANA);
            }
            
            // Verificar si está dentro del rango
            $esValido = $horaSeleccionada->gte($horaInicio) && $horaSeleccionada->lte($horaFin);
            
            Log::info('Validación de horario', [
                'fecha' => $fecha,
                'hora' => $hora,
                'es_finde' => $esFindeSemana,
                'hora_inicio' => $horaInicio->format('H:i'),
                'hora_fin' => $horaFin->format('H:i'),
                'es_valido' => $esValido
            ]);
            
            return $esValido;
        } catch (\Exception $e) {
            Log::error('Error al validar horario', [
                'error' => $e->getMessage(),
                'fecha' => $fecha,
                'hora' => $hora
            ]);
            return false;
        }
    }
    
    /**
     * Muestra la página de confirmación de reserva
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function confirmacion($id)
    {
        try {
            // Obtener el usuario autenticado
            $usuario = auth()->user();
            $usuarioId = auth()->id();
            
            Log::info('Cargando confirmación de reserva', [
                'id_reserva' => $id,
                'usuario_id' => $usuarioId
            ]);
            
            if (!$usuario) {
                Log::warning('Intento de ver confirmación sin usuario autenticado');
                return redirect()->route('login')
                    ->with('error', 'Debes iniciar sesión para ver tu reserva');
            }
            
            $reserva = Reserva::where('id', $id)
                ->where('usuario_id', $usuarioId)
                ->first();
                
            if (!$reserva) {
                Log::warning('Reserva no encontrada o no pertenece al usuario', [
                    'id_reserva' => $id,
                    'usuario_id' => $usuarioId
                ]);
                
                return redirect()->route('reservaciones.index')
                    ->with('error', 'No se ha encontrado la reserva solicitada');
            }
            
            // Obtener detalles de la mesa
            $mesa = Mesa::find($reserva->mesa_id);
            
            // Formatear fecha para mostrar
            $fechaFormateada = Carbon::parse($reserva->fecha)->format('d/m/Y');
            
            // Preparar datos para la vista
            $reservaData = [
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'personas' => $reserva->cantPersona,
                'fecha' => $fechaFormateada,
                'hora' => $reserva->hora,
                'codigo' => $reserva->codReserva,
                'mesa' => $mesa ? $mesa->codMesa : 'No disponible'
            ];
            
            $mensaje = [
                'titulo' => '¡Gracias por tu reserva!',
                'subtitulo' => 'Te hemos enviado un email con los detalles de tu reserva. Tu código de reserva es: ' . $reserva->codReserva,
            ];
            
            Log::info('Mostrando confirmación de reserva', [
                'id_reserva' => $id,
                'datos' => $reservaData
            ]);
            
            return view('confirmacionreserva', [
                'reserva' => (object)$reservaData,
                'mensaje' => (object)$mensaje,
            ]);
            
        } catch (Exception $e) {
            // Registrar el error para depuración
            Log::error('Error al mostrar confirmación: ' . $e->getMessage(), [
                'id_reserva' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('reservaciones.index')
                ->with('error', 'Error al mostrar la confirmación. Por favor, contacte con el restaurante.');
        }
    }
    
    /**
     * Genera un código único para la reserva
     * 
     * @return int
     */
    private function generarCodigoReserva()
    {
        try {
            // Obtener el último código de reserva y generar uno nuevo incrementalmente
            $ultimaReserva = Reserva::orderBy('codReserva', 'desc')->first();
            $nuevoCodigo = $ultimaReserva ? $ultimaReserva->codReserva + 1 : 1001;
            
            Log::info('Generando código de reserva', [
                'ultimo_codigo' => $ultimaReserva ? $ultimaReserva->codReserva : 'ninguno',
                'nuevo_codigo' => $nuevoCodigo
            ]);
            
            return $nuevoCodigo;
        } catch (\Exception $e) {
            Log::error('Error al generar código de reserva', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // En caso de error, generar un código aleatorio como fallback
            return 1000 + rand(1000, 9999);
        }
    }
    
    /**
     * Cancela una reserva existente
     * 
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelar($id)
    {
        try {
            // Obtener el usuario autenticado
            $usuario = auth()->user();
            $usuarioId = auth()->id();
            
            if (!$usuario) {
                return redirect()->route('login')
                    ->with('error', 'Debes iniciar sesión para cancelar tu reserva');
            }
            
            $reserva = Reserva::where('id', $id)
                ->where('usuario_id', $usuarioId)
                ->first();
                
            if (!$reserva) {
                return back()->with('error', 'No se ha encontrado la reserva para cancelar');
            }
            
            // Verificar que la reserva está en una fecha futura
            $fechaReserva = Carbon::parse($reserva->fecha . ' ' . $reserva->hora);
            
            if ($fechaReserva->isPast()) {
                return back()->with('error', 'No se puede cancelar una reserva pasada');
            }
            
            // Iniciar transacción
            DB::beginTransaction();
            
            try {
                // Eliminar la reserva (el modelo tiene un evento booted 
                // que se encarga de marcar la mesa como disponible)
                $reserva->delete();
                
                // Confirmar la transacción
                DB::commit();
                
                Log::info('Reserva cancelada correctamente', [
                    'id_reserva' => $id,
                    'usuario_id' => $usuarioId
                ]);
                
                return redirect()->route('user.profile')
                    ->with('success', 'Reserva cancelada correctamente');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al eliminar la reserva', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->with('error', 'Error al cancelar la reserva: ' . $e->getMessage());
            }
                
        } catch (Exception $e) {
            // Si hay una transacción activa, revertirla
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            // Registrar el error para depuración
            Log::error('Error al cancelar reserva: ' . $e->getMessage(), [
                'id_reserva' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al cancelar la reserva. Por favor, inténtelo de nuevo.');
        }
    }
    
    /**
     * Muestra las reservas del usuario actual
     * 
     * @return \Illuminate\View\View
     */
    public function misReservas()
    {
        try {
            // Obtener el usuario autenticado
            $usuario = auth()->user();
            $usuarioId = auth()->id();
            
            if (!$usuario) {
                return redirect()->route('login')
                    ->with('error', 'Debes iniciar sesión para ver tus reservas');
            }
            
            // Obtener reservas del usuario, ordenadas por fecha
            $reservas = Reserva::where('usuario_id', $usuarioId)
                               ->orderBy('fecha', 'desc')
                               ->orderBy('hora', 'desc')
                               ->paginate(10);
            
            return view('mis-reservas', [
                'reservas' => $reservas,
                'usuario' => $usuario
            ]);
            
        } catch (Exception $e) {
            // Registrar el error para depuración
            Log::error('Error al cargar mis reservas: ' . $e->getMessage(), [
                'usuario_id' => auth()->id(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al cargar las reservas. Por favor, inténtelo de nuevo.');
        }
    }
}