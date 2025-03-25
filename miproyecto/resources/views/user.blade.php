<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
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
                <h2 class="user-name">{{ $user->nombre ?? 'Nombre' }} {{ $user->apellido ?? 'y Apellidos' }}</h2>
            </div>
        </header>

        <!-- Tabs de navegación -->
        <div class="tabs">
            <button class="tab-button active" data-tab="cuenta">Mi Cuenta</button>
            <button class="tab-button" data-tab="direcciones">Mis Direcciones</button>
            <button class="tab-button" data-tab="pedidos">Mis Pedidos</button>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tab-content">
            <!-- Tab Mi Cuenta -->
            <div id="cuenta" class="tab-pane active">
                <form class="user-form">
                    @csrf
                    @method('PUT')
                    <div class="content-box">
                        <h3>Información personal</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ $user->nombre ?? 'Antonio' }}">
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" value="{{ $user->apellido ?? 'Pérez Pérez' }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="{{ $user->telefono ?? '697286731' }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ $user->email ?? 'antonioperez@gmail.com' }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-secondary">Descartar</button>
                        <button type="submit" class="btn-primary">Actualizar información</button>
                    </div>
                </form>
            </div>
            

            <!-- Tab Mis Direcciones -->
            <div id="direcciones" class="tab-pane">
                <div class="content-box">
                    <h3>Mis direcciones de envío</h3>
                    
                    <div class="address-list">
                        @forelse($direcciones ?? [] as $direccion)
                            <div class="address-card">
                                <div class="address-header">
                                    <h4>{{ $direccion->nombre }}</h4>
                                    @if($direccion->predeterminada)
                                        <span class="default-badge">Predeterminada</span>
                                    @endif
                                </div>
                                <p>{{ $direccion->contacto }}</p>
                                <p>{{ $direccion->calle }}</p>
                                <p>{{ $direccion->codigoPostal }} {{ $direccion->ciudad }}, {{ $direccion->pais }}</p>
                                <p>Tel: {{ $direccion->telefono }}</p>
                                <div class="address-actions">
                                    <button class="btn-secondary" onclick="editarDireccion({{ $direccion->id }})">Editar</button>
                                    <button class="btn-text" onclick="eliminarDireccion({{ $direccion->id }})">Eliminar</button>
                                    @if(!$direccion->predeterminada)
                                        <button class="btn-text" onclick="establecerPredeterminada({{ $direccion->id }})">Establecer como predeterminada</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="address-card">
                                <div class="address-header">
                                    <h4>Casa</h4>
                                    <span class="default-badge">Predeterminada</span>
                                </div>
                                <p>Antonio Pérez Pérez</p>
                                <p>Calle Principal 123, Piso 2B</p>
                                <p>28001 Madrid, España</p>
                                <p>Tel: 697286731</p>
                                <div class="address-actions">
                                    <button class="btn-secondary">Editar</button>
                                    <button class="btn-text">Eliminar</button>
                                </div>
                            </div>
                            
                            <div class="address-card">
                                <div class="address-header">
                                    <h4>Trabajo</h4>
                                </div>
                                <p>Antonio Pérez Pérez</p>
                                <p>Avenida Comercial 45, Edificio Torre</p>
                                <p>28003 Madrid, España</p>
                                <p>Tel: 697286731</p>
                                <div class="address-actions">
                                    <button class="btn-secondary">Editar</button>
                                    <button class="btn-text">Eliminar</button>
                                    <button class="btn-text">Establecer como predeterminada</button>
                                </div>
                            </div>
                        @endforelse
                        
                        <button class="btn-primary add-address" >+ Añadir nueva dirección</button>
                    </div>
                </div>
            </div>

            <!-- Tab Mis Pedidos -->
            <div id="pedidos" class="tab-pane">
                <div class="content-box">
                    <h3>Historial de pedidos</h3>
                    
                    <div class="order-filters">
                        <select class="filter-select" id="periodo-filtro">
                            <option value="all">Todos los pedidos</option>
                            <option value="30">Últimos 30 días</option>
                            <option value="180">Últimos 6 meses</option>
                            <option value="year">Este año</option>
                        </select>
                        <input type="search" placeholder="Buscar pedido..." class="search-input" id="buscar-pedido">
                    </div>
                    
                    <div class="orders-list">
                        @forelse($pedidos ?? [] as $pedido)
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <h4>Pedido #{{ $pedido->numero }}</h4>
                                        <span class="order-date">{{ \Carbon\Carbon::parse($pedido->fecha)->format('d F Y') }}</span>
                                    </div>
                                    <span class="order-status {{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span>
                                </div>
                                <div class="order-details">
                                    <div class="order-products">
                                        <img src="{{ $pedido->productos[0]->imagen ?? asset('product-placeholder.png') }}" alt="Producto" class="product-thumbnail" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'25\' y=\'30\' font-family=\'Arial\' font-size=\'10\' text-anchor=\'middle\' fill=\'%23999\' %3EProducto%3C/text%3E%3C/svg%3E';">
                                        <span class="product-count">+{{ count($pedido->productos) - 1 }} productos</span>
                                    </div>
                                    <div class="order-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-amount">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
                                    </div>
                                    <div class="order-actions">
                                        <button class="btn-secondary" onclick="window.location.href='{{ route('pedidos.show', $pedido->id) }}'">Ver detalles</button>
                                        <button class="btn-text" onclick="repetirPedido({{ $pedido->id }})">Repetir pedido</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <h4>Pedido #10234</h4>
                                        <span class="order-date">15 marzo 2025</span>
                                    </div>
                                    <span class="order-status delivered">Entregado</span>
                                </div>
                                <div class="order-details">
                                    <div class="order-products">
                                        <img src="{{ asset('product-placeholder.png') }}" alt="Producto" class="product-thumbnail" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'25\' y=\'30\' font-family=\'Arial\' font-size=\'10\' text-anchor=\'middle\' fill=\'%23999\' %3EProducto%3C/text%3E%3C/svg%3E';">
                                        <span class="product-count">+2 productos</span>
                                    </div>
                                    <div class="order-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-amount">89,95 €</span>
                                    </div>
                                    <div class="order-actions">
                                        <button class="btn-secondary">Ver detalles</button>
                                        <button class="btn-text">Repetir pedido</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <h4>Pedido #10189</h4>
                                        <span class="order-date">24 febrero 2025</span>
                                    </div>
                                    <span class="order-status delivered">Entregado</span>
                                </div>
                                <div class="order-details">
                                    <div class="order-products">
                                        <img src="{{ asset('product-placeholder.png') }}" alt="Producto" class="product-thumbnail" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'25\' y=\'30\' font-family=\'Arial\' font-size=\'10\' text-anchor=\'middle\' fill=\'%23999\' %3EProducto%3C/text%3E%3C/svg%3E';">
                                        <span class="product-count">+1 producto</span>
                                    </div>
                                    <div class="order-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-amount">45,50 €</span>
                                    </div>
                                    <div class="order-actions">
                                        <button class="btn-secondary">Ver detalles</button>
                                        <button class="btn-text">Repetir pedido</button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>