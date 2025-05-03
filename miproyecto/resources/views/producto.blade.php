<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $producto->nombre ?? 'Detalle de Producto' }} - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7b1fbf0d4d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/cssFuturo/producto.css') }}">
    <script src="{{ asset('js/producto.js') }}"></script>
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
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('menu') }}">Menu</a></li>
                    <li><a href="{{ route('reservaciones.index') }}">Reservas</a></li>
                </ul>
            </nav>
            <div class="actions">
                <button class="btn-primary">Pedir Online</button>
                <a class="user-icon">
                    <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" alt="Usuario" class="icon">
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content">
            <h1 class="page-title">Productos</h1>
            
            <!-- Product Detail Section -->
            <div class="product-detail">
                <div class="product-image-container">
                    <img src="{{ $producto->imagen ?? asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/especialburguer.png') }}" alt="{{ $producto->nombre ?? 'Hamburguesa especial' }}" class="product-main-image">
                </div>
                
                <div class="product-info">
                    <h2 class="product-title" id="product-title">{{ $producto->nombre ?? 'Hamburguesa especial' }}</h2>
                    
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 0; $i < ($producto->rating ?? 5); $i++)
                                <span class="star">★</span>
                            @endfor
                        </div>
                        <span class="reviews-count">{{ $producto->reviews_count ?? 4 }} reviews</span>
                    </div>
                    
                    <div class="product-price" id="product-price">$ {{ $producto->precio ?? '18' }}</div>
                    
                    <div class="product-description" id="product-description">
                        <p>{{ $producto->descripcion ?? 'Carne de vacuno con tomate, lechuga, queso y salsa de bacon' }}</p>
                    </div>
                    
                    <div class="product-actions">
                        <form>
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id ?? 1 }}">
                            <button type="submit" class="btn-primary add-to-cart">Add To Cart</button>
                        </form>
                        <button class="btn-wishlist" onclick="toggleWishlist({{ $producto->id ?? 1 }})">
                            <i class="heart-icon">♡</i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Similar Products Section -->
            <section class="similar-products">
                <h2>Similar Products</h2>
                
                <div class="product-grid">
                    @forelse($similarProducts ?? [] as $similar)
                        <div class="product-card" data-product-id="{{ $similar->id }}">
                            <div class="product-image">
                                <img src="{{ $similar->imagen }}" alt="{{ $similar->nombre }}" class="product-thumbnail">
                            </div>
                            <div class="product-card-info">
                                <div class="product-name">{{ $similar->nombre }}</div>
                                <div class="product-card-rating">{{ str_repeat('★ ', $similar->rating) }}</div>
                                <div class="product-card-price">${{ number_format($similar->precio, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <!-- Productos similares por defecto -->
                        <div class="product-card" data-product-id="1">
                            <div class="product-image">
                                <img src="{{ asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/cheseburguer.png') }}" alt="Hamburguesa Clásica" class="product-thumbnail">
                            </div>
                            <div class="product-card-info">
                                <div class="product-name">PRODUCT NAME</div>
                                <div class="product-card-rating">★ ★ ★</div>
                                <div class="product-card-price">$7.00</div>
                            </div>
                        </div>
                        
                        <div class="product-card" data-product-id="2">
                            <div class="product-image">
                                <img src="{{ asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/cheseburguer.png') }}" alt="Hamburguesa Deluxe" class="product-thumbnail">
                            </div>
                            <div class="product-card-info">
                                <div class="product-name">PRODUCT NAME</div>
                                <div class="product-card-rating">★ ★ ★</div>
                                <div class="product-card-price">$9.00</div>
                            </div>
                        </div>
                        
                        <div class="product-card" data-product-id="3">
                            <div class="product-image">
                                <img src="{{ asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/cheseburguer.png') }}" alt="Hamburguesa Vegetariana" class="product-thumbnail">
                            </div>
                            <div class="product-card-info">
                                <div class="product-name">PRODUCT NAME</div>
                                <div class="product-card-rating">★ ★ ★</div>
                                <div class="product-card-price">$8.00</div>
                            </div>
                        </div>
                        
                        <div class="product-card" data-product-id="4">
                            <div class="product-image">
                                <img src="{{ asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/cheseburguer.png') }}" alt="Hamburguesa de Pollo" class="product-thumbnail">
                            </div>
                            <div class="product-card-info">
                                <div class="product-name">PRODUCT NAME</div>
                                <div class="product-card-rating">★ ★ ★</div>
                                <div class="product-card-price">$7.50</div>
                            </div>
                        </div>
                        
                        <div class="product-card" data-product-id="5">
                            <div class="product-image">
                                <img src="{{ asset('assets/images/repo/comida/p_3GwOHUvwOpa8FhM8bMmF02UWBi0vEFqC/cheseburguer.png') }}" alt="Hamburguesa Vegie" class="product-thumbnail">
                            </div>
                            <div class="product-card-info">
                                <div class="product-name">PRODUCT NAME</div>
                                <div class="product-card-rating">★ ★ ★</div>
                                <div class="product-card-price">$9.00</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>
            
            <!-- Reviews Section -->
            <section class="reviews">
                <h2>Reviews</h2>
                
                @forelse($reviews ?? [] as $review)
                    <div class="review">
                        <div class="review-avatar">
                            <img src="{{ $review->usuario->avatar ?? asset('user-avatar.jpg') }}" alt="{{ $review->usuario->nombre }}">
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <div class="reviewer-name">{{ $review->usuario->nombre }}</div>
                                <div class="review-date">{{ \Carbon\Carbon::parse($review->fecha)->format('F d, Y') }}</div>
                            </div>
                            <div class="review-rating">{{ str_repeat('★ ', $review->rating) }}</div>
                            <div class="review-text">
                                <p>{{ $review->comentario }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Reviews por defecto -->
                    <div class="review">
                        <div class="review-avatar">
                            <img src="{{ asset('user-avatar.jpg') }}" alt="John Doe">
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <div class="reviewer-name">John Doe</div>
                                <div class="review-date">August 14, 2024</div>
                            </div>
                            <div class="review-rating">★ ★ ★ ★ ★</div>
                            <div class="review-text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="review">
                        <div class="review-avatar">
                            <img src="{{ asset('user-avatar.jpg') }}" alt="John Doe">
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <div class="reviewer-name">John Doe</div>
                                <div class="review-date">August 14, 2024</div>
                            </div>
                            <div class="review-rating">★ ★ ★</div>
                            <div class="review-text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            </div>
                        </div>
                    </div>
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