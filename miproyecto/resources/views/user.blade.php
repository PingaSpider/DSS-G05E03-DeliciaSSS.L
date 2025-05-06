<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Delicias de la Vida</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <script src="{{ asset('js/user.js') }}" defer></script>
</head>
<body>
    <div class="profile-container">
        <!-- Header con información del usuario -->
        <header class="user-header">
            <div class="user-info">
                <div class="user-avatar">
                    <img src="{{ $user->avatar ?? asset('avatar-placeholder.png') }}" alt="Avatar de usuario" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Ccircle cx=\'25\' cy=\'25\' r=\'25\' fill=\'%23f5f5f5\'/%3E%3Ccircle cx=\'25\' cy=\'20\' r=\'8\' fill=\'%23c0c0c0\'/%3E%3Cpath d=\'M12,35 a13,10 0 0,0 26,0\' fill=\'%23c0c0c0\'/%3E%3C/svg%3E';">
                </div>
                <h2 class="user-name">{{ $user->nombre ?? 'Nombre' }} {{ $user->apellido ?? '' }}</h2>
            </div>
            <a href="{{ route('home') }}" class="back-to-home">Home</a>
            <a href="{{ route('logout') }}" class="logout-button">Cerrar sesión</a>
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

        <!-- Tabs de navegación -->
        <div class="tabs">
            <button class="tab-button active" data-tab="cuenta">Mi Cuenta</button>
            <button class="tab-button" data-tab="pedidos">Mis Pedidos</button>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tab-content">
            <!-- Tab Mi Cuenta -->
            <div id="cuenta" class="tab-pane active">
                <form class="user-form" action="{{ route('user.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="content-box">
                        <h3>Información personal</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre y Apellidos</label>
                                <input type="text" id="nombre" name="nombre" value="{{ $user->nombre }}">
                                @error('nombre')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="{{ $user->telefono }}">
                            @error('telefono')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}">
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="resetForm()">Descartar</button>
                        <button type="submit" class="btn-primary">Actualizar información</button>
                    </div>
                </form>
            </div>

            <!-- Tab Mis Pedidos -->
            <div id="pedidos" class="tab-pane">
                <div class="content-box">
                    <h3>Historial de pedidos</h3>
                    
                    <div class="order-filters">
                        <form action="{{ route('user.profile') }}" method="GET" id="filter-form">
                            <input type="hidden" name="tab" value="pedidos">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            
                            <div class="filter-row">
                                <div class="filter-group">
                                    <label for="fecha_desde">Desde:</label>
                                    <input type="date" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}" class="date-input">
                                </div>
                                
                                <div class="filter-group">
                                    <label for="fecha_hasta">Hasta:</label>
                                    <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="date-input">
                                </div>
                                
                                <div class="filter-group">
                                    <label for="num_pedido">Nº Pedido:</label>
                                    <input type="text" id="num_pedido" name="num_pedido" value="{{ request('num_pedido') }}" placeholder="P0001" class="pedido-input">
                                </div>
                                
                                <div class="filter-group">
                                    <label for="estado">Estado:</label>
                                    <select id="estado" name="estado" class="estado-select">
                                        <option value="">Todos</option>
                                        <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="En Preparación" {{ request('estado') == 'En Preparación' ? 'selected' : '' }}>En Preparación</option>
                                        <option value="Listo" {{ request('estado') == 'Listo' ? 'selected' : '' }}>Listo</option>
                                        <option value="Entregado" {{ request('estado') == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                                        <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                                
                                <div class="filter-actions">
                                    <button type="submit" class="btn-primary">Filtrar</button>
                                    <a href="{{ route('user.profile', ['tab' => 'pedidos']) }}" class="btn-text">Limpiar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="orders-list">
                        @forelse($pedidos ?? [] as $pedido)
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <h4>Pedido #{{ $pedido->cod }}</h4>
                                        <span class="order-date">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d F Y') }}</span>
                                    </div>
                                    <span class="order-status {{ strtolower($pedido->estado) }}">{{ $pedido->estado }}</span>
                                </div>
                                <div class="order-details">
                                    <div class="order-products">
                                        @if(isset($pedido->lineasPedido) && count($pedido->lineasPedido) > 0)
                                            @php
                                                $firstLine = $pedido->lineasPedido->first();
                                                $producto = $firstLine->producto ?? null;
                                            @endphp
                                            <img src="{{ $producto && isset($producto->imagen) ? $producto->imagen : asset('product-placeholder.png') }}" 
                                                alt="Producto" class="product-thumbnail" 
                                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'25\' y=\'30\' font-family=\'Arial\' font-size=\'10\' text-anchor=\'middle\' fill=\'%23999\' %3EProducto%3C/text%3E%3C/svg%3E';">
                                            <span class="product-count">{{ count($pedido->lineasPedido) }} {{ count($pedido->lineasPedido) == 1 ? 'producto' : 'productos' }}</span>
                                        @else
                                            <img src="{{ asset('product-placeholder.png') }}" alt="Producto" class="product-thumbnail" 
                                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'25\' y=\'30\' font-family=\'Arial\' font-size=\'10\' text-anchor=\'middle\' fill=\'%23999\' %3EProducto%3C/text%3E%3C/svg%3E';">
                                            <span class="product-count">Sin detalles</span>
                                        @endif
                                    </div>
                                    <div class="order-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-amount">{{ number_format($pedido->total ?? 0, 2, ',', '.') }} €</span>
                                    </div>
                                    <div class="order-actions">
                                        <a href="{{ route('user.showOrder', $pedido->cod) }}" class="btn-secondary">Ver detalles</a>
                                        <form action="{{ route('user.repeatOrder', $pedido->cod) }}" method="POST" style="display:inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn-text">Repetir pedido</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <p>No se encontraron pedidos con los filtros seleccionados.</p>
                                <a href="{{ route('menu') }}" class="btn-primary">Ver nuestro menú</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Desactivar todas las pestañas
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    // Activar la pestaña seleccionada
                    button.classList.add('active');
                    document.getElementById(button.dataset.tab).classList.add('active');
                });
            });
            
            
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
        });
    </script>
</body>
</html>