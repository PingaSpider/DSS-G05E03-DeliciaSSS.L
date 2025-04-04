<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Reserva</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Editar Reserva</h1>
        <hr>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Errores generales de validación -->
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="form_reserva" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="codReserva"><b>Código de Reserva</b></label>
                <input type="text" name="codReserva" id="codReserva" value="{{ $reserva->codReserva }}" readonly disabled class="readonly-input">
                <div class="input-info">El código de la reserva no se puede modificar</div>
            </div>
            
            <div class="form-group">
                <label for="fecha"><b>Fecha</b><span class="required">*</span></label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $reserva->fecha) }}" required>
                @error('fecha')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hora"><b>Hora</b><span class="required">*</span></label>
                <input type="time" name="hora" id="hora" value="{{ old('hora', $reserva->hora) }}" required>
                @error('hora')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cantPersona"><b>Cantidad de Personas</b><span class="required">*</span></label>
                <input type="number" min="1" name="cantPersona" id="cantPersona" value="{{ old('cantPersona', $reserva->cantPersona) }}" required>
                @error('cantPersona')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group checkbox-container">
                <label for="reservaConfirmada"><b>Reserva Confirmada</b>
                <input type="checkbox" title="Reserva Confirmada" name="reservaConfirmada" id="reservaConfirmada" value="1" {{ old('reservaConfirmada', $reserva->reservaConfirmada) ? 'checked' : '' }}>
                </label>
                @error('reservaConfirmada')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mesa_id"><b>Mesa</b><span class="required">*</span></label>
                <select name="mesa_id" id="mesa_id" required>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->codMesa }}" {{ $mesa->codMesa == old('mesa_id', $reserva->mesa_id) ? 'selected' : '' }} data-capacidad="{{ $mesa->cantidadMesa }}">
                            Mesa {{ $mesa->codMesa }} ({{ $mesa->cantidadMesa }} personas)
                        </option>
                    @endforeach
                </select>
                @error('mesa_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usuario_id"><b>Cliente</b><span class="required">*</span></label>
                <select name="usuario_id" id="usuario_id" required>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $usuario->id == old('usuario_id', $reserva->usuario_id) ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->email }})
                        </option>
                    @endforeach
                </select>
                @error('usuario_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Actualizar Reserva</button>
            
            <div class="action-links">
                <a href="{{ route('reservas.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar disponibilidad de mesas según cantidad de personas
            const cantPersonaInput = document.getElementById('cantPersona');
            const mesaSelect = document.getElementById('mesa_id');
            
            cantPersonaInput.addEventListener('change', function() {
                const personasCount = parseInt(this.value);
                
                // Recorrer todas las opciones y habilitar/deshabilitar según capacidad
                Array.from(mesaSelect.options).forEach(function(option) {
                    if (option.value === '') return; // Saltar la opción "Selecciona una mesa"
                    
                    const capacidad = parseInt(option.getAttribute('data-capacidad'));
                    if (capacidad < personasCount) {
                        option.disabled = true;
                        option.style.color = '#999';
                        
                        // Si la opción está seleccionada, mostrar advertencia
                        if (option.selected) {
                            alert('La mesa seleccionada no tiene capacidad suficiente. Por favor, seleccione otra mesa.');
                        }
                    } else {
                        option.disabled = false;
                        option.style.color = '';
                    }
                });
            });
            
            // Disparar el evento al cargar para configurar opciones iniciales
            cantPersonaInput.dispatchEvent(new Event('change'));
            
            // Validación del formulario antes de enviar
            document.getElementById('form_reserva').addEventListener('submit', function(e) {
                const cantPersona = parseInt(cantPersonaInput.value);
                const mesaId = mesaSelect.value;
                const mesaOpcion = mesaSelect.options[mesaSelect.selectedIndex];
                
                if (mesaId === '') {
                    e.preventDefault();
                    alert('Por favor, selecciona una mesa para la reserva.');
                    return false;
                }
                
                if (mesaOpcion.disabled) {
                    e.preventDefault();
                    alert('La mesa seleccionada no tiene capacidad suficiente para el número de personas indicado.');
                    return false;
                }
                
                // Si todo está correcto, el formulario se enviará
                return true;
            });
        });
    </script>
</body>
</html>


<!--Entrega2-->