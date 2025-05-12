<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $producto->nombre ?? 'Detalle de Producto' }} - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/producto.css') }}">
    <script src="{{ asset('js/producto.js') }}"></script>
    <script src="{{ asset('js/sesionHandler.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/sesion.css') }}">
</head>
<body>
    <div class="container">
         <!-- Header -->
         <header>
            <div class="navigation-container">
                <!-- Logo (a la izquierda) -->
                <div class="logo-container">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Delicias de la Vida" class="logo-1">
                    </a>
                </div>
                
                <!-- Navegación (al centro) -->
                <div class="link-bar-1">
                    <a href="{{ route('home') }}" class="link-bar-name">Home</a>
                    |
                    <a href="{{ route('menu') }}" class="link-bar-name">Menu</a>
                    |
                    @auth
                        <a href="{{ route('reservaciones.index') }}" class="link-bar-name">Reservar</a>
                    @else
                    <a href="javascript:void(0)" 
                        class="link-bar-name auth-required"
                        data-message="Es necesario tener una cuenta para reservar"
                        data-login-url="{{ route('login.form') }}">Reservar</a>
                    @endauth
                </div>
                
                <!-- Acciones (a la derecha) -->
                <div class="actions">
                    <!-- Botón de carrito -->
                    @auth
                        <a href="{{ route('carrito.view') }}" class="to-cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    @endauth
                    
                    <!-- Avatar de usuario -->
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
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content">
            <h1 class="page-title">Productos</h1>
            
            <!-- Product Detail Section -->
            <div class="product-detail">
                <div class="product-image-container">
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="product-main-image">
                </div>
                
                <div class="product-info">
                    <h2 class="product-title" id="product-title">{{ $producto->nombre }}</h2>
                    
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 0; $i < $producto->rating; $i++)
                                <span class="star">★</span>
                            @endfor
                        </div>
                        <span class="reviews-count">{{ $producto->reviews_count }} reviews</span>
                    </div>
                    
                    <div class="product-price" id="product-price">${{ number_format($producto->pvp, 2) }}</div>
                    
                    @if($producto->comida)
                    <div class="product-description" id="product-description">
                        <p>{{ $producto->comida->descripcion }}</p>
                    </div>
                    @elseif($producto->bebida)
                    <div class="product-description" id="product-description">
                        <p>{{ $producto->bebida->tipoBebida }} - {{ $producto->bebida->tamanyo }}</p>
                    </div>
                    @else
                    <div class="product-description" id="product-description">
                        <p>Delicioso {{ strtolower($producto->nombre) }} preparado con los mejores ingredientes.</p>
                    </div>
                    @endif
                    
                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus" data-product="{{ $producto->cod }}">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" value="1" min="1" max="10" data-product="{{ $producto->cod }}">
                            <button class="quantity-btn plus" data-product="{{ $producto->cod }}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="add-to-cart-btn" data-product="{{ $producto->cod }}">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>

                    @if($producto->stock < 10 && $producto->stock > 0)
                    <div class="stock-warning">
                        <p>¡Solo quedan {{ $producto->stock }} unidades!</p>
                    </div>
                    @elseif($producto->stock == 0)
                    <div class="stock-warning out-of-stock">
                        <p>Producto agotado</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Similar Products Section -->
            <section class="similar-products">
                <h2>Similar Products</h2>
                
                <div class="product-grid">
                    @forelse($similarProducts as $similar)
                        @include('partials.product-card', ['producto' => $similar])
                    @empty
                        <p>No hay productos similares disponibles.</p>
                    @endforelse
                </div>
            </section>
            
            <!-- Reviews Section -->
            <section class="reviews">
                <h2>Reviews</h2>
                
                @forelse($reviews as $review)
                    <div class="review">
                        <div class="review-avatar">
                            <img src="{{ $review->usuario->avatar }}" alt="{{ $review->usuario->nombre }}" onerror="this.src='{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}'" id="review-avatar">
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <div class="reviewer-name">{{ $review->usuario->nombre }}</div>
                                <div class="review-date">{{ \Carbon\Carbon::parse($review->fecha)->format('d/m/Y') }}</div>
                            </div>
                            <div class="review-rating">
                                @for($i = 0; $i < $review->rating; $i++)
                                    ★
                                @endfor
                            </div>
                            <div class="review-text">
                                <p>{{ $review->comentario }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No hay reseñas para este producto aún.</p>
                @endforelse
            </section>
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