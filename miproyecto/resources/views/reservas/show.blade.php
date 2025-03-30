<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalles de la Reserva</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reserva/show.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalles de la Reserva</h1>
        <hr>
        
        <div class="reserva-details">
            <div class="detail-item">
                <span class="detail-label">Código de Reserva:</span>
                <span class="detail-value">{{ $reserva->cod }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Fecha de la Reserva:</span>
                <span class="detail-value">{{ date('d/m/Y', strtotime($reserva->fecha)) }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Estado:</span>
                <span class="detail-value">
                    <span class="badge 
                        @if($reserva->estado == 'Pendiente') badge-pendiente 
                        @elseif($reserva->estado == 'Confirmada') badge-confirmada 
                        @elseif($reserva->estado == 'Cancelada') badge-cancelada 
                        @endif">
                        {{ $reserva->estado }}
                    </span>
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
            
            <div class="detail-item">
                <span class="detail-label">Teléfono del Cliente:</span>
                <span class="detail-value">{{ $reserva->usuario->telefono }}</span>
            </div>
            
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
            <a href="{{ route('reservas.edit', $reserva->cod) }}" class="action-btn edit-btn">Editar Reserva</a>
            
            <form action="{{ route('reservas.destroy', $reserva->cod) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reserva?')">Eliminar Reserva</button>
            </form>
        </div>
        
        <div class="action-links">
            <a href="{{ route('reservas.paginate') }}" class="link-back">Volver al listado</a>
        </div>
    </div>
    
    <style>
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>
