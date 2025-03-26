<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalles de Mesa</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/showmesa.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalles de la Mesa</h1>
        <hr>
        
        <div class="mesa-details">
            <div class="detail-item">
                <span class="detail-label">Código de Mesa:</span>
                <span class="detail-value">{{ $mesa->codMesa }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Capacidad:</span>
                <span class="detail-value">{{ $mesa->cantidadMesa }} personas</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Estado:</span>
                <span class="detail-value status-badge {{ $mesa->ocupada ? 'status-ocupada' : 'status-libre' }}">
                    {{ $mesa->ocupada ? 'Ocupada' : 'Libre' }}
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Fecha de Registro:</span>
                <span class="detail-value">{{ $mesa->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Última Actualización:</span>
                <span class="detail-value">{{ $mesa->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('mesas.edit', $mesa->codMesa) }}" class="action-btn edit-btn">Editar Mesa</a>
            
            <form action="{{ route('mesas.destroy', $mesa->codMesa) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta mesa?')">Eliminar Mesa</button>
            </form>
        </div>
        
        <div class="action-links">
            <a href="{{ route('mesas.paginate') }}" class="link-back">Volver al listado</a>
        </div>
    </div>
    
    <style>
        .mesa-details {
            background-color: #fff2d6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .detail-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1.1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
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