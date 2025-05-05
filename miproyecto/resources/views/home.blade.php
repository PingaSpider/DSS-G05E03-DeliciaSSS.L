<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deliciass - Restaurante</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Barra de navegación -->
        <header>
            <div class="navigation-container">
                <!-- Logo -->
                <div class="logo-container">
                    <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Deliciass" class="logo-1">
                </div>
                <!-- Enlaces de navegación -->
                <div class="link-bar-1">
                    <a href="{{ route('menu') }}" class="link-bar-name">Menu</a>
                    |
                    <a href="{{ route('reservaciones.index') }}" class="link-bar-name">Reservar</a>
                    |
                    <a href="{{ route('producto.show') }}" class="link-bar-name">Carta</a>
                </div>
                
                <!-- Botón de pedido -->
                <div class="order-button-container">
                    <button class="button-1-copy">Pedir Online</button>
                </div>
                
                <!-- Avatar de usuario -->
                <div class="avatar-container">
                    <div class="avatar-1">
                        <a href="{{route('user.profile')}}">
                            <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" alt="Avatar" id="avatar">
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Línea vertical lateral -->
        <div class="vertical-line-container">
            <div class="deliciass">Deliciass</div>
            <div class="vertical-line-2"></div>
            <div class="desde-2024-">DESDE 2024</div>
        </div>
        
        <!-- Imagen principal del plato -->
        <div class="hero-image">
            <div class="imagePrincipal">
                <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/SBroNFXYw5C7tcoxcOqnO6ln7CiFlvH4.png') }}" alt="Plato gourmet">
            </div>
        </div>
        
        <!-- Sección de descripción -->
        <section class="descripcion-section">
            <div class="descripcion-container">
                <h2 class="algo-delicioso-hecho-con-amor">{{ $titular ?? 'Algo Delicioso. Hecho con Amor' }}</h2>
                <p class="cada-plato-es-una-muestra-de-nuestra-pasi-n-por-la">
                    {{ $descripcion ?? 'Cada plato es una muestra de nuestra pasión por la buena cocina. Utilizamos ingredientes frescos y de temporada para crear experiencias culinarias únicas que despiertan los sentidos. Nuestro equipo de chefs trabaja con dedicación para ofrecer lo mejor de la gastronomía local con un toque internacional.' }}
                </p>
                <div class="rectangle-1">
                    <div class="ver-men-">Ver Menú</div>
                </div>
            </div>
            
            <div class="imagen-secundaria">
                <div class="imagenDescripcion">
                    <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/8l4lelEDma4bvufvH8FZ8qXyeAdRP0Tz.png') }}" alt="Filete con guarnición">
                </div>
            </div>
        </section>
        
        <!-- Sección de platos más vendidos -->
        <section class="platos-vendidos-section">
            <div class="imagenMasVendidos">
                <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/3EEeFsN67I0pScWFkyFsyGQmplVc4QAF.png') }}" alt="Platos más vendidos">
            </div>
            
            <div class="platos-vendidos-overlay">
                <div class="rectangle-2">
                    Platos Más Vendidos
                </div>
                <div class="rectangle-3">
                    <div class="m-s-informaci-n">Más Información</div>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Footer -->
    <footer class="footer rectangle-6">
        <div class="container">
            <div class="footer-container">
                <div class="footer-section">
                    <h3 class="grupo-footer">Dirección</h3>
                    <div class="grupo-24">
                        <p>{{ $direccion->calle ?? 'Calle Los Olivos 34' }}</p>
                        <p>{{ $direccion->ciudad ?? 'Madrid, España' }}</p>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3 class="grupo-footer-1">Horarios</h3>
                    <div class="grupo-25">
                        <p>{{ $horarios->semana ?? 'Lun-Vie: 12:30 - 22:00' }}</p>
                        <p>{{ $horarios->finde ?? 'Sab-Dom: 13:00 - 23:30' }}</p>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3 class="grupo-footer-2">Contáctanos</h3>
                    <div class="grupo-26">
                        <p>Teléfono: {{ $contacto->telefono ?? '+34 555 123 456' }}</p>
                        <p>E-mail: {{ $contacto->email ?? 'info@deliciass.com' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-logo">
                <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo-naranja.png') }}" alt="Deliciass">
            </div>
        </div>
    </footer>
</body>
</html>