<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalles del Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalles del Pedido</h1>
        <hr>
        
        <div class="pedido-details">
            <div class="detail-item">
                <span class="detail-label">Código del Pedido:</span>
                <span class="detail-value">{{ $pedido->cod }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Fecha:</span>
                <span class="detail-value">{{ date('d/m/Y', strtotime($pedido->fecha)) }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Estado:</span>
                <span class="detail-value">
                    <span class="badge 
                        @if($pedido->estado == 'Pendiente') badge-pendiente 
                        @elseif($pedido->estado == 'En proceso') badge-proceso 
                        @elseif($pedido->estado == 'Completado') badge-completado 
                        @elseif($pedido->estado == 'Cancelado') badge-cancelado 
                        @endif">
                        {{ $pedido->estado }}
                    </span>
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Cliente:</span>
                <span class="detail-value">{{ $pedido->usuario->nombre }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Email del Cliente:</span>
                <span class="detail-value">{{ $pedido->usuario->email }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Teléfono del Cliente:</span>
                <span class="detail-value">{{ $pedido->usuario->telefono }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Fecha de Creación:</span>
                <span class="detail-value timestamp">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Última Actualización:</span>
                <span class="detail-value timestamp">{{ $pedido->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        @if(count($pedido->lineasPedido) > 0)
        <div class="lineas-pedido">
            <h2>Líneas del Pedido</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($pedido->lineasPedido as $linea)
                        <tr>
                            <td>{{ $linea->producto->nombre }}</td>
                            <td>{{ $linea->cantidad }}</td>
                            <td>{{ number_format($linea->precio, 2, ',', '.') }} €</td>
                            <td>{{ number_format($linea->cantidad * $linea->precio, 2, ',', '.') }} €</td>
                        </tr>
                        @php $total += $linea->cantidad * $linea->precio; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                            <td style="font-weight: bold;">{{ number_format($total, 2, ',', '.') }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @else
        <div class="highlight-box">
            <div class="title">Sin líneas de pedido</div>
            <div class="content">Este pedido no tiene productos asociados.</div>
        </div>
        @endif
        
        <div class="action-buttons">
            <a href="{{ route('pedidos.edit', $pedido->cod) }}" class="action-btn edit-btn">Editar Pedido</a>
            
            <form action="{{ route('pedidos.destroy', $pedido->cod) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?')">Eliminar Pedido</button>
            </form>
        </div>
        
        <div class="action-links">
            <a href="{{ route('pedidos.paginate') }}" class="link-back">Volver al listado</a>
        </div>
    </div>
    
    <style>
        tfoot td {
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }
        
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


<!--Entrega2-->