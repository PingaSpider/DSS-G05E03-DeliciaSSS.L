<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito de Compra - Delicias de la Vida</title>
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="{{ asset('js/carrito.js') }}"></script>
    <script src="{{ asset('js/search-products.js') }}"></script>
    <script src="{{ asset('js/sesionHandler.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/sesion.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Delicias de la Vida">
                </a>
            </div>
            <div class="search-bar" id="product-search">
                <input type="text" placeholder="Buscar Productos....">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    |
                    <li><a href="{{ route('menu') }}">Menu</a></li>
                    |
                    <li><a href="{{ route('reservaciones.index') }}">Reservas</a></li>
                </ul>
            </nav>
            <!-- Estructura HTML para el avatar con menú desplegable -->
            <div class="avatar-container">
                    <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" id="avatar" class="avatar" alt="Avatar">
                    <div class="dropdown-menu" id="avatarMenu">
                        @auth
                            <!-- Usuario autenticado: muestra opciones de perfil y cerrar sesión -->
                            <a href="{{ route('user.profile') }}">Mi Perfil</a>
                            
                            <!-- Formulario de logout estilizado como enlace -->
                            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="logout-link-form">
                                @csrf
                                <button type="submit" class="link-button">Cerrar sesión</button>
                            </form>
                        @else
                            <!-- Usuario no autenticado: muestra opciones de login y registro -->
                            <a href="{{ route('login.form') }}">Iniciar sesión</a>
                            <a href="{{ route('registro.form') }}">Registrarse</a>
                        @endauth
                    </div>
                </div>
        </header>

        <!-- Checkout Steps -->
        <div class="checkout-steps">
            <div class="step active" data-step="cart">
                <span class="step-number">1.</span>
                <span class="step-name">Shopping Cart</span>
            </div>
            <div class="step" data-step="details">
                <span class="step-number">2.</span>
                <span class="step-name">Shipping Details</span>
            </div>
            <div class="step" data-step="payment">
                <span class="step-number">3.</span>
                <span class="step-name">Payment Options</span>
            </div>
        </div>

        <!-- Main Content -->
        <main class="content">
            <!-- Shopping Cart Step -->
            <div class="step-content active" id="cart-step">
                <div class="cart-content">
                    <div class="cart-items">
                        <h2>Carrito de Compra</h2>
                        @forelse($carrito->lineasPedido as $item)
                            <div class="cart-item" data-linea-id="{{ $item->linea }}">
                                <div class="item-image">
                                    <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->producto->nombre }}">
                                </div>
                                <div class="item-details">
                                    <h3>{{ $item->producto->nombre }}</h3>
                                    @if($item->producto->comida)
                                        <p>{{ $item->producto->comida->descripcion }}</p>
                                    @elseif($item->producto->bebida)
                                        <p>{{ $item->producto->bebida->tipoBebida }} - {{ $item->producto->bebida->tamanyo }}</p>
                                    @else
                                        <p>{{ $item->producto->nombre }}</p>
                                    @endif
                                    <span class="item-price">${{ number_format($item->precio, 2) }}</span>
                                </div>
                                <div class="item-quantity">
                                    <div class="quantity-selector">
                                        <input type="number" value="{{ $item->cantidad }}" min="1" max="10" 
                                            data-linea="{{ $item->linea }}" class="item-quantity-input">
                                        <div class="quantity-buttons">
                                            <button class="quantity-up" onclick="updateQuantity('{{ $item->linea }}', 1)">+</button>
                                            <button class="quantity-down" onclick="updateQuantity('{{ $item->linea }}', -1)">-</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="item-actions">
                                <button class="btn-remove" onclick="removeFromCart('{{ $item->linea }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                </div>
                            </div>
                        @empty
                            <div class="empty-cart">
                                <p>Tu carrito está vacío</p>
                                <a href="{{ route('producto.show') }}" class="btn-primary">Ver productos</a>
                            </div>
                        @endforelse            
                    </div>
                    
                    <div class="cart-summary">
                        <h2>Resumen de Compra</h2>
                        <div class="summary-total">
                            <span class="total-label">TOTAL</span>
                            <span class="total-amount" id="cart-total">${{ number_format($carrito->total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="cart-actions">
                    <button class="btn-primary next-step" data-next="details">Next</button>
                    <button class="btn-secondary">Cancel</button>
                </div>
            </div>
            
            <!-- Shipping Details Step -->
            <div class="step-content" id="details-step">
                <h2>Detalles de Envío</h2>
                <form class="shipping-form" >
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required value="{{ $cliente->nombre ?? '' }}">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="{{ $cliente->email ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required value="{{ $cliente->telefono ?? '' }}">
                    </div>
                </div>
                </form>
                
                <div class="cart-actions">
                    <button class="btn-primary next-step" data-next="payment">Next</button>
                    <button class="btn-secondary prev-step" data-prev="cart">Back</button>
                </div>
            </div>
            
            <!-- Payment Options Step -->
            <div class="step-content" id="payment-step">
                <h2>Opciones de Pago</h2>
                <form class="payment-form"  method="POST" action="{{ route('carrito.checkout') }}">
                    @csrf
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="tarjeta" name="payment_method" value="tarjeta" checked>
                            <label for="tarjeta">Tarjeta de Crédito/Débito</label>
                            <div class="payment-details">
                                <div class="form-group">
                                    <label for="card-number">Número de Tarjeta</label>
                                    <input type="text" id="card-number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="expiry">Fecha de Expiración</label>
                                        <input type="text" id="expiry" name="expiry" placeholder="MM/AA">
                                    </div>
                                    <div class="form-group">
                                        <label for="cvv">CVV</label>
                                        <input type="text" id="cvv" name="cvv" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-method">
                            <input type="radio" id="paypal" name="payment_method" value="paypal">
                            <label for="paypal">
                                <img src="{{ asset('assets/images/repo/0NqEprKfVi/39JSjYDc0n.png') }}" alt="PayPal" class="paypal-logo">
                            </label>
                        </div>
                        
                        <div class="payment-method">
                            <input type="radio" id="efectivo" name="payment_method" value="efectivo">
                            <label for="efectivo">Efectivo al entregar</label>
                        </div>
                    </div>
                    
                    <div class="cart-summary payment-summary">
                        <h3>Resumen de Compra</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($carrito->total, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Envío</span>
                            <span>$0.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>TOTAL</span>
                            <span>${{ number_format($carrito->total, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="cart-actions">
                        <button type="submit" class="btn-primary">Confirmar Pedido</button>
                        <button type="button" class="btn-secondary prev-step" data-prev="details">Back</button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-logo">
                <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo-naranja.png') }}" alt="Delicias de la Vida">
            </div>
            <div class="footer-info">
                <div class="footer-section">
                    <h3>{{ $footer->direccion->titulo ?? 'Dirección' }}</h3>
                    <p>{{ $footer->direccion->calle ?? 'Calle Los Dolores, 44' }}</p>
                    <p>{{ $footer->direccion->ciudad ?? '03110 Alicante' }}</p>
                </div>
                <div class="footer-section">
                    <h3>{{ $footer->horarios->titulo ?? 'Horarios' }}</h3>
                    <p>{{ $footer->horarios->semana ?? 'Lun-Vie: 12:30 - 17:00' }}</p>
                    <p>{{ $footer->horarios->finde ?? 'Sáb-Dom: 12:30 - 24:00' }}</p>
                </div>
                <div class="footer-section">
                    <h3>{{ $footer->contacto->titulo ?? 'Contáctanos' }}</h3>
                    <p>{{ $footer->contacto->email ?? 'delicias@gmail.com' }}</p>
                    <p>{{ $footer->contacto->telefono ?? 'Tel: 678-45-20-16' }}</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>