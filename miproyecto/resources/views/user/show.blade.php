<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DeliciaSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>

<body>
    <div class="profile-container">
        <!-- Header del usuario -->
        <div class="user-header">
            <div class="user-info">
                <div class="user-avatar">
                    <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" alt="Avatar de usuario">
                </div>
                <div class="user-name">{{ $usuario->nombre }}</div>
            </div>
        </div>
        
        <!-- Contenido -->
        <div class="user-content">
            <div class="user-card">
                <h3 class="card-title">Información del Usuario</h3>
                
                <div class="user-detail">
                    <div class="detail-label">Nombre:</div>
                    <div class="detail-value">{{ $usuario->nombre }}</div>
                </div>
                
                <div class="user-detail">
                    <div class="detail-label">Correo:</div>
                    <div class="detail-value">{{ $usuario->email }}</div>
                </div>
                
                <div class="user-detail">
                    <div class="detail-label">Teléfono:</div>
                    <div class="detail-value">{{ $usuario->telefono }}</div>
                </div>
            </div>
        </div>
        <div class="action-links">
        <a href="{{ route('usuarios.paginate') }}" class="link-back">Volver al listado</a>
        </div>
    </div>
</body>
</html>


<!--Entrega2-->