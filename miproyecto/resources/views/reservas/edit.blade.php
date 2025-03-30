v<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Reserva</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reserva/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reserva/edit.css') }}">
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
        
        <form id="form_reserva" action="{{ route('reservas.update', $reserva->codReserva) }}" method="POST">
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

            <div class="form-group">
                <label for="reservaConfirmada"><b>Reserva Confirmada</b></label>
                <input type="checkbox" name="reservaConfirmada" id="reservaConfirmada" {{ old('reservaConfirmada', $reserva->reservaConfirmada) ? 'checked' : '' }}>
                @error('reservaConfirmada')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mesa_id"><b>Mesa</b><span class="required">*</span></label>
                <select name="mesa_id" id="mesa_id" required>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->codMesa }}" {{ $mesa->codMesa == old('mesa_id', $reserva->mesa_id) ? 'selected' : '' }}>
                            Mesa {{ $mesa->codMesa }}
                        </option>
                    @endforeach
                </select>
                @error('mesa_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usuario_id"><b>Usuario</b><span class="required">*</span></label>
                <select name="usuario_id" id="usuario_id" required>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $usuario->id == old('usuario_id', $reserva->usuario_id) ? 'selected' : '' }}>
                            {{ $usuario->nombre }}
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

    <style>
        @media (max-width: 576px) {
            .form-group {
                width: 100%;
            }

            input, select {
                width: 100%;
            }
        }
    </style>

</body>
</html>
