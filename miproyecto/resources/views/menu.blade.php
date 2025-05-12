<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menú - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7b1fbf0d4d.js" crossorigin="anonymous"></script>
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Pasar el estado de autenticación y el código del menú a JavaScript
        window.isAuthenticated = {{ $isAuthenticated ? 'true' : 'false' }};
        window.menuDelDiaCod = '{{ $menuDelDiaCod }}';
    </script>
    <script src="{{ asset('js/menu.js') }}" defer></script>
    <script src="{{ asset('js/sesionHandler.js') }}" defer></script>
    <script src="{{ asset('js/authNeeded.js') }}"></script>
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
            <nav class="main-nav">
                <ul>
                    |
                    <li><a href="{{ route('home') }}">Home</a></li>
                    |
                    <li><a href="{{ route('reservaciones.index') }}">Reservar</a></li>
                    |
                    <a href="{{ route('producto.show') }}" class="link-bar-name">Carta</a>
                    
                </ul>
            </nav>
            <div class="actions">
                <!-- Botón de carrito - Mostrar solo si está autenticado -->
                @auth
                    <a href="{{ route('carrito.view') }}" class="to-cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                @endauth
                <div class="avatar-container">
                    <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" alt="User avatar">
                    <div class="dropdown-menu" id=avatarMenu>
                        @auth
                            <a href="{{ route('user.profile') }}" class="dropdown-item">Perfil</a>
                            <a href="{{ route('logout') }}" class="dropdown-item">Cerrar sesión</a>
                        @else
                            <a href="{{ route('login') }}" class="dropdown-item">Iniciar sesión</a>
                            <a href="{{ route('registro') }}" class="dropdown-item">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="content">
            <!-- Menu Title -->
            <h1 class="menu-title" id="menuTitle">MENU</h1>
            
            <!-- Menu Categories -->
            <div class="menu-categories">
                <button class="menu-category active" data-target="menuDelDia" onclick="openMenuModal()">Menus de la Semana</button>
            </div>
            
            <!-- Menu Content Sections -->
            <div class="menu-content">
                <!-- Menu del Día (Default View) -->
                <section class="menu-section active" id="menuDelDia">
                    <h2 class="section-title">Menu del {{ $diaActual ?? 'Día' }}</h2>
                    <div class="menu-price">{{ $menuDelDia->precio . '€' ?? '12.99€' }}</div>
                    <div class="menu-note">{{ $menuDelDia->nota ?? 'Se puede elegir 2 platos' }}</div>
                    
                    <div class="menu-courses">
                        @foreach($menuDelDia->cursos ?? [] as $curso)
                        <div class="menu-course">
                            <h3>{{ $curso['titulo'] }}</h3>
                            <div class="course-items">
                                @foreach($curso['platos'] as $plato)
                                <div class="menu-item">
                                    <div class="item-image">
                                        <img src="{{ $plato['imagen'] }}" alt="{{ $plato['descripcion'] }}">
                                    </div>
                                    <div class="item-description">{{ $plato['descripcion'] }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="menu-actions">
                            <button class="add-to-cart-btn">Añadir al carrito</button>
                            <button class="order-now-btn">Pedir ahora</button>
                        </div>
                    </div>
                    
                    <!-- Productos Recomendados -->
                    <div class="recommended-products">
                        <h2>{{ $recomendados->titulo ?? 'Productos recomendados' }}</h2>
                        <p>{{ $recomendados->descripcion ?? 'Nuestras delicias más populares seleccionadas especialmente para ti.' }}</p>
                        
                        <div class="product-grid">
                            @foreach($recomendados->productos ?? [] as $producto)
                            <div class="product-card" >
                                <div class="product-image">
                                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}">
                                </div>
                                <div class="product-info¡">
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
                @foreach($categoriasSecciones ?? [] as $seccion)
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

    <!-- Modal de Menús de la Semana -->
    <div id="menuSemanaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeMenuModal()">&times;</span>
            <h2>Menús de la Semana</h2>
            
            <div class="weekday-menus">
                @foreach($menusSemana ?? [] as $diaMenu)
                <div class="weekday-menu {{ $diaMenu['isToday'] ? 'today' : '' }}">
                    <div class="weekday-header">
                        <span class="weekday-name">{{ $diaMenu['dia'] }}</span>
                        @if($diaMenu['isToday'])
                            <span class="today-indicator">HOY</span>
                        @endif
                    </div>
                    
                    @php
                        $menuData = collect($menus)->firstWhere('cod', $diaMenu['menu']);
                    @endphp
                    
                    @if($menuData)
                        <div class="menu-day-info">
                            <strong>{{ $menuData->nombre }}</strong> - {{ $menuData->precio }}€
                        </div>
                        
                        <div class="day-menu-items">
                            @foreach($menuData->cursos as $curso)
                            <div class="day-menu-item">
                                <h4>{{ $curso['titulo'] }}</h4>
                                <div class="day-dishes-grid">
                                    @foreach($curso['platos'] as $plato)
                                        <div class="day-dish-item">
                                            <img src="{{ $plato['imagen'] }}" alt="{{ $plato['nombre'] }}">
                                            <span>{{ $plato['nombre'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p>Menú no disponible</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function openMenuModal() {
            document.getElementById('menuSemanaModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeMenuModal() {
            document.getElementById('menuSemanaModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('menuSemanaModal');
            if (event.target == modal) {
                closeMenuModal();
            }
        }

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMenuModal();
            }
        });
    </script>
</body>
</html>