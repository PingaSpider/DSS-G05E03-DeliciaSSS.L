<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva - Delicias de la Vida</title>
    <link rel="stylesheet" href="{{ asset('css/confirmareserva.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="container">
        <!-- Navegación -->
        <nav class="navegacion">
            <ul>
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li>|</li>
                <li><a href="{{ route('reserva') }}">Reserva</a></li>
                <li>|</li>
                <li class="active">Confirmación</li>
            </ul>
        </nav>

        <!-- Contenido Principal -->
        <main class="contenido">
            <h1>Confirmación de Reserva</h1>
            
            <section class="detalles-reserva">
                <h2>Detalles de Reserva</h2>
                <div class="reserva-box">
                    <p><strong>Nombre:</strong> {{ $reserva->nombre ?? 'Antonio Pérez' }}</p>
                    <p><strong>Cantidad de Personas:</strong> {{ $reserva->personas ?? '2' }}</p>
                    <p><strong>Fecha:</strong> {{ $reserva->fecha ?? '03/12/2025' }}</p>
                    <p><strong>Hora:</strong> {{ $reserva->hora ?? '15:15' }}</p>
                </div>
            </section>

            <section class="mensaje-confirmacion">
                <h2>{{ $mensaje->titulo ?? '¡Gracias por reservar!' }}</h2>
                <p>{{ $mensaje->subtitulo ?? 'Se te enviará una confirmación por email' }}</p>
                
                @if(session('success'))
                <div class="alerta-exito">
                    {{ session('success') }}
                </div>
                @endif
            </section>

            <div class="botones-accion">
                <a href="{{ route('home') }}" class="btn-volver">Volver a Inicio</a>
                <a href="{{ route('menu') }}" class="btn-menu">Ver Menú</a>
            </div>
        </main>

        <!-- Pie de página con logo -->
        <footer class="footer">
            <div class="logo-container">
                <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Delicias de la Vida" class="logo">
            </div>
        </footer>
    </div>
</body>
</html>