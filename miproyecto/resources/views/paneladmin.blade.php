<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de Administración</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sesion.css') }}">
    <script src="{{ asset('js/sesionHandler.js') }}" defer></script>
    <style>
        body {
            background-color: #fffbeb;
            font-family: 'Raleway', sans-serif;
        }
        
        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        h1 {
            color: #e67e22;
            text-align: center;
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        hr {
            border: none;
            height: 1px;
            background-color: #e0e0e0;
            margin: 20px 0 30px 0;
        }
        
        .admin-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .menu-button {
            background-color: #e67e22;
            color: white;
            padding: 15px 30px;
            border-radius: 6px;
            width: 300px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .menu-button:hover {
            background-color: #d35400;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .submenu-button {
            background-color: #f39c12;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            width: 250px;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        
        .submenu-button:hover {
            background-color: #e67e22;
            transform: translateY(-1px);
        }
        
        .menu-section {
            margin-bottom: 20px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .products-submenu {
            display: none;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
            align-items: center;
        }
        
        .show-submenu {
            display: flex;
        }
        
        .back-link {
            margin-top: 30px;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            display: block;
        }
        
        .back-link:hover {
            color: #2c3e50;
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Estilos específicos para el avatar en panel admin */
        #avatar {
            position: relative;
        }
        
        /* Sobreescribir estilos del avatar container específicamente para el panel admin */
        .container .avatar-container {
            position: absolute;
            top: 30px;
            right: 30px;
            z-index: 10;
        }
        
        /* Ajustar el menú dropdown para que aparezca correctamente */
        .container .avatar-container .dropdown-menu {
            right: 0;
            top: 45px;
        }
        
        /* Añadir espacio para evitar que el avatar tape el título */
        .container h1 {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel de Administración</h1>
        <hr>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        <!-- Estructura HTML para el avatar con menú desplegable -->
        <div class="avatar-container">
            <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" id="avatar" class="avatar" alt="Avatar">
            <div class="dropdown-menu" id="avatarMenu">
                @auth
                    <!-- Usuario autenticado: muestra opciones de perfil y cerrar sesión -->
                    <a href="{{ route('user.profile') }}">Mi Perfil</a>
                    
                    @if(Auth::user()->esAdmin())
                        <a href="{{ route('paneladmin') }}">Panel Admin</a>
                    @endif
                    
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
        <div class="admin-menu">
            <div class="menu-section">
                <a href="{{ route('productos.paginate') }}" class="menu-button">
                    Productos
                </a>
            </div>
            
            <div class="menu-section">
                <a href="{{ route('pedidos.paginate') }}" class="menu-button" id="pedidos-btn">
                    Pedidos
                </a>
                <div class="products-submenu" id="pedidos-submenu">
                    <a href="{{ route('pedidos.paginate') }}" class="submenu-button">Pedidos</a>
                    <a href="{{ route('lineaPedidos.paginate') }}" class="submenu-button">Líneas de Pedido</a>
                </div>
            </div>
            
            <div class="menu-section">
                <a href="{{ route('mesas.paginate') }}" class="menu-button">
                    Mesas
                </a>
            </div>
            
            <div class="menu-section">
                <a href="{{ route('reservas.paginate') }}" class="menu-button">
                    Reservas
                </a>
            </div>
            
            <div class="menu-section">
                <a href="{{ route('usuarios.paginate') }}" class="menu-button">
                    Usuarios
                </a>
            </div>
            
        </div>
    </div>
    
    <script>
        // JavaScript para mostrar/ocultar submenús
        document.addEventListener('DOMContentLoaded', function() {
            const pedidosBtn = document.getElementById('pedidos-btn');
            const pedidosSubmenu = document.getElementById('pedidos-submenu');
            
            // Toggle para el submenú de pedidos
            pedidosBtn.addEventListener('click', function(e) {
                e.preventDefault();
                pedidosSubmenu.classList.toggle('show-submenu');
                
                // Ocultar el otro submenú si está abierto
                productosSubmenu.classList.remove('show-submenu');
                
                // Si el submenú se muestra, esperar un momento y luego redirigir
                if (!pedidosSubmenu.classList.contains('show-submenu')) {
                    window.location.href = pedidosBtn.getAttribute('href');
                }
            });
        });
    </script>
</body>
</html>


<!--Entrega2-->