<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido - Delicias de la Vida</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/confirmareserva.css') }}">
    
</head>
<body>
    <div class="container">
        <!-- Navegación -->
        <nav class="navegacion">
            <ul>
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li class="active">Confirmación</li>
            </ul>
        </nav>

        <!-- Contenido Principal -->
        <main class="contenido">
            <h1>Confirmación de Pedido</h1>
            
            <section class="detalles-pedido">
                <h2>Detalles del pedido</h2>
                <div class="pedido-box">
                    <p><strong>Código de Pedido:</strong> {{ $pedido->cod }}</p>
                    <p><strong>Nombre:</strong> {{ $pedido->usuario->nombre }}</p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}</p>
                    <p><strong>Estado:</strong> {{ ucfirst($pedido->estado) }}</p>
                    <p><strong>Total a Pagar:</strong> ${{ number_format($pedido->total, 2) }}</p>
                </div>
                
                <h3>Productos solicitados:</h3>
                <div class="productos-list">
                    @foreach($pedido->lineasPedido as $linea)
                        <div class="producto-item">
                            <span>{{ $linea->producto->nombre }}</span>
                            <span>x{{ $linea->cantidad }}</span>
                            <span>${{ number_format($linea->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="mensaje-confirmacion">
                <h2>{{ $mensaje->titulo }}</h2>
                <p>{{ $mensaje->subtitulo }}</p>
                
                @if(session('success'))
                <div class="alerta-exito">
                    {{ session('success') }}
                </div>
                @endif
            </section>

            <div class="botones-accion">
                <a href="{{ route('home') }}" class="btn-volver">Volver a Inicio</a>
                <a href="{{ route('menu') }}" class="btn-menu">Ver Menú</a>
                <a href="{{ route('user.showOrder','pedido->cod') }}" class="btn-pedidos">Mis Pedidos</a>
            </div>
        </main>

        <!-- Pie de página con logo -->
        <footer class="footer">
            <div class="logo-container">
                <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Delicias de la Vida" class="logo">
            </div>
            @if(isset($footer))
            <div class="footer-info">
                <div class="footer-section">
                    <h3>{{ $footer->direccion->titulo }}</h3>
                    <p>{{ $footer->direccion->calle }}</p>
                    <p>{{ $footer->direccion->ciudad }}</p>
                </div>
                <div class="footer-section">
                    <h3>{{ $footer->horarios->titulo }}</h3>
                    <p>{{ $footer->horarios->semana }}</p>
                    <p>{{ $footer->horarios->finde }}</p>
                </div>
                <div class="footer-section">
                    <h3>{{ $footer->contacto->titulo }}</h3>
                    <p>{{ $footer->contacto->email }}</p>
                    <p>{{ $footer->contacto->telefono }}</p>
                </div>
            </div>
            @endif
        </footer>
    </div>
</body>
</html>