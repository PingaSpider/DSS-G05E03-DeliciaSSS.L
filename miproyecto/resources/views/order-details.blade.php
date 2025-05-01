<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido #{{ $pedido->cod }} - Delicias de la Vida</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-details.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
</head>
<body>
    <div class="order-container">
        <!-- Header -->
        <header class="order-header">
            <div class="back-navigation">
                <a href="{{ route('user.profile', ['tab' => 'pedidos']) }}" class="back-link">
                    <span class="back-icon">←</span> Volver a mis pedidos
                </a>
            </div>
            <h1>Detalles del Pedido #{{ $pedido->cod }}</h1>
        </header>

        <!-- Mostrar mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Resumen del pedido -->
        <div class="order-summary">
            <div class="order-info">
                <div class="info-group">
                    <h3>Información del pedido</h3>
                    <div class="info-row">
                        <span class="info-label">Fecha:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value status-badge {{ strtolower($pedido->estado) }}">{{ $pedido->estado }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total:</span>
                        <span class="info-value total-value">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
                    </div>
                </div>
            </div>
            
            <!-- Acciones del pedido -->
            <div class="order-actions">
                <form action="{{ route('user.repeatOrder', $pedido->cod) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $pedido->usuario_id }}">
                    <button type="submit" class="btn-primary">Repetir pedido</button>
                </form>
                
                @if($pedido->estado === 'Pendiente')
                <form action="{{ route('user.cancelOrder', $pedido->cod) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $pedido->usuario_id }}">
                    <button type="submit" class="btn-secondary" onclick="return confirm('¿Estás seguro de cancelar este pedido?')">Cancelar pedido</button>
                </form>
                @endif
            </div>
        </div>

        <!-- Productos del pedido -->
        <div class="order-products">
            <h2>Productos</h2>
            
            <div class="product-list">
                @forelse($pedido->lineasPedido as $linea)
                <div class="product-item">
                    <div class="product-image">
                        <img src="{{ $linea->producto->imagen ?? asset('product-placeholder.png') }}" alt="{{ $linea->producto->nombre ?? 'Producto' }}" 
                            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'%3E%3Crect width=\'80\' height=\'80\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'40\' y=\'40\' font-family=\'Arial\' font-size=\'12\' text-anchor=\'middle\' fill=\'%23999\' %3EProducto%3C/text%3E%3C/svg%3E';">
                    </div>
                    <div class="product-details">
                        <div class="product-name">{{ $linea->producto->nombre ?? 'Producto no disponible' }}</div>
                        <div class="product-price">{{ number_format($linea->precio, 2, ',', '.') }} € x {{ $linea->cantidad }}</div>
                    </div>
                    <div class="product-subtotal">
                        {{ number_format($linea->precio * $linea->cantidad, 2, ',', '.') }} €
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <p>No hay productos en este pedido.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Resumen de costos -->
            <div class="cost-summary">
                <div class="cost-row">
                    <span class="cost-label">Subtotal:</span>
                    <span class="cost-value">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
                </div>
                <div class="cost-row">
                    <span class="cost-label">Gastos de envío:</span>
                    <span class="cost-value">{{ number_format(0, 2, ',', '.') }} €</span>
                </div>
                <div class="cost-row total-row">
                    <span class="cost-label">Total:</span>
                    <span class="cost-value">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
                </div>
            </div>
        </div>
        
        <!-- Seguimiento del pedido -->
        @if($pedido->estado !== 'Cancelado')
        <div class="order-tracking">
            <h2>Seguimiento del pedido</h2>
            
            <div class="tracking-steps">
                <div class="step {{ in_array($pedido->estado, ['Pendiente', 'En Preparación', 'Listo', 'Entregado']) ? 'active' : '' }}">
                    <div class="step-icon">1</div>
                    <div class="step-info">
                        <h4>Pedido recibido</h4>
                        <p>Tu pedido ha sido registrado.</p>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step {{ in_array($pedido->estado, ['En Preparación', 'Listo', 'Entregado']) ? 'active' : '' }}">
                    <div class="step-icon">2</div>
                    <div class="step-info">
                        <h4>Preparando</h4>
                        <p>Tu pedido está siendo preparado.</p>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step {{ in_array($pedido->estado, ['Listo', 'Entregado']) ? 'active' : '' }}">
                    <div class="step-icon">3</div>
                    <div class="step-info">
                        <h4>Listo para entrega</h4>
                        <p>Tu pedido está listo y en camino.</p>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step {{ $pedido->estado === 'Entregado' ? 'active' : '' }}">
                    <div class="step-icon">4</div>
                    <div class="step-info">
                        <h4>Entregado</h4>
                        <p>¡Tu pedido ha sido entregado!</p>
                    </div>
                </div>
            </div>
            
            @if($pedido->estado !== 'Entregado')
            <div class="tracking-message">
                <p>Estimado tiempo de entrega: 30-45 minutos</p>
            </div>
            @endif
        </div>
        @endif
        
        <!-- Acciones finales -->
        <div class="footer-actions">
            <a href="{{ route('menu') }}" class="btn-text">Ver menú</a>
            <a href="{{ route('user.profile', ['tab' => 'pedidos']) }}" class="btn-text">Volver a mis pedidos</a>
        </div>
    </div>

    <script>
        // Mostrar mensaje de éxito/error durante 5 segundos
        const alerts = document.querySelectorAll('.alert');
        if (alerts.length > 0) {
            setTimeout(function() {
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                });
            }, 5000);
        }
    </script>
</body>
</html>