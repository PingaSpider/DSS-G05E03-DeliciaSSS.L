<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalles de Línea de Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lineapedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalles de Línea de Pedido</h1>
        <hr>
        
        <div class="lineapedido-details">
            <div class="detail-item">
                <span class="detail-label">Código de Línea:</span>
                <span class="detail-value">{{ $lineaPedido->linea }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Pedido:</span>
                <span class="detail-value">
                    <a href="{{ route('pedidos.show', $lineaPedido->pedido->cod) }}" class="link-detail">
                        {{ $lineaPedido->pedido->cod }} 
                        ({{ date('d/m/Y', strtotime($lineaPedido->pedido->fecha)) }})
                    </a>
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Cliente:</span>
                <span class="detail-value">
                    <a href="{{ route('usuarios.show', $lineaPedido->pedido->usuario->id) }}" class="link-detail">
                        {{ $lineaPedido->pedido->usuario->nombre }}
                    </a>
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Producto:</span>
                <span class="detail-value">{{ $lineaPedido->producto->nombre }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Descripción del Producto:</span>
                <span class="detail-value description">
                    {{ $lineaPedido->producto->descripcion ?? 'Sin descripción' }}
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Cantidad:</span>
                <span class="detail-value">{{ $lineaPedido->cantidad }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Precio Unitario:</span>
                <span class="detail-value">{{ number_format($lineaPedido->precio, 2, ',', '.') }} €</span>
            </div>
            
            <div class="detail-item subtotal-item">
                <span class="detail-label">Subtotal:</span>
                <span class="detail-value subtotal">{{ number_format($lineaPedido->subtotal, 2, ',', '.') }} €</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Fecha de Creación:</span>
                <span class="detail-value timestamp">{{ $lineaPedido->created_at ? $lineaPedido->created_at->format('d/m/Y H:i') : 'No disponible' }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Última Actualización:</span>
                <span class="detail-value timestamp">{{ $lineaPedido->updated_at ? $lineaPedido->updated_at->format('d/m/Y H:i') : 'No disponible' }}</span>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('lineaPedidos.edit', $lineaPedido->linea) }}" class="action-btn edit-btn">Editar Línea</a>
            
            <form action="{{ route('lineaPedidos.destroy', $lineaPedido->linea) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta línea de pedido?')">Eliminar Línea</button>
            </form>
        </div>
        
        <div class="related-info">
            <h2>Información del Pedido</h2>
            <div class="related-details">
                <div class="related-item">
                    <span class="related-label">Estado del Pedido:</span>
                    <span class="related-value">
                        <span class="badge 
                            @if($lineaPedido->pedido->estado == 'Pendiente') badge-pendiente 
                            @elseif($lineaPedido->pedido->estado == 'En proceso') badge-proceso 
                            @elseif($lineaPedido->pedido->estado == 'Completado') badge-completado 
                            @elseif($lineaPedido->pedido->estado == 'Cancelado') badge-cancelado 
                            @endif">
                            {{ $lineaPedido->pedido->estado }}
                        </span>
                    </span>
                </div>                
                <a href="{{ route('pedidos.show', $lineaPedido->pedido->cod) }}" class="view-related">Ver Pedido Completo</a>
            </div>
        </div>
        
        <div class="action-links">
            <a href="{{ route('lineaPedidos.paginate') }}" class="link-back">Volver al listado</a>
        </div>
    </div>
</body>
</html>