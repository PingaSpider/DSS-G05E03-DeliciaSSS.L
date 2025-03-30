<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Reserva</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reserva/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nueva Reserva</h1>
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
        
        <form id="form_reserva" action="{{ route('reservas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="codReserva"><b>Código de Reserva</b></label>
                <input type="number" placeholder="Introduce el código" name="codReserva" id="codReserva" value="{{ old('codReserva') }}" required>
                <div class="error-message" id="codReservaError">
                    @error('codReserva')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="fecha"><b>Fecha de la Reserva</b></label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hora"><b>Hora de la Reserva</b></label>
                <input type="time" name="hora" value="{{ old('hora') }}" required>
                @error('hora')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cantPersona"><b>Cantidad de Personas</b></label>
                <input type="number" name="cantPersona" value="{{ old('cantPersona') }}" min="1" required>
                @error('cantPersona')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="reservaConfirmada"><b>Confirmar Reserva</b></label>
                <input type="checkbox" name="reservaConfirmada" {{ old('reservaConfirmada') ? 'checked' : '' }}>
            </div>

            <div class="form-group">
                <label for="mesa_id"><b>Mesa</b></label>
                <select name="mesa_id" required>
                    <option value="">Selecciona una mesa</option>
                    @foreach ($mesas as $mesa)
                        <option value="{{ $mesa->codMesa }}" {{ old('mesa_id') == $mesa->codMesa ? 'selected' : '' }}>
                            Mesa {{ $mesa->codMesa }} ({{ $mesa->capacidad }} personas)
                        </option>
                    @endforeach
                </select>
                @error('mesa_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usuario_id"><b>Cliente</b></label>
                <select name="usuario_id" required>
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

            <button type="submit" class="submit-btn">Crear Reserva</button>
            
            <div class="action-links">
                <a href="{{ route('reservas.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>
</body>
</html>
