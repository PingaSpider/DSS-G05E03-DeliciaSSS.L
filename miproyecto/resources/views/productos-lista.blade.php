<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Productos - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/producto.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sesion.css') }}">
    <script src="{{ asset('js/producto.js') }}"></script>
    <script src="{{ asset('js/sesionHandler.js') }}" defer></script>

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
                    <a href="{{ route('reservaciones.index') }}" class="link-bar-name">Reservas</a>
                </div>
                
                <!-- Acciones (a la derecha) -->
                <div class="actions">
                    <!-- Botón de carrito -->
                    <a href="{{ route('carrito.view') }}" class="to-cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    
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

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Descubre Nuestros Productos</h1>
                <p>La mejor selección de comidas y bebidas para ti</p>
            </div>
        </section>

        <!-- Main Content -->
        <main class="content">
            <!-- Search and Filter Section -->
            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="product-search" placeholder="Buscar productos...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="category-tabs">
                <div class="category-tab active" data-category="todos">Todos</div>
                <div class="category-tab" data-category="desayunos">Desayunos</div>
                <div class="category-tab" data-category="hamburguesas">Hamburguesas</div>
                <div class="category-tab" data-category="pizzas">Pizzas</div>
                <div class="category-tab" data-category="bebidas">Bebidas</div>
                <div class="category-tab" data-category="postres">Postres</div>
                <div class="category-tab" data-category="menus">Menús</div>
            </div>

            <!-- Category Sections -->
            <!-- Todos los productos -->
            <div class="category-section active" id="todos">
                <h2 class="category-title">Todos los Productos</h2>
                <div class="product-grid">
                    @forelse($productos as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay productos disponibles en este momento.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class="mt-4 flex justify-center">
                    {{ $productos->appends(request()->query())->links() }}
                </div>
            </div>

            <!-- Desayunos -->
            <div class="category-section" id="desayunos">
                <h2 class="category-title">Desayunos</h2>
                <div class="product-grid">
                    @forelse($desayunos as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay desayunos disponibles.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class="mt-4 flex justify-center">
                    {{ $desayunos->appends(['todos_page' => request('todos_page')])->links() }}
                </div>
            </div>

            <!-- Hamburguesas -->
            <div class="category-section" id="hamburguesas">
                <h2 class="category-title">Hamburguesas</h2>
                <div class="product-grid">
                    @forelse($hamburguesas as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay hamburguesas disponibles.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class="mt-4 flex justify-center">
                    {{ $hamburguesas->appends(['todos_page' => request('todos_page')])->links() }}
                </div>
            </div>

            <!-- Pizzas -->
            <div class="category-section" id="pizzas">
                <h2 class="category-title">Pizzas</h2>
                <div class="product-grid">
                    @forelse($pizzas as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay pizzas disponibles.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class="mt-4 flex justify-center">
                    {{ $pizzas->appends(['todos_page' => request('todos_page')])->links() }}
                </div>
            </div>

            <!-- Bebidas -->
            <div class="category-section" id="bebidas">
                <h2 class="category-title">Bebidas</h2>
                <div class="product-grid">
                    @forelse($bebidas as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay bebidas disponibles.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class="mt-4 flex justify-center">
                    {{ $bebidas->appends(['todos_page' => request('todos_page')])->links()}}
                </div>
            </div>

            <!-- Postres -->
            <div class="category-section" id="postres">
                <h2 class="category-title">Postres</h2>
                <div class="product-grid">
                    @forelse($postres as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay postres disponibles.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class=""mt-4 flex justify-center">
                    {{ $postres->appends(['todos_page' => request('todos_page')])->links() }}
                </div>
            </div>

            <!-- Menús -->
            <div class="category-section" id="menus">
                <h2 class="category-title">Menús Especiales</h2>
                <div class="product-grid">
                    @forelse($menus as $producto)
                        @include('partials.product-card', ['producto' => $producto])
                    @empty
                        <p>No hay menús disponibles.</p>
                    @endforelse
                </div>
                
                <!-- Enlaces de paginación -->
                <div class="mt-4 flex justify-center">
                    {{ $menus->appends(['todos_page' => request('todos_page')])->links() }}
                </div>
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