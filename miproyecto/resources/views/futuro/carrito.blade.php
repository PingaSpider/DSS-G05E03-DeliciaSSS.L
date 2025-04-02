<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compra - Delicias de la Vida</title>
    <link rel="stylesheet" href="{{ asset('css/cssFuturo/carrito.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7b1fbf0d4d.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/carrito.js') }}"></script>
    
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/repo/0NqEprKrYi/delicias-logo.png') }}" alt="Delicias de la Vida">
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('reserva') }}">Reservas</a></li>
                    <li><a href="{{ route('menu') }}">Menu</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </nav>
            <div class="actions">
                <button class="btn-primary">Pedir Online</button>
                <a href="#" class="user-icon">
                    <img src="{{ asset('assets/repo/E-commerce_Shop_Avatar_1.png') }}" alt="Usuario" class="icon">
                </a>
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
                        
                        @forelse($cartItems ?? [] as $item)
                            <div class="cart-item">
                                <div class="item-image">
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                </div>
                                <div class="item-details">
                                    <h3>{{ $item['name'] }}</h3>
                                    <p>{{ $item['description'] }}</p>
                                    <span class="item-price">${{ $item['price'] }}</span>
                                </div>
                                <div class="item-quantity">
                                    <div class="quantity-selector">
                                        <input type="number" value="{{ $item['quantity'] }}" min="1" max="10" 
                                               data-id="{{ $item['id'] }}" class="item-quantity-input">
                                        <div class="quantity-buttons">
                                            <button class="quantity-up">+</button>
                                            <button class="quantity-down">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Datos por defecto si no hay items en el carrito -->
                            <div class="cart-item">
                                <div class="item-image">
                                    <img src="{{ asset('assets/repo/comida/cheseburguer.png') }}" alt="Hamburguesa de Cheddar">
                                </div>
                                <div class="item-details">
                                    <h3>HAMBURGUESA DE CHEDDAR</h3>
                                    <p>Hamburguesa con queso</p>
                                    <span class="item-price">$7.50</span>
                                </div>
                                <div class="item-quantity">
                                    <div class="quantity-selector">
                                        <input type="number" value="1" min="1" max="10" data-id="1" class="item-quantity-input">
                                        <div class="quantity-buttons">
                                            <button class="quantity-up">+</button>
                                            <button class="quantity-down">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="cart-item">
                                <div class="item-image">
                                    <img src="{{ asset('assets/repo/comida/cocacola_zero.png') }}" alt="Coca Cola Zero">
                                </div>
                                <div class="item-details">
                                    <h3>COCA COLA ZERO</h3>
                                    <p>Tamaño Mediano</p>
                                    <span class="item-price">$3.50</span>
                                </div>
                                <div class="item-quantity">
                                    <div class="quantity-selector">
                                        <input type="number" value="1" min="1" max="10" data-id="2" class="item-quantity-input">
                                        <div class="quantity-buttons">
                                            <button class="quantity-up">+</button>
                                            <button class="quantity-down">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="cart-summary">
                        <h2>Resumen de Compra</h2>
                        <div class="summary-total">
                            <span class="total-label">TOTAL</span>
                            <span class="total-amount">${{ $total ?? '11.00' }}</span>
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
                    
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" required value="{{ $cliente->direccion ?? '' }}">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input type="text" id="ciudad" name="ciudad" required value="{{ $cliente->ciudad ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="cp">Código Postal</label>
                            <input type="text" id="cp" name="cp" required value="{{ $cliente->cp ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="instrucciones">Instrucciones de entrega (opcional)</label>
                        <textarea id="instrucciones" name="instrucciones" rows="3">{{ $cliente->instrucciones ?? '' }}</textarea>
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
                <form class="payment-form"  method="POST">
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
                            <label for="paypal">PayPal</label>
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
                            <span>${{ $subtotal ?? '11.00' }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Envío</span>
                            <span>${{ $envio ?? '0.00' }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>TOTAL</span>
                            <span>${{ $total ?? '11.00' }}</span>
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
                <img src="{{ asset('assets/repo/0NqEprKrYi/delicias-logo.png') }}" alt="Delicias de la Vida">
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