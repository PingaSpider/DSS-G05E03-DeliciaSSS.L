<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7b1fbf0d4d.js" crossorigin="anonymous"></script>
    <link href="{{ asset('css/cssFuturo/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <script src="{{ asset('js/menu.js') }}" defer></script>
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
                    |
                    <li><a href="{{ route('home') }}">Home</a></li>
                    |
                    <li><a href="{{ route('reservaciones.index') }}">Reservar</a></li>
                    |
                </ul>
            </nav>
            <div class="actions">
                <button class="btn-primary">Pedir Online</button>
                <a href="#" class="user-icon">
                    <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" alt="Usuario" class="icon">
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content">
            <!-- Menu Title -->
            <h1 class="menu-title" id="menuTitle">MENU</h1>
            
            <!-- Menu Categories -->
            <div class="menu-categories">
                <button class="menu-category active" data-target="menuDelDia">Menu del Día</button>
                @foreach($categoriasSecciones ?? [
                    ['id' => 'desayunos', 'nombre' => 'Desayunos'],
                    ['id' => 'combinados', 'nombre' => 'Combinados'],
                ] as $categoria)
                <button class="menu-category" data-target="{{ $categoria['id'] }}">{{ $categoria['nombre'] }}</button>
                @endforeach
            </div>
            
            <!-- Menu Content Sections -->
            <div class="menu-content">
                <!-- Menu del Día (Default View) -->
                <section class="menu-section active" id="menuDelDia">
                    <h2 class="section-title">Menu del Día</h2>
                    <div class="menu-price">{{ $menuDelDia->precio ?? '12.99€' }}</div>
                    <div class="menu-note">{{ $menuDelDia->nota ?? 'Se puede elegir 2 platos' }}</div>
                    
                    <div class="menu-courses">
                        @foreach($menuDelDia->cursos ?? [
                            ['titulo' => 'Primero a Elegir', 'platos' => [
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto']
                            ]],
                            ['titulo' => 'Segundo a Elegir', 'platos' => [
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto']
                            ]],
                            ['titulo' => 'Postre', 'platos' => [
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto'],
                                ['imagen' => 'placeholder.jpg', 'descripcion' => 'Descripción del Producto']
                            ]]
                        ] as $curso)
                        <div class="menu-course">
                            <h3>{{ $curso['titulo'] }}</h3>
                            <div class="course-items">
                                @foreach($curso['platos'] as $plato)
                                <div class="menu-item">
                                    <div class="item-image">
                                        <img src="{{ asset('assets/images/comida/' . $plato['imagen']) }}" alt="{{ $plato['descripcion'] }}">
                                    </div>
                                    <div class="item-description">{{ $plato['descripcion'] }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Productos Recomendados -->
                    <div class="recommended-products">
                        <h2>{{ $recomendados->titulo ?? 'Productos recomendados' }}</h2>
                        <p>{{ $recomendados->descripcion ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.' }}</p>
                        
                        <div class="product-grid">
                            @foreach($recomendados->productos ?? [
                                ['imagen' => 'placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 3, 'precio' => '13€'],
                                ['imagen' => 'placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 3, 'precio' => '13€'],
                                ['imagen' => 'placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 3, 'precio' => '13€'],
                                ['imagen' => 'placeholder.jpg', 'nombre' => 'PRODUCT NAME', 'rating' => 3, 'precio' => '13€']
                            ] as $producto)
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ asset('assets/images/comida/' . $producto['imagen']) }}" alt="test">
                                </div>
                                <div class="product-info">
                                <div class="product-name">{{ $producto['nombre'] }}</div>
                                <div class="product-rating">{{ str_repeat('★', $producto['rating']) }}</div>
                                <div class="product-price">{{ $producto['precio'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                
                <!-- Category Sections (Hidden initially) -->
                @foreach($categoriasSecciones ?? [
                    ['id' => 'desayunos', 'titulo' => 'Desayunos', 'items' => [
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Tostada con Tomate', 'descripcion' => 'Pan artesanal con tomate rallado y aceite de oliva', 'precio' => '3.50€'],
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Croissant con Jamón y Queso', 'descripcion' => 'Croissant recién horneado con jamón serrano y queso', 'precio' => '4.25€'],
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Café con Bollería', 'descripcion' => 'Café a elegir con bollería del día', 'precio' => '3.75€']
                    ]],
                    ['id' => 'combinados', 'titulo' => 'Combinados', 'items' => [
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Combo Especial', 'descripcion' => 'Hamburguesa, patatas y bebida a elegir', 'precio' => '9.95€']
                    ]],
                    ['id' => 'bebidas', 'titulo' => 'Bebidas', 'items' => [
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Coca Cola', 'descripcion' => 'Refresco de cola 330ml', 'precio' => '2.50€']
                    ]],
                    ['id' => 'hamburguesas', 'titulo' => 'Hamburguesas', 'items' => [
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Hamburguesa Clásica', 'descripcion' => 'Carne de ternera, lechuga, tomate y queso', 'precio' => '7.50€']
                    ]],
                    ['id' => 'pizzas', 'titulo' => 'Pizzas', 'items' => [
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Pizza Margarita', 'descripcion' => 'Tomate, mozzarella y albahaca', 'precio' => '8.95€']
                    ]],
                    ['id' => 'postres', 'titulo' => 'Postres', 'items' => [
                        ['imagen' => 'placeholder.jpg', 'nombre' => 'Tarta de Chocolate', 'descripcion' => 'Tarta casera con chocolate belga', 'precio' => '4.50€']
                    ]]
                ] as $seccion)
                <section class="menu-section" id="{{ $seccion['id'] }}">
                    <h2 class="section-title">{{ $seccion['titulo'] }}</h2>
                    <div class="category-items">
                        @foreach($seccion['items'] as $item)
                        <div class="category-item">
                            <div class="item-image">
                                <img src="{{ asset('assets/images/comida' . $item['imagen']) }}" alt="{{ $item['nombre'] }}">
                            </div>
                            <div class="item-details">
                                <h3>{{ $item['nombre'] }}</h3>
                                <p>{{ $item['descripcion'] }}</p>
                                <div class="item-price">{{ $item['precio'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endforeach
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