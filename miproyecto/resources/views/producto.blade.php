<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $producto->nombre ?? 'Detalle de Producto' }} - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7b1fbf0d4d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/producto.css') }}">
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
                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->cod }}">
                            <button type="submit" class="btn-primary add-to-cart">Add To Cart</button>
                        </form>
                        <button class="btn-wishlist" onclick="toggleWishlist('{{ $producto->cod }}')">
                            <i class="heart-icon">♡</i>
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
                        <div class="product-card" data-product-id="{{ $similar->cod }}">
                            <a href="{{ route('producto.show', $similar->cod) }}" class="product-link">
                                <div class="product-image">
                                    <img src="{{ $similar->imagen_url }}" alt="{{ $similar->nombre }}" class="product-thumbnail">
                                </div>
                                <div class="product-card-info">
                                    <div class="product-name">{{ $similar->nombre }}</div>
                                    <div class="product-card-rating">
                                        @for($i = 0; $i < $similar->rating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($i = $similar->rating; $i < 5; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                    <div class="product-card-price">${{ number_format($similar->pvp, 2) }}</div>
                                </div>
                            </a>
                        </div>
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

    <script>
        function toggleWishlist(productId) {
            fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const heartIcon = document.querySelector('.heart-icon');
                    heartIcon.textContent = data.inWishlist ? '♥' : '♡';
                    heartIcon.style.color = data.inWishlist ? 'red' : 'inherit';
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Manejo del formulario de añadir al carrito
        document.querySelector('.add-to-cart-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto añadido al carrito');
                    // Actualizar contador del carrito si existe
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart.length;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>