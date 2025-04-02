<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalles de la Reserva</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lineapedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalles de la Reserva</h1>
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
        
        <div class="reserva-details">
            <div class="detail-item">
                <span class="detail-label">Código de Reserva:</span>
                <span class="detail-value">{{ $reserva->codReserva }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Fecha de la Reserva:</span>
                <span class="detail-value">{{ date('d/m/Y', strtotime($reserva->fecha)) }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Hora de la Reserva:</span>
                <span class="detail-value">{{ $reserva->hora }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Cantidad de Personas:</span>
                <span class="detail-value">{{ $reserva->cantPersona }} personas</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Estado:</span>
                <span class="detail-value">
                    <span class="badge {{ $reserva->reservaConfirmada ? 'badge-confirmada' : 'badge-pendiente' }}">
                        {{ $reserva->reservaConfirmada ? 'Confirmada' : 'Pendiente de confirmación' }}
                    </span>
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Mesa:</span>
                <span class="detail-value">
                    @if(isset($reserva->mesa))
                        Mesa {{ $reserva->mesa->codMesa }} (Capacidad: {{ $reserva->mesa->cantidadMesa }} personas)
                    @else
                        {{ $reserva->mesa_id }}
                    @endif
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Cliente:</span>
                <span class="detail-value">{{ $reserva->usuario->nombre }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Email del Cliente:</span>
                <span class="detail-value">{{ $reserva->usuario->email }}</span>
            </div>
            
            @if(isset($reserva->usuario->telefono))
            <div class="detail-item">
                <span class="detail-label">Teléfono del Cliente:</span>
                <span class="detail-value">{{ $reserva->usuario->telefono }}</span>
            </div>
            @endif
            
            <div class="detail-item">
                <span class="detail-label">Fecha de Creación:</span>
                <span class="detail-value timestamp">{{ $reserva->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Última Actualización:</span>
                <span class="detail-value timestamp">{{ $reserva->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('reservas.edit', $reserva->id) }}" class="action-btn edit-btn">Editar Reserva</a>
            <button type="button" class="action-btn delete-btn" onclick="deleteReserva('{{ $reserva->id }}')">Eliminar Reserva</button>
        </div>
        
        <div class="action-links">
            <a href="{{ route('reservas.paginate') }}" class="link-back">Volver al listado</a>
        </div>
    </div>
    
    <!-- Form oculto para eliminar reserva -->
    <form id="deleteReservaForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Función para eliminar reserva
        function deleteReserva(codReserva) {
            if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                const form = document.getElementById('deleteReservaForm');
                form.action = `/reservas/${codReserva}`;
                form.submit();
            }
        }
    </script>
</body>
</html>