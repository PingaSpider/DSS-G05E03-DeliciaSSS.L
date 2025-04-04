<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Reserva</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nueva Reserva</h1>
        <hr>
        
        <div class="reserva-info">
            <p>El código de reserva se generará automáticamente al crear la reserva.</p>
        </div>
        
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

        <form id="form_reserva" action="{{ route('reservas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="fecha"><b>Fecha de la Reserva</b><span class="required">*</span></label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hora"><b>Hora de la Reserva</b><span class="required">*</span></label>
                <input type="time" name="hora" id="hora" value="{{ old('hora', '20:00') }}" required>
                <div class="field-info">Introduce la hora en formato 24h (ej: 20:00 para las 8 de la tarde)</div>
                @error('hora')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cantPersona"><b>Cantidad de Personas</b><span class="required">*</span></label>
                <input type="number" name="cantPersona" id="cantPersona" value="{{ old('cantPersona', 2) }}" min="1" required>
                <div class="field-info">Indica el número de comensales para seleccionar una mesa adecuada</div>
                @error('cantPersona')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mesa_id"><b>Mesa</b><span class="required">*</span></label>
                <select name="mesa_id" id="mesa_id" required>
                    <option value="">Selecciona una mesa</option>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->codMesa }}" {{ old('mesa_id') == $mesa->codMesa ? 'selected' : '' }} data-capacidad="{{ $mesa->cantidadMesa }}">
                            Mesa {{ $mesa->codMesa }} ({{ $mesa->cantidadMesa }} personas)
                        </option>
                    @endforeach
                </select>
                <div class="field-info">Se mostrarán solo las mesas disponibles para la fecha y hora seleccionadas</div>
                @error('mesa_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usuario_id"><b>Cliente</b><span class="required">*</span></label>
                <select name="usuario_id" id="usuario_id" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->email }})
                        </option>
                    @endforeach
                </select>
                @error('usuario_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group checkbox-container">
                <input type="checkbox" name="reservaConfirmada" id="reservaConfirmada" value="1" {{ old('reservaConfirmada') ? 'checked' : '' }}>
                <label for="reservaConfirmada"><b>Confirmar Reserva</b></label>
            </div>
            <div class="field-info">Marca esta casilla si la reserva ya está confirmada con el cliente. Si no, podrá confirmarse más tarde.</div>

            <button type="submit" class="submit-btn">Crear Reserva</button>
            
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
                        if (option.selected) {
                            mesaSelect.value = '';
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
                
                if (mesaId === '') {
                    e.preventDefault();
                    alert('Por favor, selecciona una mesa para la reserva.');
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